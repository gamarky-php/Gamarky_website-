<?php

namespace App\Pdf;

use App\Models\ExportQuote;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportQuotePdf
{
    protected ExportQuote $quote;

    public function __construct(ExportQuote $quote)
    {
        $this->quote = $quote;
    }

    /**
     * توليد ملف PDF للعرض
     */
    public function generate(): \Barryvdh\DomPDF\PDF
    {
        $quote = $this->quote->load('shipment.client', 'shipment.creator');

        // تجميع التكاليف حسب الأعمدة
        $costsByColumn = $this->groupCostsByColumn();

        $data = [
            'quote' => $quote,
            'shipment' => $quote->shipment,
            'costsByColumn' => $costsByColumn,
            'generatedAt' => now()->format('Y-m-d H:i'),
        ];

        return Pdf::loadView('pdf.export-quote', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
    }

    /**
     * حفظ PDF في Storage
     */
    public function save(): string
    {
        $filename = 'export-quote-'.$this->quote->quote_no.'.pdf';
        $path = 'quotes/'.date('Y/m').'/'.$filename;

        $pdf = $this->generate();
        $pdf->save(storage_path('app/public/'.$path));

        // تحديث مسار PDF في القاعدة
        $this->quote->update(['pdf_path' => $path]);

        return $path;
    }

    /**
     * تحميل PDF مباشرة
     */
    public function download(): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'عرض-سعر-'.$this->quote->quote_no.'.pdf';

        return $this->generate()->download($filename);
    }

    /**
     * عرض PDF في المتصفح
     */
    public function stream(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->generate()->stream();
    }

    /**
     * تجميع التكاليف حسب col_index
     */
    protected function groupCostsByColumn(): array
    {
        $costs = $this->quote->shipment->costs()->orderBy('col_index')->get();

        $grouped = [];
        foreach ($costs as $cost) {
            $colIndex = $cost->col_index ?? 0;

            if (! isset($grouped[$colIndex])) {
                $grouped[$colIndex] = [
                    'title' => $cost->meta['column_title'] ?? "عمود {$colIndex}",
                    'items' => [],
                    'total' => 0,
                ];
            }

            $grouped[$colIndex]['items'][] = $cost;
            $grouped[$colIndex]['total'] += $cost->amount;
        }

        return $grouped;
    }

    /**
     * حساب التكلفة حسب Incoterm
     */
    protected function calculateByIncoterm(string $incoterm): float
    {
        $costs = $this->quote->shipment->costs;

        $manufacturing = $costs->where('category', 'manufacturing')->sum('amount');
        $packing = $costs->where('category', 'packing')->sum('amount');
        $localClearance = $costs->where('category', 'local_clearance')->sum('amount');
        $portFees = $costs->where('category', 'port_fees')->sum('amount');
        $localTrucking = $costs->where('category', 'local_trucking')->sum('amount');
        $freight = $costs->where('category', 'freight')->sum('amount');
        $insurance = $costs->where('category', 'insurance')->sum('amount');

        return match ($incoterm) {
            'EXW' => $manufacturing + $packing,
            'FOB' => $manufacturing + $packing + $localClearance + $portFees + $localTrucking,
            'CFR' => $manufacturing + $packing + $localClearance + $portFees + $localTrucking + $freight,
            'CIF' => $manufacturing + $packing + $localClearance + $portFees + $localTrucking + $freight + $insurance,
            default => 0,
        };
    }
}
