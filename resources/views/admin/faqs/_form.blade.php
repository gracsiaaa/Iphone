<div class="surface mx-auto max-w-3xl space-y-5 p-7 sm:p-8">
    <div class="field">
        <label class="label" for="question">Pertanyaan</label>
        <input
            id="question"
            class="input"
            name="question"
            value="{{ old('question', $faq->question ?? '') }}"
            required
        >
    </div>

    <div class="field">
        <label class="label" for="answer">Jawaban</label>
        <textarea
            id="answer"
            class="input min-h-40"
            name="answer"
            required
        >{{ old('answer', $faq->answer ?? '') }}</textarea>
    </div>

    <div class="form-grid">
        <div class="field">
            <label class="label" for="category">Kategori</label>
            <input
                id="category"
                class="input"
                name="category"
                value="{{ old('category', $faq->category ?? 'Umum') }}"
                required
            >
        </div>

        <div class="field">
            <label class="label" for="sort-order">Urutan</label>
            <input
                id="sort-order"
                class="input"
                type="number"
                min="0"
                name="sort_order"
                value="{{ old('sort_order', $faq->sort_order ?? 0) }}"
                required
            >
        </div>
    </div>

    <label class="check-row">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            @checked(old('is_active', $faq->is_active ?? true))
        >
        <span>Tampilkan FAQ</span>
    </label>

    <div class="form-actions">
        <button class="btn-primary">Simpan FAQ</button>
    </div>
</div>
