<?php

namespace App\Exports;

use App\Models\MfgQuote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MfgQuoteExport implements FromCollection, ShouldAutoSize, WithColumnWidths, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected MfgQuote $quote;

    public function __construct(MfgQuote $quote)
    {
        $this->quote = $quote->load('costRun.bomItems', 'costRun.operations', 'costRun.overheads');
    }

    public function collection()
    {
        $rows = collect();

        $rows->push(['type' => 'header', 'label' => 'رقم العرض', 'value' => $this->quote->quote_number]);
        $rows->push(['type' => 'header', 'label' => 'المنتج', 'value' => $this->quote->costRun->product_name]);
        $rows->push(['type' => 'header', 'label' => 'العميل', 'value' => $this->quote->client_name ?? '-']);
        $rows->push(['type' => 'header', 'label' => 'التاريخ', 'value' => $this->quote->created_at->format('Y-m-d')]);
        $rows->push(['type' => 'separator']);

        $rows->push(['type' => 'section', 'label' => 'قائمة المواد (BOM)']);
        foreach ($this->quote->costRun->bomItems as $item) {
            $rows->push([
                'type' => 'item',
                'label' => $item->material_name,
                'detail' => $item->qty_per_unit.' '.$item->uom.' × '.number_format($item->unit_price, 2),
                'amount' => $item->total_cost,
            ]);
        }
        $rows->push(['type' => 'subtotal', 'label' => 'إجمالي المواد', 'amount' => $this->quote->costRun->total_material_cost]);
        $rows->push(['type' => 'separator']);

        $rows->push(['type' => 'section', 'label' => 'عمليات التشغيل']);
        foreach ($this->quote->costRun->operations as $op) {
            $rows->push([
                'type' => 'item',
                'label' => $op->operation_name,
                'detail' => 'إعداد: '.$op->setup_time_hours.'س | دورة: '.$op->cycle_time_minutes.'د',
                'amount' => $op->total_cost,
            ]);
        }
        $rows->push(['type' => 'subtotal', 'label' => 'إجمالي العمليات', 'amount' => $this->quote->costRun->total_operation_cost]);
        $rows->push(['type' => 'separator']);

        $rows->push(['type' => 'section', 'label' => 'تكاليف غير مباشرة']);
        foreach ($this->quote->costRun->overheads as $oh) {
            $rows->push([
                'type' => 'item',
                'label' => $oh->overhead_name,
                'detail' => $oh->allocation_method === 'fixed' ? 'ثابت' : 'نسبة '.$oh->rate_pct.'%',
                'amount' => $oh->total_cost,
            ]);
        }
        $rows->push(['type' => 'subtotal', 'label' => 'إجمالي غير المباشرة', 'amount' => $this->quote->costRun->total_overhead_cost]);
        $rows->push(['type' => 'separator']);

        $rows->push(['type' => 'total', 'label' => 'تكلفة الدفعة', 'amount' => $this->quote->costRun->total_batch_cost]);
        $rows->push(['type' => 'total', 'label' => 'تكلفة الوحدة', 'amount' => $this->quote->unit_cost]);
        $rows->push(['type' => 'total', 'label' => 'هامش الربح ('.number_format($this->quote->margin_pct, 2).'%)', 'amount' => $this->quote->unit_price - $this->quote->unit_cost]);
        $rows->push(['type' => 'total', 'label' => 'سعر الوحدة', 'amount' => $this->quote->unit_price]);
        $rows->push(['type' => 'total', 'label' => 'الكمية', 'amount' => $this->quote->qty]);
        $rows->push(['type' => 'final', 'label' => 'الإجمالي النهائي', 'amount' => $this->quote->total_amount]);

        return $rows;
    }

    public function headings(): array
    {
        return ['البيان', 'التفاصيل', 'المبلغ ('.$this->quote->currency.')'];
    }

    public function map($row): array
    {
        switch ($row['type']) {
            case 'header':
                return [$row['label'], $row['value'], ''];
            case 'section':
                return [$row['label'], '', ''];
            case 'item':
                return [$row['label'], $row['detail'] ?? '', number_format($row['amount'], 2)];
            case 'subtotal':
            case 'total':
            case 'final':
                return [$row['label'], '', number_format($row['amount'], 2)];
            default:
                return ['', '', ''];
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A:C')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'font' => ['color' => ['rgb' => 'FFFFFF']],
        ]);

        return [];
    }

    public function title(): string
    {
        return 'عرض السعر';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'C' => 20,
        ];
    }
}
