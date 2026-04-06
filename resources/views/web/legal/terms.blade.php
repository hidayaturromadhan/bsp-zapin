@extends('layouts.app')

@section('content')
<div class="page-shell">
    <h1>{{ $locale === 'id' ? 'Syarat & Ketentuan' : 'Terms & Conditions' }}</h1>

    <p>
        {{ $locale === 'id'
            ? 'Dengan menggunakan website ini, Anda menyetujui syarat berikut.'
            : 'By using this website, you agree to the following terms.' }}
    </p>

    <h3>{{ $locale === 'id' ? 'Penggunaan' : 'Usage' }}</h3>
    <p>{{ $locale === 'id' ? 'Website digunakan secara sah.' : 'Use the website lawfully.' }}</p>

    <h3>{{ $locale === 'id' ? 'Hak Cipta' : 'Copyright' }}</h3>
    <p>{{ $locale === 'id' ? 'Semua konten dilindungi.' : 'All content is protected.' }}</p>
</div>
@endsection