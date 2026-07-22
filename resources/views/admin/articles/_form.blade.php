<div class="editor-layout">
    <section class="surface space-y-5 p-6 sm:p-8">
        <div class="field">
            <label class="label" for="title">Judul</label>
            <input
                id="title"
                class="input"
                name="title"
                value="{{ old('title', $article->title ?? '') }}"
                required
            >
        </div>

        <div class="field">
            <label class="label" for="excerpt">Ringkasan</label>
            <textarea
                id="excerpt"
                class="input min-h-24"
                name="excerpt"
            >{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
        </div>

        <div class="field">
            <label class="label" for="content">Isi artikel</label>
            <textarea
                id="content"
                class="input min-h-[420px]"
                name="content"
                required
            >{{ old('content', $article->content ?? '') }}</textarea>
            <p class="field-note">
                Versi starter menggunakan teks biasa. Editor rich text dapat ditambahkan kemudian.
            </p>
        </div>

        <div class="form-grid">
            <div class="field">
                <label class="label" for="meta-title">Meta title</label>
                <input
                    id="meta-title"
                    class="input"
                    name="meta_title"
                    value="{{ old('meta_title', $article->meta_title ?? '') }}"
                >
            </div>

            <div class="field">
                <label class="label" for="meta-description">Meta description</label>
                <textarea
                    id="meta-description"
                    class="input min-h-24"
                    name="meta_description"
                >{{ old('meta_description', $article->meta_description ?? '') }}</textarea>
            </div>
        </div>
    </section>

    <aside class="space-y-6">
        <section class="surface panel-padding">
            <div class="field">
                <label class="label" for="status">Status</label>
                <select id="status" class="input" name="status">
                    <option
                        value="draft"
                        @selected(old('status', $article->status ?? 'draft') === 'draft')
                    >
                        Draft
                    </option>
                    <option
                        value="published"
                        @selected(old('status', $article->status ?? '') === 'published')
                    >
                        Published
                    </option>
                </select>
            </div>

            <div class="field mt-5">
                <label class="label" for="published-at">Tanggal publikasi</label>
                <input
                    id="published-at"
                    class="input"
                    type="datetime-local"
                    name="published_at"
                    value="{{ old(
                        'published_at',
                        isset($article) && $article->published_at
                            ? $article->published_at->format('Y-m-d\\TH:i')
                            : ''
                    ) }}"
                >
            </div>
        </section>

        <section class="surface panel-padding">
            <label class="label" for="thumbnail">Thumbnail</label>

            @isset($article)
                @if($article->thumbnail)
                    <img
                        src="{{ $article->thumbnail_url }}"
                        class="mb-4 aspect-video w-full rounded-xl object-cover"
                        alt="Thumbnail artikel"
                    >
                @endif
            @endisset

            <input
                id="thumbnail"
                class="input"
                type="file"
                name="thumbnail"
                accept="image/*"
            >
        </section>

        <button class="btn-primary w-full">
            {{ isset($article) ? 'Simpan Artikel' : 'Buat Artikel' }}
        </button>
    </aside>
</div>
