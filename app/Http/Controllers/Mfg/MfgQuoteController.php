<?php

namespace App\Http\Controllers\Mfg;

use App\Exports\MfgQuoteExport;
use App\Http\Controllers\Controller;
use App\Models\MfgCostRun;
use App\Models\MfgQuote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MfgQuoteController extends Controller
{
    public function index()
    {
        $quotes = MfgQuote::with(['costRun.product'])
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(15);

        return view('front.mfg.quotes.index', compact('quotes'));
    }

    public function generate(Request $request, MfgCostRun $run)
    {
        if ($run->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'margin_pct' => 'nullable|numeric|min:0|max:100',
            'qty' => 'required|numeric|min:1',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Use margin from request or from run
        $marginPct = $validated['margin_pct'] ?? $run->margin_pct ?? 20;

        $unitCost = $run->unit_cost;
        $unitPrice = $unitCost / (1 - $marginPct / 100);
        $totalAmount = $unitPrice * $validated['qty'];

        // Generate unique quote number
        $quoteNumber = 'MFQ-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));

        $quote = MfgQuote::create([
            'quote_number' => $quoteNumber,
            'mfg_cost_run_id' => $run->id,
            'client_name' => $validated['client_name'] ?? null,
            'client_email' => $validated['client_email'] ?? null,
            'client_phone' => $validated['client_phone'] ?? null,
            'unit_cost' => $unitCost,
            'margin_pct' => $marginPct,
            'unit_price' => $unitPrice,
            'qty' => $validated['qty'],
            'total_amount' => $totalAmount,
            'currency' => $run->currency,
            'valid_until' => $validated['valid_until'] ?? now()->addDays(30),
            'notes' => $validated['notes'] ?? null,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'quote_id' => $quote->id,
                'redirect' => route('mfg.quotes.show', $quote->id),
            ]);
        }

        return redirect()
            ->route('mfg.quotes.show', $quote->id)
            ->with('status', 'تم إنشاء عرض السعر بنجاح');
    }

    public function show(MfgQuote $quote)
    {
        $quote->load([
            'costRun.product',
            'costRun.bomItems',
            'costRun.ops',
            'costRun.overheads',
        ]);

        if ($quote->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('front.mfg.quotes.show', compact('quote'));
    }

    public function pdf(MfgQuote $quote)
    {
        $quote->load([
            'costRun.product',
            'costRun.bomItems',
            'costRun.ops',
            'costRun.overheads',
        ]);

        if ($quote->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pdf = Pdf::loadView('pdf.mfg-quote', compact('quote'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial',
            ]);

        return $pdf->download($quote->quote_number.'.pdf');
    }

    public function excel(MfgQuote $quote)
    {
        $quote->load([
            'costRun.product',
            'costRun.bomItems',
            'costRun.ops',
            'costRun.overheads',
        ]);

        if ($quote->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return Excel::download(
            new MfgQuoteExport($quote),
            $quote->quote_number.'.xlsx'
        );
    }
}
