<?php

namespace App\Exports;

use App\Models\ExportQuote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportQuoteExport implements FromCollection, ShouldAutoSize, WithColumnWidths, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected ExportQuote $quote;

    protected array $costsByColumn;

    public function __construct(ExportQuote $quote)
    {
        $this->quote = $quote->load('shipment.costs', 'shipment.client');
        $this->prepareCostsByColumn();
    }

    /**
     * جلب البيانات
     */
    public function collection()
    {
        $rows = collect();

        // معلومات العرض
        $rows->push([
            'type' => 'header',
            'label' => 'رقم العرض',
            'value' => $this->quote->quote_no,
        ]);

        $rows->push([
            'type' => 'header',
            'label' => 'التاريخ',
            'value' => $this->quote->created_at->format('Y-m-d'),
        ]);

        $rows->push([
            'type' => 'header',
            'label' => 'العميل',
            'value' => $this->quote->shipment->client->name ?? 'غير محدد',
        ]);

        $rows->push([
            'type' => 'header',
            'label' => 'الشرط التجاري',
            'value' => $this->quote->incoterm_final,
        ]);

        $rows->push([
            'type' => 'separator',
        ]);

        // التكاليف حسب الأعمدة
        foreach ($this->costsByColumn as $colIndex => $column) {
            $rows->push([
                'type' => 'column_header',
                'label' => $column['title'],
            ]);

            foreach ($column['items'] as $cost) {
                $rows->push([
                    'type' => 'cost_item',
                    'label' => $cost->line_name,
                    'category' => $this->translateCategory($cost->category),
                    'amount' => $cost->amount,
                    'currency' => $cost->currency,
                ]);
            }

            $rows->push([
                'type' => 'column_total',
                'label' => 'إجمالي '.$column['title'],
                'amount' => $column['total'],
            ]);

            $rows->push([
                'type' => 'separator',
            ]);
        }

        // الإجمالي النهائي
        $rows->push([
            'type' => 'final_total',
            'label' => 'التكلفة الإجمالية',
            'amount' => $this->quote->total_cost,
        ]);

        $rows->push([
            'type' => 'final_total',
            'label' => 'هامش الربح ('.$this->quote->margin_pct.'%)',
            'amount' => ($this->quote->sell_price - $this->quote->total_cost),
        ]);

        $rows->push([
            'type' => 'final_price',
            'label' => 'سعر البيع النهائي',
            'amount' => $this->quote->sell_price,
            'currency' => $this->quote->currency,
        ]);

        return $rows;
    }

    /**
     * العناوين
     */
    public function headings(): array
    {
        return [
            'البيان',
            'التصنيف',
            'المبلغ',
            'العملة',
        ];
    }

    /**
     * تنسيق الصفوف
     */
    public function map($row): array
    {
        switch ($row['type']) {
            case 'header':
                return [
                    $row['label'],
                    $row['value'] ?? '',
                    '',
                    '',
                ];

            case 'column_header':
                return [
                    $row['label'],
                    '',
                    '',
                    '',
                ];

            case 'cost_item':
                return [
                    $row['label'],
                    $row['category'] ?? '',
                    number_format($row['amount'], 2),
                    $row['currency'] ?? $this->quote->currency,
                ];

            case 'column_total':
                return [
                    $row['label'],
                    '',
                    number_format($row['amount'], 2),
                    $this->quote->currency,
                ];

            case 'final_total':
            case 'final_price':
                return [
                    $row['label'],
                    '',
                    number_format($row['amount'], 2),
                    $row['currency'] ?? $this->quote->currency,
                ];

            case 'separator':
            default:
                return ['', '', '', ''];
        }
    }

    /**
     * تنسيق الخلايا
     */
    public function styles(Worksheet $sheet)
    {
        // تنسيق عام
        $sheet->getStyle('A:D')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // تنسيق العناوين (الصف الأول)
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        return $sheet;
    }

    /**
     * عنوان الورقة
     */
    public function title(): string
    {
        return 'عرض سعر '.$this->quote->quote_no;
    }

    /**
     * عرض الأعمدة
     */
    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 20,
            'C' => 15,
            'D' => 10,
        ];
    }

    /**
     * تجميع التكاليف حسب الأعمدة
     */
    protected function prepareCostsByColumn(): void
    {
        $costs = $this->quote->shipment->costs()->orderBy('col_index')->get();

        $this->costsByColumn = [];
        foreach ($costs as $cost) {
            $colIndex = $cost->col_index ?? 0;

            if (! isset($this->costsByColumn[$colIndex])) {
                $this->costsByColumn[$colIndex] = [
                    'title' => $cost->meta['column_title'] ?? "عمود {$colIndex}",
                    'items' => [],
                    'total' => 0,
                ];
            }

            $this->costsByColumn[$colIndex]['items'][] = $cost;
            $this->costsByColumn[$colIndex]['total'] += $cost->amount;
        }
    }

    /**
     * ترجمة التصنيفات
     */
    protected function translateCategory(string $category): string
    {
        return match ($category) {
            'manufacturing' => 'تصنيع',
            'packing' => 'تعبئة',
            'local_clearance' => 'تخليص محلي',
            'port_fees' => 'رسوم ميناء',
            'local_trucking' => 'نقل محلي',
            'freight' => 'شحن',
            'insurance' => 'تأمين',
            'bank' => 'بنوك',
            'docs' => 'مستندات',
            'extras' => 'إضافات',
            'profit' => 'ربح',
            'final_price' => 'السعر النهائي',
            default => $category,
        };
    }
}
