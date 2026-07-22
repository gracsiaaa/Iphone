<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('admin.articles.index', ['articles' => Article::with('author')->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($data['title']);
        if ($request->hasFile('thumbnail')) $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        if ($data['status'] === 'published' && empty($data['published_at'])) $data['published_at'] = now();
        $article = Article::create($data);
        ActivityLogger::log($request, 'article.created', "Membuat artikel {$article->title}", $article);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['title'], $article->id);
        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail) Storage::disk('public')->delete($article->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }
        if ($data['status'] === 'published' && empty($data['published_at'])) $data['published_at'] = now();
        $article->update($data);
        ActivityLogger::log($request, 'article.updated', "Memperbarui artikel {$article->title}", $article);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Request $request, Article $article): RedirectResponse
    {
        $article->delete();
        ActivityLogger::log($request, 'article.deleted', "Menghapus artikel {$article->title}", $article);
        return back()->with('success', 'Artikel dipindahkan ke arsip.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value); $slug = $base; $i = 2;
        while (Article::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}
