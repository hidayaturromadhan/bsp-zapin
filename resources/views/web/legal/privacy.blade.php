@extends('layouts.app')

@section('content')
<div class="page-shell">
    <h1>{{ $locale === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}</h1>

    <p>
        {{ $locale === 'id'
            ? 'Kami berkomitmen melindungi data pribadi pengguna.'
            : 'We are committed to protecting user personal data.' }}
    </p>

    <h3>Data</h3>
    <p>{{ $locale === 'id' ? 'Kami dapat mengumpulkan data seperti nama, email.' : 'We may collect data such as name and email.' }}</p>

    <h3>Penggunaan</h3>
    <p>{{ $locale === 'id' ? 'Digunakan untuk layanan dan komunikasi.' : 'Used for service and communication.' }}</p>
</div>
@endsection