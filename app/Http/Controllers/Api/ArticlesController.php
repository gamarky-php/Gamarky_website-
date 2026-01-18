<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Articles Editor API Controller
 * 
 * Features:
 * - Rich content editor
 * - Categories and tags
 * - SEO meta (title, description, keywords)
 * - Status (draft, published, scheduled, archived)
 * - Slug generation
 * - Author tracking
 * - View counter
 */
class ArticlesController extends Controller
{
    /**
     * Get all articles
     * 
     * GET /api/articles
     */
    public function index(Request $request)
    {
        $query = DB::table('articles')
            ->select([
                'articles.*',
                DB::raw('(SELECT COUNT(*) FROM article_views WHERE article_id = articles.id) as views_count'),
                DB::raw('(SELECT GROUP_CONCAT(tags.name) FROM article_tags 
                         JOIN tags ON article_tags.tag_id = tags.id 
                         WHERE article_tags.article_id = articles.id) as tags')
            ])
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('articles.status', $request->status);
        }
        
        // Filter by category
        if ($request->has('category_id')) {
            $query->where('articles.category_id', $request->category_id);
        }
        
        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('articles.title', 'like', '%' . $request->search . '%')
                  ->orWhere('articles.content', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by author
        if ($request->has('author_id')) {
            $query->where('articles.author_id', $request->author_id);
        }
        
        $articles = $query->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $articles->items(),
            'pagination' => [
                'total' => $articles->total(),
                'per_page' => $articles->perPage(),
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
            ]
        ]);
    }

    /**
     * Get single article
     * 
     * GET /api/articles/{id}
     */
    public function show($id)
    {
        // Support both ID and slug
        $query = DB::table('articles');
        
        if (is_numeric($id)) {
            $query->where('id', $id);
        } else {
            $query->where('slug', $id);
        }
        
        $article = $query->first();
        
        if (!$article) {
            return response()->json([
                'success' => false,
                'error' => 'Article not found'
            ], 404);
        }
        
        // Get category
        $article->category = DB::table('categories')
            ->where('id', $article->category_id)
            ->first();
        
        // Get tags
        $article->tags = DB::table('article_tags')
            ->join('tags', 'article_tags.tag_id', '=', 'tags.id')
            ->where('article_tags.article_id', $article->id)
            ->select('tags.*')
            ->get();
        
        // Get views count
        $article->views_count = DB::table('article_views')
            ->where('article_id', $article->id)
            ->count();
        
        // Decode SEO meta
        $article->seo_meta = json_decode($article->seo_meta ?? '{}', true);
        
        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    /**
     * Create new article
     * 
     * POST /api/articles
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:articles,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|url',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);
        
        // Generate slug if not provided
        if (!isset($validated['slug'])) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        }
        
        // Prepare SEO meta
        $seoMeta = [
            'title' => $validated['seo_title'] ?? $validated['title'],
            'description' => $validated['seo_description'] ?? $validated['excerpt'] ?? '',
            'keywords' => $validated['seo_keywords'] ?? '',
        ];
        
        $articleId = DB::table('articles')->insertGetId([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 200),
            'featured_image' => $validated['featured_image'] ?? null,
            'category_id' => $validated['category_id'],
            'author_id' => auth()->id(),
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'published' ? Carbon::now() : null),
            'seo_meta' => json_encode($seoMeta),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // Attach tags
        if (isset($validated['tags']) && count($validated['tags']) > 0) {
            $tagData = [];
            foreach ($validated['tags'] as $tagId) {
                $tagData[] = [
                    'article_id' => $articleId,
                    'tag_id' => $tagId,
                    'created_at' => Carbon::now(),
                ];
            }
            DB::table('article_tags')->insert($tagData);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Article created successfully',
            'data' => [
                'id' => $articleId,
                'slug' => $validated['slug']
            ]
        ], 201);
    }

    /**
     * Update article
     * 
     * PUT /api/articles/{id}
     */
    public function update(Request $request, int $id)
    {
        $article = DB::table('articles')->where('id', $id)->first();
        
        if (!$article) {
            return response()->json([
                'success' => false,
                'error' => 'Article not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:articles,slug,' . $id,
            'content' => 'sometimes|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|url',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'status' => 'sometimes|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);
        
        // Update SEO meta if provided
        if (isset($validated['seo_title']) || isset($validated['seo_description']) || isset($validated['seo_keywords'])) {
            $currentSeo = json_decode($article->seo_meta ?? '{}', true);
            $seoMeta = [
                'title' => $validated['seo_title'] ?? $currentSeo['title'] ?? $article->title,
                'description' => $validated['seo_description'] ?? $currentSeo['description'] ?? '',
                'keywords' => $validated['seo_keywords'] ?? $currentSeo['keywords'] ?? '',
            ];
            $validated['seo_meta'] = json_encode($seoMeta);
            
            unset($validated['seo_title'], $validated['seo_description'], $validated['seo_keywords']);
        }
        
        // Update tags if provided
        if (isset($validated['tags'])) {
            DB::table('article_tags')->where('article_id', $id)->delete();
            
            if (count($validated['tags']) > 0) {
                $tagData = [];
                foreach ($validated['tags'] as $tagId) {
                    $tagData[] = [
                        'article_id' => $id,
                        'tag_id' => $tagId,
                        'created_at' => Carbon::now(),
                    ];
                }
                DB::table('article_tags')->insert($tagData);
            }
            
            unset($validated['tags']);
        }
        
        $validated['updated_at'] = Carbon::now();
        
        DB::table('articles')->where('id', $id)->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully'
        ]);
    }

    /**
     * Delete article
     * 
     * DELETE /api/articles/{id}
     */
    public function destroy(int $id)
    {
        $deleted = DB::table('articles')->where('id', $id)->delete();
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'error' => 'Article not found'
            ], 404);
        }
        
        // Delete tags
        DB::table('article_tags')->where('article_id', $id)->delete();
        
        // Delete views
        DB::table('article_views')->where('article_id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }

    /**
     * Record view
     * 
     * POST /api/articles/{id}/view
     */
    public function recordView(int $id)
    {
        $article = DB::table('articles')->where('id', $id)->first();
        
        if (!$article) {
            return response()->json([
                'success' => false,
                'error' => 'Article not found'
            ], 404);
        }
        
        DB::table('article_views')->insert([
            'article_id' => $id,
            'user_id' => auth()->id() ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'View recorded'
        ]);
    }

    /**
     * Get categories
     * 
     * GET /api/articles/categories
     */
    public function getCategories()
    {
        $categories = DB::table('categories')
            ->select([
                'categories.*',
                DB::raw('(SELECT COUNT(*) FROM articles WHERE category_id = categories.id AND status = "published") as articles_count')
            ])
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get tags
     * 
     * GET /api/articles/tags
     */
    public function getTags()
    {
        $tags = DB::table('tags')
            ->select([
                'tags.*',
                DB::raw('(SELECT COUNT(*) FROM article_tags WHERE tag_id = tags.id) as usage_count')
            ])
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }

    /**
     * Create category
     * 
     * POST /api/articles/categories
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
        ]);
        
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $categoryId = DB::table('categories')->insertGetId([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => ['id' => $categoryId]
        ], 201);
    }

    /**
     * Create tag
     * 
     * POST /api/articles/tags
     */
    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:tags,slug',
        ]);
        
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $tagId = DB::table('tags')->insertGetId([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'data' => ['id' => $tagId]
        ], 201);
    }

    /**
     * Generate unique slug
     */
    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (DB::table('articles')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Get published articles (public endpoint)
     * 
     * GET /api/articles/published
     */
    public function getPublished(Request $request)
    {
        $query = DB::table('articles')
            ->select([
                'articles.id',
                'articles.title',
                'articles.slug',
                'articles.excerpt',
                'articles.featured_image',
                'articles.category_id',
                'articles.published_at',
                DB::raw('(SELECT COUNT(*) FROM article_views WHERE article_id = articles.id) as views_count')
            ])
            ->where('status', 'published')
            ->where('published_at', '<=', Carbon::now())
            ->orderBy('published_at', 'desc');
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $articles = $query->paginate(12);
        
        return response()->json([
            'success' => true,
            'data' => $articles->items(),
            'pagination' => [
                'total' => $articles->total(),
                'per_page' => $articles->perPage(),
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
            ]
        ]);
    }
}
