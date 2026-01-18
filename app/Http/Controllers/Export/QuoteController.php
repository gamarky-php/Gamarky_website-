<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\ExportQuote;
use App\Models\ExportShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class QuoteController extends Controller
{
    public function index()
    {
        // Authorization: show only user's quotes

        $quotes = ExportQuote::with('shipment')
            ->whereHas('shipment', function ($q) {
                $q->where('created_by', Auth::id());
            })
            ->latest()
            ->paginate(15);

        return view('front.export.quotes.index', compact('quotes'));
    }

    public function generate(ExportShipment $shipment, Request $request)
    {
        // Authorization: only owner can generate quotes
        if ($shipment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'incoterm_final' => 'required|in:EXW,FOB,CFR,CIF',
            'margin_pct' => 'nullable|numeric|min:0|max:100',
        ]);

        // حساب التكلفة حسب incoterm
        $totalCost = $this->calculateCostByIncoterm(
            $shipment,
            $validated['incoterm_final']
        );

        // حساب سعر البيع مع الهامش
        $marginPct = $validated['margin_pct'] ?? 0;
        $sellPrice = $totalCost * (1 + ($marginPct / 100));

        // توليد رقم العرض
        $quoteNo = 'EXP-'.date('Ymd').'-'.str_pad($shipment->id, 4, '0', STR_PAD_LEFT);

        // إنشاء العرض
        $quote = ExportQuote::create([
            'export_shipment_id' => $shipment->id,
            'quote_no' => $quoteNo,
            'incoterm_final' => $validated['incoterm_final'],
            'total_cost' => $totalCost,
            'unit_cost' => null, // يمكن حسابه لاحقاً حسب الوحدات
            'margin_pct' => $marginPct,
            'sell_price' => $sellPrice,
            'currency' => $shipment->currency,
            'status' => 'draft',
        ]);

        // إذا كان الطلب JSON، إرجاع JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء العرض بنجاح',
                'quote_id' => $quote->id,
                'id' => $quote->id,
                'quote_no' => $quote->quote_no,
                'redirect' => route('export.quotes.show', $quote->id),
            ], 201);
        }

        return redirect()
            ->route('export.quotes.show', $quote->id)
            ->with('status', 'تم إنشاء العرض بنجاح');
    }

    public function show(ExportQuote $quote)
    {
        // Authorization: only owner can view
        if ($quote->shipment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $quote->load(['shipment.costs', 'shipment.client']);

        return view('front.export.quotes.show', compact('quote'));
    }

    public function pdf(ExportQuote $quote)
    {
        // Authorization: only owner can export
        if ($quote->shipment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pdf = new \App\Pdf\ExportQuotePdf($quote);

        return $pdf->download();
    }

    public function excel(ExportQuote $quote)
    {
        // Authorization: only owner can export
        if ($quote->shipment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return Excel::download(
            new \App\Exports\ExportQuoteExport($quote),
            'quote-'.$quote->quote_no.'.xlsx'
        );
    }

    public function send(ExportQuote $quote, Request $request)
    {
        // Authorization: only owner can send
        if ($quote->shipment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:1000',
        ]);

        // TODO: إرسال البريد الإلكتروني
        // Mail::to($validated['email'])->send(new QuoteMail($quote, $validated['message'] ?? ''));

        $quote->update(['status' => 'sent']);

        return back()->with('status', 'تم إرسال العرض بنجاح إلى '.$validated['email']);
    }

    /**
     * حساب التكلفة حسب الإنكوترمز
     */
    protected function calculateCostByIncoterm(ExportShipment $shipment, string $incoterm): float
    {
        $costs = $shipment->costs;
        $total = 0;

        // Categories المتضمنة حسب Incoterm
        $includedCategories = match ($incoterm) {
            'EXW' => ['manufacturing', 'packing'],
            'FOB' => ['manufacturing', 'packing', 'local_clearance', 'port_fees', 'local_trucking'],
            'CFR' => ['manufacturing', 'packing', 'local_clearance', 'port_fees', 'local_trucking', 'freight'],
            'CIF' => ['manufacturing', 'packing', 'local_clearance', 'port_fees', 'local_trucking', 'freight', 'insurance'],
            default => []
        };

        foreach ($costs as $cost) {
            if (in_array($cost->category, $includedCategories)) {
                // تحويل العملة إذا لزم الأمر
                if ($cost->currency === $shipment->currency) {
                    $total += $cost->amount;
                } else {
                    // تطبيق سعر الصرف (تبسيط - يمكن تحسينه)
                    $total += $cost->amount * $shipment->fx_rate;
                }
            }
        }

        return round($total, 2);
    }
}
