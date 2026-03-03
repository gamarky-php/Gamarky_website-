<!DOCTYPE html>
{{-- PDF: lang/dir dynamically set for multi-language support --}}
<html lang="@locale" dir="@dir">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pdf.export_quote_title') }} - {{ $quote->quote_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
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
            border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 3px solid #667eea;
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
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
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
            <h1>{{ __('pdf.export_quote_title') }}</h1>
            <div class="meta">
                <div>{{ __('pdf.quote_no') }}: <strong>{{ $quote->quote_no }}</strong></div>
                <div>{{ __('pdf.date') }}: <strong>{{ $generatedAt }}</strong></div>
            </div>
        </div>

        <!-- Quote information -->
        <div class="info-grid">
            <div class="info-card">
                <div class="label">{{ __('pdf.client') }}</div>
                <div class="value">{{ $shipment->client->name ?? __('pdf.not_specified') }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">{{ __('pdf.incoterm') }}</div>
                <div class="value">{{ $quote->incoterm_final }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">{{ __('pdf.loading_port') }}</div>
                <div class="value">{{ $shipment->pol }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">{{ __('pdf.discharge_port') }}</div>
                <div class="value">{{ $shipment->pod }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">{{ __('pdf.shipping_method') }}</div>
                <div class="value">{{ strtoupper($shipment->method) }}</div>
            </div>
            
            <div class="info-card">
                <div class="label">{{ __('pdf.currency') }}</div>
                <div class="value">{{ $quote->currency }}</div>
            </div>
        </div>

        <!-- Cost details -->
        <div class="section-title">{{ __('pdf.cost_details') }}</div>
        
        @foreach($costsByColumn as $colIndex => $column)
            <table>
                <thead>
                    <tr class="column-header">
                        <th colspan="3">{{ $column['title'] }}</th>
                    </tr>
                    <tr>
                        <th style="width: 50%;">{{ __('pdf.item_description') }}</th>
                        <th style="width: 25%;">{{ __('pdf.category') }}</th>
                        <th style="width: 25%;" class="text-left">{{ __('pdf.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($column['items'] as $cost)
                    <tr>
                        <td>{{ $cost->line_name }}</td>
                        <td>
                            @switch($cost->category)
                                @case('manufacturing') {{ __('pdf.category_manufacturing') }} @break
                                @case('packing') {{ __('pdf.category_packing') }} @break
                                @case('local_clearance') {{ __('pdf.category_local_clearance') }} @break
                                @case('port_fees') {{ __('pdf.category_port_fees') }} @break
                                @case('local_trucking') {{ __('pdf.category_local_trucking') }} @break
                                @case('freight') {{ __('pdf.category_freight') }} @break
                                @case('insurance') {{ __('pdf.category_insurance') }} @break
                                @case('bank') {{ __('pdf.category_bank') }} @break
                                @case('docs') {{ __('pdf.category_docs') }} @break
                                @case('extras') {{ __('pdf.category_extras') }} @break
                                @case('profit') {{ __('pdf.category_profit') }} @break
                                @default {{ $cost->category }}
                            @endswitch
                        </td>
                        <td class="text-left">{{ number_format($cost->amount, 2) }} {{ $cost->currency }}</td>
                    </tr>
                    @endforeach
                    
                    <tr class="column-total">
                        <td colspan="2">{{ __('pdf.column_total') }} {{ $column['title'] }}</td>
                        <td class="text-left">{{ number_format($column['total'], 2) }} {{ $quote->currency }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach

        <!-- Final total -->
        <div class="section-title">{{ __('pdf.final_total') }}</div>
        
        <table>
            <tbody>
                <tr class="total-row">
                    <td style="width: 75%;">{{ __('pdf.total_cost') }}</td>
                    <td class="text-left">{{ number_format($quote->total_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
                
                <tr class="total-row">
                    <td>{{ __('pdf.profit_margin') }} ({{ $quote->margin_pct }}%)</td>
                    <td class="text-left">{{ number_format($quote->sell_price - $quote->total_cost, 2) }} {{ $quote->currency }}</td>
                </tr>
                
                <tr class="final-price">
                    <td>{{ __('pdf.final_sell_price') }}</td>
                    <td class="text-left">{{ number_format($quote->sell_price, 2) }} {{ $quote->currency }}</td>
                </tr>
                
                @if($shipment->weight_ton > 0)
                <tr>
                    <td>{{ __('pdf.price_per_ton') }}</td>
                    <td class="text-left">{{ number_format($quote->sell_price / $shipment->weight_ton, 2) }} {{ $quote->currency }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Notes -->
        @if($shipment->notes)
        <div class="section-title">{{ __('pdf.notes') }}</div>
        <div style="padding: 15px; background: #f9fafb; border-radius: 6px; margin-bottom: 20px;">
            {{ $shipment->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('pdf.validity_notice') }}</p>
            <p style="margin-top: 10px;">{{ __('pdf.created_by') }}: {{ $shipment->creator->name ?? __('pdf.system') }}</p>
        </div>
    </div>
</body>
</html>
