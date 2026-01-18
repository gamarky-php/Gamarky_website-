<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض سعر - {{ $quote->quote_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header .meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            opacity: 0.9;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-right: 3px solid #667eea;
        }
        
        .info-card .label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .info-card .value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .section-title {
            background: #667eea;
            color: white;
            padding: 10px 15px;
            margin: 25px 0 15px 0;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th {
            background: #f3f4f6;
            padding: 10px;
            text-align: right;
            font-weight: bold;
            border: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        table td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .column-header {
            background: #eef2ff !important;
            font-weight: bold;
            color: #4338ca;
        }
        
        .column-total {
            background: #fef3c7 !important;
            font-weight: bold;
        }
        
        .total-row {
            background: #dbeafe !important;
            font-weight: bold;
            font-size: 12px;
        }
        
        .final-price {
            background: #dcfce7 !important;
            font-weight: bold;
            font-size: 14px;
            color: #166534;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        
        .text-left {
            text-align: left;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-sent {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-accepted {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>عرض سعر تصدير</h1>
            <div class="meta">
                <div>رقم العرض: <strong>{{ $quote->quote_no }}</strong></div>
                <div>التاريخ: <strong>{{ $generatedAt }}</strong></div>
            </div>
        </div>

        <!-- معلومات العرض -->
        <div class="info-grid">
            <div class="info-card">
                <div class="label">العميل</div>
                <div class="value">{{ $shipment->client->name ?? 'غير محدد' }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">الشرط التجاري</div>
                <div class="value">{{ $quote->incoterm_final }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">ميناء التحميل</div>
                <div class="value">{{ $shipment->pol }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">ميناء الوصول</div>
                <div class="value">{{ $shipment->pod }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">طريقة الشحن</div>
                <div class="value">{{ strtoupper($shipment->method) }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">العملة</div>
                <div class="value">{{ $quote->currency }}</div>
            </div>
        </div>

        <!-- تفاصيل التكاليف -->
        <div class="section-title">تفاصيل التكاليف</div>
        
        @foreach($costsByColumn as $colIndex => $column)
            <table>
                <thead>
                    <tr class="column-header">
                        <th colspan="3">{{ $column['title'] }}</th>
                    </tr>
                    <tr>
                        <th style="width: 50%;">البيان</th>
                        <th style="width: 25%;">التصنيف</th>
                        <th style="width: 25%;" class="text-left">المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($column['items'] as $cost)
                    <tr>
                        <td>{{ $cost->line_name }}</td>
                        <td>
                            @switch($cost->category)
                                @case('manufacturing') تصنيع @break
                                @case('packing') تعبئة @break
                                @case('local_clearance') تخليص محلي @break
                                @case('port_fees') رسوم ميناء @break
                                @case('local_trucking') نقل محلي @break
                                @case('freight') شحن @break
                                @case('insurance') تأمين @break
                                @case('bank') بنوك @break
                                @case('docs') مستندات @break
                                @case('extras') إضافات @break
                                @case('profit') ربح @break
                                @default {{ $cost->category }}
                            @endswitch
                        </td>
                        <td class="text-left">{{ number_format($cost->amount, 2) }} {{ $cost->currency }}</td>
                    </tr>
                    @endforeach
                    
                    <tr class="column-total">
                        <td colspan="2">إجمالي {{ $column['title'] }}</td>
                        <td class="text-left">{{ number_format($column['total'], 2) }} {{ $quote->currency }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach

        <!-- الإجمالي النهائي -->
        <div class="section-title">الإجمالي النهائي</div>
        
        <table>
            <tbody>
                <tr class="total-row">
                    <td style="width: 75%;">التكلفة الإجمالية</td>
                    <td class="text-left">{{ number_format($quote->total_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
                
                <tr class="total-row">
                    <td>هامش الربح ({{ $quote->margin_pct }}%)</td>
                    <td class="text-left">{{ number_format($quote->sell_price - $quote->total_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
                
                <tr class="final-price">
                    <td>سعر البيع النهائي</td>
                    <td class="text-left">{{ number_format($quote->sell_price, 2) }} {{ $quote->currency }}</td>
                </tr>
                
                @if($shipment->weight_ton > 0)
                <tr>
                    <td>سعر الطن</td>
                    <td class="text-left">{{ number_format($quote->sell_price / $shipment->weight_ton, 2) }} {{ $quote->currency }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- ملاحظات -->
        @if($shipment->notes)
        <div class="section-title">ملاحظات</div>
        <div style="padding: 15px; background: #f9fafb; border-radius: 6px; margin-bottom: 20px;">
            {{ $shipment->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>هذا العرض صالح لمدة 30 يومًا من تاريخ الإصدار</p>
            <p style="margin-top: 10px;">تم الإنشاء بواسطة: {{ $shipment->creator->name ?? 'النظام' }}</p>
        </div>
    </div>
</body>
</html>
