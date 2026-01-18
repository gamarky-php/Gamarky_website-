<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض سعر تصنيع - {{ $quote->quote_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        
        .container { padding: 20px; max-width: 800px; margin: 0 auto; }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .header h1 { font-size: 24px; margin-bottom: 10px; }
        .header .meta { font-size: 11px; opacity: 0.9; }
        
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px; }
        .info-card { background: #f8f9fa; padding: 15px; border-radius: 6px; border-right: 3px solid #667eea; }
        .info-card .label { font-size: 10px; color: #6b7280; margin-bottom: 5px; }
        .info-card .value { font-size: 14px; font-weight: bold; color: #1f2937; }
        
        .section-title {
            background: #667eea;
            color: white;
            padding: 10px 15px;
            margin: 25px 0 15px 0;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background: #f3f4f6; padding: 10px; text-align: right; font-weight: bold; border: 1px solid #e5e7eb; font-size: 11px; }
        table td { padding: 8px 10px; border: 1px solid #e5e7eb; font-size: 11px; }
        table tr:nth-child(even) { background: #f9fafb; }
        
        .total-row { background: #dbeafe !important; font-weight: bold; font-size: 12px; }
        .final-price { background: #dcfce7 !important; font-weight: bold; font-size: 14px; color: #166534; }
        
        .footer { margin-top: 40px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>عرض سعر تصنيع</h1>
            <div class="meta">
                <div>رقم العرض: <strong>{{ $quote->quote_number }}</strong></div>
                <div>التاريخ: <strong>{{ $quote->created_at->format('Y-m-d') }}</strong></div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="label">المنتج</div>
                <div class="value">{{ $quote->costRun->product_name }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">العميل</div>
                <div class="value">{{ $quote->client_name ?? '-' }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">حجم الدفعة</div>
                <div class="value">{{ number_format($quote->costRun->batch_size, 0) }} {{ $quote->costRun->batch_unit }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">صالح حتى</div>
                <div class="value">{{ $quote->valid_until ? $quote->valid_until->format('Y-m-d') : '-' }}</div>
            </div>
        </div>

        <div class="section-title">قائمة المواد (BOM)</div>
        <table>
            <thead>
                <tr>
                    <th>المادة</th>
                    <th>الكمية/وحدة</th>
                    <th>سعر الوحدة</th>
                    <th>التكلفة الكلية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->costRun->bomItems as $item)
                <tr>
                    <td>{{ $item->material_name }}</td>
                    <td>{{ number_format($item->qty_per_unit, 4) }} {{ $item->uom }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total_cost, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3">إجمالي المواد</td>
                    <td>{{ number_format($quote->costRun->total_material_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">عمليات التشغيل</div>
        <table>
            <thead>
                <tr>
                    <th>العملية</th>
                    <th>إعداد (ساعة)</th>
                    <th>دورة (دقيقة)</th>
                    <th>التكلفة الكلية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->costRun->operations as $op)
                <tr>
                    <td>{{ $op->operation_name }}</td>
                    <td>{{ number_format($op->setup_time_hours, 2) }}</td>
                    <td>{{ number_format($op->cycle_time_minutes, 2) }}</td>
                    <td>{{ number_format($op->total_cost, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3">إجمالي العمليات</td>
                    <td>{{ number_format($quote->costRun->total_operation_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">تكاليف غير مباشرة</div>
        <table>
            <thead>
                <tr>
                    <th>البند</th>
                    <th>طريقة التخصيص</th>
                    <th>التكلفة الكلية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->costRun->overheads as $oh)
                <tr>
                    <td>{{ $oh->overhead_name }}</td>
                    <td>{{ $oh->allocation_method === 'fixed' ? 'ثابت' : 'نسبة ' . number_format($oh->rate_pct, 2) . '%' }}</td>
                    <td>{{ number_format($oh->total_cost, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">إجمالي غير المباشرة</td>
                    <td>{{ number_format($quote->costRun->total_overhead_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">ملخص التكلفة والسعر</div>
        <table>
            <tbody>
                <tr>
                    <td>تكلفة الدفعة</td>
                    <td>{{ number_format($quote->costRun->total_batch_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
                <tr>
                    <td>تكلفة الوحدة</td>
                    <td>{{ number_format($quote->unit_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
                <tr>
                    <td>هامش الربح ({{ number_format($quote->margin_pct, 2) }}%)</td>
                    <td>{{ number_format($quote->unit_price - $quote->unit_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
                <tr class="total-row">
                    <td>سعر الوحدة</td>
                    <td>{{ number_format($quote->unit_price, 2) }} {{ $quote->currency }}</td>
                </tr>
                <tr>
                    <td>الكمية</td>
                    <td>{{ number_format($quote->qty, 0) }}</td>
                </tr>
                <tr class="final-price">
                    <td><strong>الإجمالي النهائي</strong></td>
                    <td><strong>{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</strong></td>
                </tr>
            </tbody>
        </table>

        @if($quote->notes)
        <div class="section-title">ملاحظات</div>
        <div style="background: #fef3c7; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            {{ $quote->notes }}
        </div>
        @endif

        <div class="footer">
            <p>عرض السعر صالح حتى {{ $quote->valid_until ? $quote->valid_until->format('Y-m-d') : '-' }}</p>
            <p>تم الإنشاء بواسطة نظام جماركي في {{ now()->format('Y-m-d H:i') }}</p>
        </div>
    </div>
</body>
</html>
