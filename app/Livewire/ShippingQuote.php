<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Services\Shipping\QuoteAggregatorService;

class ShippingQuote extends Component
{
    // حقول البحث الأساسية
    public $origin_port = '';
    public $destination_port = '';
    public $loading_date = '';
    public $weight_kg = '';
    public $cbm = '';
    public $cargo_type = 'normal'; // normal, dangerous
    public $service_type = 'FCL'; // FCL, LCL
    public $container_type = '20GP'; // 20GP, 40GP, 40HQ, Reefer
    
    // الأبعاد (اختيارية)
    public $length = '';
    public $width = '';
    public $height = '';
    
    // حالة البحث
    public $searchPerformed = false;
    public $quotes = [];
    public $sortBy = 'best_value'; // best_value, price, transit_time
    
    // المقارنة
    public $selectedForComparison = [];
    
    // العروض المحفوظة
    public $savedQuotes = [];

    protected $rules = [
        'origin_port' => 'required|string|min:2',
        'destination_port' => 'required|string|min:2',
        'loading_date' => 'required|date|after:today',
        'weight_kg' => 'required|numeric|min:1',
        'cbm' => 'nullable|numeric|min:0.01',
        'cargo_type' => 'required|in:normal,dangerous',
        'service_type' => 'required|in:FCL,LCL',
        'container_type' => 'required|in:20GP,40GP,40HQ,Reefer',
    ];

    protected $messages = [
        'origin_port.required' => 'يرجى اختيار ميناء الشحن',
        'destination_port.required' => 'يرجى اختيار ميناء الوصول',
        'loading_date.required' => 'يرجى تحديد تاريخ التحميل',
        'loading_date.after' => 'يجب أن يكون تاريخ التحميل في المستقبل',
        'weight_kg.required' => 'يرجى إدخال الوزن بالكيلوجرام',
        'weight_kg.numeric' => 'الوزن يجب أن يكون رقماً',
        'cbm.numeric' => 'الحجم يجب أن يكون رقماً',
    ];

    public function mount()
    {
        // تحميل العروض المحفوظة من الجلسة
        $this->savedQuotes = session('saved_quotes', []);
        
        // تعيين تاريخ تلقائي (بعد أسبوع من الآن)
        $this->loading_date = now()->addWeek()->format('Y-m-d');
    }

    /**
     * البحث عن العروض
     */
    public function searchQuotes()
    {
        $this->validate();

        // استخدام خدمة التجميع لجلب العروض
        $aggregator = app(QuoteAggregatorService::class);
        
        $searchParams = [
            'origin_port' => $this->origin_port,
            'destination_port' => $this->destination_port,
            'loading_date' => $this->loading_date,
            'weight_kg' => $this->weight_kg,
            'cbm' => $this->cbm,
            'cargo_type' => $this->cargo_type,
            'service_type' => $this->service_type,
            'container_type' => $this->container_type,
        ];

        $this->quotes = $aggregator->aggregateQuotes($searchParams)->all();
        
        // فرز العروض حسب المعيار المختار
        $this->sortQuotes();
        
        $this->searchPerformed = true;
    }

    /**
     * محاكاة جلب العروض من خدمة التجميع
     * (يُستخدم كـ fallback فقط)
     */
    private function getQuotesFromAggregator()
    {
        // هذه الدالة احتياطية فقط - الخدمة الفعلية في QuoteAggregatorService
        return [];
    }

    /**
     * فرز العروض
     */
    public function sortQuotes()
    {
        $quotes = collect($this->quotes);

        switch ($this->sortBy) {
            case 'price':
                $quotes = $quotes->sortBy('total_price');
                break;
            case 'transit_time':
                $quotes = $quotes->sortBy('transit_days');
                break;
            case 'best_value':
            default:
                // حساب القيمة الأفضل (سعر + زمن + تقييم)
                $quotes = $quotes->sortBy(function ($quote) {
                    $priceScore = $quote['total_price'] / 10; // وزن السعر
                    $timeScore = $quote['transit_days'] * 5; // وزن الزمن
                    $ratingScore = (5 - $quote['rating']) * 50; // وزن التقييم
                    return $priceScore + $timeScore + $ratingScore;
                });
                break;
        }

        $this->quotes = $quotes->values()->all();
    }

    /**
     * تحديث طريقة الفرز
     */
    public function updateSort($sort)
    {
        $this->sortBy = $sort;
        $this->sortQuotes();
    }

    /**
     * إضافة/إزالة عرض للمقارنة
     */
    public function toggleComparison($quoteId)
    {
        if (in_array($quoteId, $this->selectedForComparison)) {
            $this->selectedForComparison = array_diff($this->selectedForComparison, [$quoteId]);
        } else {
            if (count($this->selectedForComparison) < 3) {
                $this->selectedForComparison[] = $quoteId;
            } else {
                session()->flash('warning', 'يمكنك مقارنة حتى 3 عروض فقط');
            }
        }
    }

    /**
     * حفظ العرض
     */
    public function saveQuote($quoteId)
    {
        $quote = collect($this->quotes)->firstWhere('id', $quoteId);
        
        if ($quote && !in_array($quoteId, $this->savedQuotes)) {
            $this->savedQuotes[] = $quoteId;
            session()->put('saved_quotes', $this->savedQuotes);
            session()->flash('success', 'تم حفظ العرض بنجاح');
        }
    }

    /**
     * اختيار العرض (إعادة توجيه لصفحة الحجز)
     */
    public function selectQuote($quoteId)
    {
        $quote = collect($this->quotes)->firstWhere('id', $quoteId);
        
        if ($quote) {
            // حفظ تفاصيل العرض في الجلسة
            session()->put('selected_quote', $quote);
            
            // إعادة توجيه لصفحة الحجز
            return redirect()->route('front.shipping.container.book');
        }
    }

    /**
     * الحصول على العروض المحددة للمقارنة
     */
    public function getSelectedQuotesProperty()
    {
        return collect($this->quotes)
            ->whereIn('id', $this->selectedForComparison)
            ->values()
            ->all();
    }

    /**
     * حساب الحجم CBM من الأبعاد
     */
    public function calculateCBM()
    {
        if ($this->length && $this->width && $this->height) {
            $this->cbm = round(($this->length * $this->width * $this->height) / 1000000, 2);
        }
    }

    public function render()
    {
        return view('livewire.shipping-quote')
            ->layout('layouts.app');
    }
}
