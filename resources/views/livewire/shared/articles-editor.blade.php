{{-- dir inherited from layout --}}
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">📝 {{ __('dashboard.shared.articles_editor.title') }}</h2>
        <button wire:click="openCreateModal" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">+ {{ __('dashboard.shared.articles_editor.new_article') }}</button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">✓ {{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="grid grid-cols-2 gap-4">
            <select wire:model.live="filterStatus" class="rounded-lg border-gray-300">
                <option value="">{{ __('dashboard.shared.articles_editor.all_statuses') }}</option>
                <option value="draft">{{ __('dashboard.shared.articles_editor.status_draft') }}</option>
                <option value="published">{{ __('dashboard.shared.articles_editor.status_published') }}</option>
                <option value="archived">{{ __('dashboard.shared.articles_editor.status_archived') }}</option>
            </select>
            <select wire:model.live="filterCategory" class="rounded-lg border-gray-300">
                <option value="">{{ __('dashboard.shared.articles_editor.all_categories') }}</option>
                <option value="general">{{ __('dashboard.shared.articles_editor.category_general') }}</option>
                <option value="news">{{ __('dashboard.shared.articles_editor.category_news') }}</option>
                <option value="guide">{{ __('dashboard.shared.articles_editor.category_guide') }}</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($articles->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($articles as $article)
                    <div class="p-5 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $article->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($article->excerpt ?? $article->content, 100) }}</p>
                                <div class="flex items-center gap-3 mt-3 text-xs text-gray-500">
                                    <span>{{ $article->author_name ?? __('dashboard.shared.articles_editor.unknown_author') }}</span>
                                    <span>•</span>
                                    <span>{{ \Carbon\Carbon::parse($article->created_at)->format('Y-m-d') }}</span>
                                    <span class="px-2 py-1 rounded-full {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ [
                                            'draft' => __('dashboard.shared.articles_editor.status_draft'),
                                            'published' => __('dashboard.shared.articles_editor.status_published'),
                                            'archived' => __('dashboard.shared.articles_editor.status_archived'),
                                        ][$article->status] ?? $article->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="openEditModal({{ $article->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="deleteArticle({{ $article->id }})" wire:confirm="{{ __('dashboard.shared.articles_editor.delete_confirm') }}" class="p-2 text-red-600 hover:bg-red-50 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4">{{ $articles->links() }}</div>
        @else
            <div class="p-12 text-center text-gray-500">{{ __('dashboard.shared.articles_editor.empty') }}</div>
        @endif
    </div>

    @if($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModals"></div>
                <div class="relative bg-white rounded-lg max-w-3xl w-full p-6">
                    <h3 class="text-xl font-bold mb-5">{{ $showEditModal ? __('dashboard.shared.articles_editor.edit_article') : __('dashboard.shared.articles_editor.new_article') }}</h3>
                    <form wire:submit.prevent="{{ $showEditModal ? 'updateArticle' : 'createArticle' }}" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.shared.articles_editor.field_title') }} *</label>
                            <input type="text" wire:model.live="title" class="w-full rounded-lg border-gray-300" required>
                            @error('title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.shared.articles_editor.field_slug') }}</label>
                            <input type="text" wire:model="slug" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.shared.articles_editor.field_excerpt') }}</label>
                            <textarea wire:model="excerpt" rows="2" class="w-full rounded-lg border-gray-300"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.shared.articles_editor.field_content') }} *</label>
                            <textarea wire:model="content" rows="8" class="w-full rounded-lg border-gray-300" required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <select wire:model="category" class="rounded-lg border-gray-300">
                                <option value="general">{{ __('dashboard.shared.articles_editor.category_general') }}</option>
                                <option value="news">{{ __('dashboard.shared.articles_editor.category_news') }}</option>
                                <option value="guide">{{ __('dashboard.shared.articles_editor.category_guide') }}</option>
                            </select>
                            <select wire:model="status" class="rounded-lg border-gray-300">
                                <option value="draft">{{ __('dashboard.shared.articles_editor.status_draft') }}</option>
                                <option value="published">{{ __('dashboard.shared.articles_editor.status_published') }}</option>
                                <option value="archived">{{ __('dashboard.shared.articles_editor.status_archived') }}</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" wire:click="closeModals" class="px-4 py-2 bg-gray-200 rounded-lg">{{ __('dashboard.shared.articles_editor.cancel') }}</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">{{ $showEditModal ? __('dashboard.shared.articles_editor.update') : __('dashboard.shared.articles_editor.create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
