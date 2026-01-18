<div dir="rtl" class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">🖼️ مكتبة الوسائط</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">✓ {{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-900 mb-4">رفع ملفات</h3>
        <form wire:submit.prevent="uploadFiles" class="space-y-4">
            <input type="file" wire:model="files" multiple class="w-full">
            @error('files.*') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">رفع الملفات</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="grid grid-cols-3 gap-4">
            <input type="text" wire:model.live="search" placeholder="بحث..." class="rounded-lg border-gray-300">
            <select wire:model.live="filterType" class="rounded-lg border-gray-300">
                <option value="">جميع الأنواع</option>
                <option value="image">صور</option>
                <option value="document">مستندات</option>
                <option value="video">فيديو</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($mediaFiles as $file)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 hover:shadow-md transition-shadow">
                @if($file->type === 'image')
                    <img src="{{ Storage::url($file->path) }}" class="w-full h-32 object-cover rounded-lg mb-2">
                @else
                    <div class="w-full h-32 bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                @endif
                <p class="text-xs text-gray-700 truncate font-medium">{{ $file->filename }}</p>
                <p class="text-xs text-gray-500">{{ number_format($file->size / 1024, 1) }} KB</p>
                <button wire:click="deleteFile({{ $file->id }})" wire:confirm="حذف؟" class="mt-2 w-full px-2 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200">حذف</button>
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $mediaFiles->links() }}</div>
</div>
