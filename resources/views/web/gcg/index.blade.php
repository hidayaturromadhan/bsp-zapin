@extends('layouts.app')

@section('title', 'GCG')

@section('content')

<style>
.gcg-wrap { display: flex; flex-direction: column; gap: 24px; }

.gcg-header h1 { font-size: 28px; font-weight: 700; }
.gcg-header p { color: #6b7280; font-size: 14px; }

.gcg-filter { display: flex; gap: 10px; flex-wrap: wrap; }

.gcg-filter button {
    padding: 8px 16px;
    border-radius: 999px;
    border: 1px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    font-size: 13px;
}

.gcg-filter button.active,
.gcg-filter button:hover {
    background: #2f7d32;
    color: #fff;
}

.gcg-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 18px;
}

.gcg-card {
    display: block;
    background: #fff;
    border-radius: 16px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    transition: .2s;
    text-decoration: none;
    color: inherit;
}

.gcg-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 28px rgba(0,0,0,.08);
}

.gcg-title { font-weight: 600; font-size: 15px; }
.gcg-meta { font-size: 12px; color: #6b7280; }
</style>

<div class="gcg-wrap">

    <div class="gcg-header">
        <h1>Good Corporate Governance</h1>
        <p>Pedoman dan dokumen tata kelola perusahaan</p>
    </div>

    <!-- FILTER -->
    <div class="gcg-filter">
        <button class="active" data-filter="all">All</button>
        @foreach($categories as $cat)
            @php
                $t = $cat->translations->firstWhere('locale', $locale)
                    ?? $cat->translations->first();
            @endphp
            <button data-filter="{{ $t->slug }}">{{ $t->title }}</button>
        @endforeach
    </div>

    <!-- GRID -->
    <div class="gcg-grid">

        @foreach($categories as $cat)
            @php
                $t = $cat->translations->firstWhere('locale', $locale)
                    ?? $cat->translations->first();
            @endphp

            <a href="{{ route('gcg.show', ['locale' => $locale, 'slug' => $t->slug]) }}"
               class="gcg-card"
               data-category="{{ $t->slug }}">

                <div class="gcg-title">{{ $t->title }}</div>
                <div class="gcg-meta">{{ $cat->activeDocuments->count() }} Dokumen</div>
            </a>
        @endforeach

    </div>

</div>

<script>
document.querySelectorAll('.gcg-filter button').forEach(btn => {
    btn.addEventListener('click', () => {

        document.querySelectorAll('.gcg-filter button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.dataset.filter;

        document.querySelectorAll('.gcg-card').forEach(card => {
            if (filter === 'all' || card.dataset.category === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

@endsection