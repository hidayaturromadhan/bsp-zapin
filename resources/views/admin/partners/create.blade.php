@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:1100px;">
    <div style="margin-bottom:18px;">
        <h1 style="margin:0; font-size:26px; font-weight:800;">Tambah Data</h1>
        <p style="margin:6px 0 0; color:#6b7280;">Tambahkan pelanggan atau mitra bisnis untuk tampil di homepage.</p>
    </div>

    @if($errors->any())
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; background:#fff1f2; color:#b42318; border:1px solid #fecdd3;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.partners._form', [
            'submitLabel' => 'Simpan Data'
        ])
    </form>
</div>
@endsection