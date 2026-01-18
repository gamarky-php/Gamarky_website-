<div dir="rtl" class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">📢 إدارة الإعلانات</h2>
            <p class="text-sm text-gray-600 mt-1">إدارة إعلانات لوحة التحكم والترويج للخدمات</p>
        </div>
        <button wire:click="openCreateModal" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
            + إنشاء إعلان جديد
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-green-800">✓ {{ session('success') }}</p>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">تصفية النتائج</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                    <option value="scheduled">مجدول</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الموقع</label>
                <select wire:model.live="filterLocation" class="w-full rounded-lg border-gray-300">
                    <option value="">جميع المواقع</option>
                    <option value="sidebar">الشريط الجانبي</option>
                    <option value="top">أعلى الصفحة</option>
                    <option value="bottom">أسفل الصفحة</option>
                    <option value="popup">نافذة منبثقة</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Ads List --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($ads->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإعلان</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموقع</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأولوية</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الفترة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($ads as $ad)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($ad->image_path)
                                            <img src="{{ Storage::url($ad->image_path) }}" class="w-16 h-16 object-cover rounded-lg" alt="{{ $ad->title }}">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $ad->title }}</p>
                                            @if($ad->description)
                                                <p class="text-xs text-gray-600 mt-0.5">{{ Str::limit($ad->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                        {{ [
                                            'sidebar' => 'شريط جانبي',
                                            'top' => 'أعلى',
                                            'bottom' => 'أسفل',
                                            'popup' => 'منبثق'
                                        ][$ad->location] ?? $ad->location }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold bg-indigo-100 text-indigo-800 rounded-full">{{ $ad->priority }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    @if($ad->start_date && $ad->end_date)
                                        {{ \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d') }} → {{ \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d') }}
                                    @else
                                        دائم
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="toggleStatus({{ $ad->id }})" 
                                            class="px-3 py-1 text-xs font-semibold rounded-full transition-colors
                                            {{ $ad->status === 'active' ? 'bg-green-100 text-green-800 hover:bg-green-200' : '' }}
                                            {{ $ad->status === 'inactive' ? 'bg-gray-100 text-gray-800 hover:bg-gray-200' : '' }}
                                            {{ $ad->status === 'scheduled' ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' : '' }}">
                                        {{ [
                                            'active' => 'نشط',
                                            'inactive' => 'غير نشط',
                                            'scheduled' => 'مجدول'
                                        ][$ad->status] ?? $ad->status }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openEditModal({{ $ad->id }})" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded" title="تعديل">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteAd({{ $ad->id }})" wire:confirm="هل أنت متأكد من الحذف؟" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $ads->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">لا توجد إعلانات</h3>
                <p class="text-sm text-gray-600">ابدأ بإنشاء أول إعلان</p>
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="{{ $showEditModal ? 'updateAd' : 'createAd' }}">
                        <div class="bg-white px-6 pt-5 pb-4 sm:p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-5">{{ $showEditModal ? 'تعديل الإعلان' : 'إنشاء إعلان جديد' }}</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">العنوان *</label>
                                    <input type="text" wire:model="title" class="w-full rounded-lg border-gray-300" required>
                                    @error('title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                    <textarea wire:model="description" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">صورة الإعلان</label>
                                    <input type="file" wire:model="image" accept="image/*" class="w-full">
                                    @if($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover rounded-lg">
                                    @elseif($existing_image)
                                        <img src="{{ Storage::url($existing_image) }}" class="mt-2 w-32 h-32 object-cover rounded-lg">
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">رابط الإعلان</label>
                                    <input type="url" wire:model="link_url" class="w-full rounded-lg border-gray-300" placeholder="https://example.com">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الموقع *</label>
                                        <select wire:model="location" class="w-full rounded-lg border-gray-300">
                                            <option value="sidebar">شريط جانبي</option>
                                            <option value="top">أعلى الصفحة</option>
                                            <option value="bottom">أسفل الصفحة</option>
                                            <option value="popup">نافذة منبثقة</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الحالة *</label>
                                        <select wire:model="status" class="w-full rounded-lg border-gray-300">
                                            <option value="active">نشط</option>
                                            <option value="inactive">غير نشط</option>
                                            <option value="scheduled">مجدول</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية</label>
                                        <input type="date" wire:model="start_date" class="w-full rounded-lg border-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية</label>
                                        <input type="date" wire:model="end_date" class="w-full rounded-lg border-gray-300">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الجمهور المستهدف</label>
                                        <input type="text" wire:model="target_audience" class="w-full rounded-lg border-gray-300" placeholder="all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الأولوية (1-10)</label>
                                        <input type="number" wire:model="priority" min="1" max="10" class="w-full rounded-lg border-gray-300">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <button type="button" wire:click="closeModals" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">{{ $showEditModal ? 'تحديث' : 'إنشاء' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
