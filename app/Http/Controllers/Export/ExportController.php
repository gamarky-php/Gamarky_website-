<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\ExportCost;
use App\Models\ExportShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function index()
    {
        // Authorization: only show user's own shipments

        $shipments = ExportShipment::with('quotes')
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(15);

        return view('front.export.shipments.index', compact('shipments'));
    }

    public function calculator()
    {
        // TODO: اجلب الإعلانات من DB لو متاحة
        // $cards = Ad::active()->forUser(auth()->id())->latest()->take(4)->get()->map(fn($ad)=>[
        //     'title'=>$ad->title, 'lines'=>$ad->features, 'cta'=>['text'=>$ad->cta_text,'url'=>$ad->cta_url], 'icon'=>$ad->icon
        // ])->toArray();
        $cards = []; // إن لم توجد بيانات، دَع القالب يضع الافتراضي
        
        return view('front.export.calculator', compact('cards'));
    }

    public function create()
    {
        // Authorization: authenticated users can create

        // TODO: اجلب الإعلانات من DB لو متاحة
        // $cards = Ad::active()->forUser(auth()->id())->latest()->take(4)->get()->map(fn($ad)=>[
        //     'title'=>$ad->title, 'lines'=>$ad->features, 'cta'=>['text'=>$ad->cta_text,'url'=>$ad->cta_url], 'icon'=>$ad->icon
        // ])->toArray();
        $cards = []; // إن لم توجد بيانات، دَع القالب يضع الافتراضي

        return view('front.export.calculator', [
            'incoterms' => ['EXW', 'FOB', 'CFR', 'CIF'],
            'methods' => ['sea' => 'بحري', 'air' => 'جوي', 'land' => 'بري'],
            'currencies' => ['USD', 'EUR', 'SAR', 'AED'],
            'cards' => $cards,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', ExportShipment::class);

        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'origin_country' => 'nullable|string|max:100',
            'pol' => 'nullable|string|max:100',
            'pod' => 'nullable|string|max:100',
            'incoterm' => 'required|in:EXW,FOB,CFR,CIF',
            'method' => 'required|in:sea,air,land',
            'container_type' => 'nullable|string|max:50',
            'weight_ton' => 'nullable|numeric|min:0',
            'volume_cbm' => 'nullable|numeric|min:0',
            'etd' => 'nullable|date',
            'currency' => 'required|string|max:10',
            'fx_rate' => 'required|numeric|min:0',
            'costs' => 'required|array',
            'costs.*.line_name' => 'required|string|max:255',
            'costs.*.category' => 'required|in:manufacturing,packing,local_clearance,port_fees,local_trucking,freight,insurance,bank,docs,extras,profit,final_price',
            'costs.*.col_index' => 'required|integer|min:1',
            'costs.*.amount' => 'required|numeric|min:0',
            'costs.*.currency' => 'required|string|max:10',
            'costs.*.meta' => 'nullable|array',
        ]);

        // إنشاء الشحنة
        $shipment = ExportShipment::create([
            'client_id' => $validated['client_id'] ?? null,
            'origin_country' => $validated['origin_country'] ?? null,
            'pol' => $validated['pol'] ?? null,
            'pod' => $validated['pod'] ?? null,
            'incoterm' => $validated['incoterm'],
            'method' => $validated['method'],
            'container_type' => $validated['container_type'] ?? null,
            'weight_ton' => $validated['weight_ton'] ?? null,
            'volume_cbm' => $validated['volume_cbm'] ?? null,
            'etd' => $validated['etd'] ?? null,
            'currency' => $validated['currency'],
            'fx_rate' => $validated['fx_rate'],
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        // إنشاء بنود التكلفة
        foreach ($validated['costs'] as $cost) {
            ExportCost::create([
                'export_shipment_id' => $shipment->id,
                'line_name' => $cost['line_name'],
                'category' => $cost['category'],
                'col_index' => $cost['col_index'],
                'amount' => $cost['amount'],
                'currency' => $cost['currency'],
                'meta' => $cost['meta'] ?? null,
            ]);
        }

        // إذا كان الطلب JSON، إرجاع JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الشحنة بنجاح',
                'shipment_id' => $shipment->id,
                'id' => $shipment->id,
                'redirect' => route('export.shipments.show', $shipment->id),
            ], 201);
        }

        return redirect()
            ->route('export.shipments.show', $shipment->id)
            ->with('status', 'تم حفظ الشحنة بنجاح');
    }

    public function show($id)
    {
        $shipment = ExportShipment::with(['costs', 'quotes', 'client'])
            ->findOrFail($id);

        // Authorization: only owner can view
        if ($shipment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('front.export.shipments.show', compact('shipment'));
    }
}
