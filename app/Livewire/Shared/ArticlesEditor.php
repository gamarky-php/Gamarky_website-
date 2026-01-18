<?php

namespace App\Livewire\Shared;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ArticlesEditor extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingArticle = null;

    // Form Fields
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $category = 'general';
    public $status = 'draft'; // draft | published | archived
    public $featured = false;
    public $published_at = '';

    // Filters
    public $filterStatus = '';
    public $filterCategory = '';

    protected $rules = [
        'title' => 'required|min:5|max:200',
        'slug' => 'required|unique:articles,slug',
        'excerpt' => 'nullable|max:500',
        'content' => 'required|min:50',
        'category' => 'required',
        'status' => 'required|in:draft,published,archived',
        'published_at' => 'nullable|date',
    ];

    public function updatedTitle()
    {
        if (!$this->editingArticle) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($articleId)
    {
        $article = DB::table('articles')->where('id', $articleId)->first();
        
        if ($article) {
            $this->editingArticle = $article;
            $this->title = $article->title;
            $this->slug = $article->slug;
            $this->excerpt = $article->excerpt;
            $this->content = $article->content;
            $this->category = $article->category ?? 'general';
            $this->status = $article->status;
            $this->featured = (bool) $article->featured;
            $this->published_at = $article->published_at;
            
            $this->showEditModal = true;
        }
    }

    public function createArticle()
    {
        $this->validate();

        DB::table('articles')->insert([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category' => $this->category,
            'status' => $this->status,
            'featured' => $this->featured,
            'published_at' => $this->status === 'published' ? ($this->published_at ?: now()) : null,
            'author_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->closeModals();
        session()->flash('success', 'تم إنشاء المقال بنجاح');
    }

    public function updateArticle()
    {
        $this->rules['slug'] = 'required|unique:articles,slug,' . $this->editingArticle->id;
        $this->validate();

        DB::table('articles')
            ->where('id', $this->editingArticle->id)
            ->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'category' => $this->category,
                'status' => $this->status,
                'featured' => $this->featured,
                'published_at' => $this->status === 'published' ? ($this->published_at ?: now()) : null,
                'updated_at' => now(),
            ]);

        $this->closeModals();
        session()->flash('success', 'تم تحديث المقال بنجاح');
    }

    public function deleteArticle($articleId)
    {
        DB::table('articles')->where('id', $articleId)->delete();
        session()->flash('success', 'تم حذف المقال بنجاح');
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->title = '';
        $this->slug = '';
        $this->excerpt = '';
        $this->content = '';
        $this->category = 'general';
        $this->status = 'draft';
        $this->featured = false;
        $this->published_at = '';
        $this->editingArticle = null;
    }

    public function getArticlesProperty()
    {
        $query = DB::table('articles')
            ->leftJoin('users', 'articles.author_id', '=', 'users.id')
            ->select('articles.*', 'users.name as author_name')
            ->orderBy('articles.created_at', 'desc');

        if ($this->filterStatus) {
            $query->where('articles.status', $this->filterStatus);
        }

        if ($this->filterCategory) {
            $query->where('articles.category', $this->filterCategory);
        }

        return $query->paginate(15);
    }

    public function render()
    {
        return view('livewire.shared.articles-editor', [
            'articles' => $this->articles,
        ]);
    }
}
