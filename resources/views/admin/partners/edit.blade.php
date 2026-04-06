@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:1100px;">
    <div style="margin-bottom:18px;">
        <h1 style="margin:0; font-size:26px; font-weight:800;">Edit Data</h1>
        <p style="margin:6px 0 0; color:#6b7280;">Perbarui pelanggan atau mitra bisnis yang tampil di homepage.</p>
    </div>

    @if($errors->any())
        <div style="margin-bottom:16px; padding:12px 14px; border-radius:12px; background:#fff1f2; color:#b42318; border:1px solid #fecdd3;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.partners._form', [
            'submitLabel' => 'Update Data'
        ])
    </form>
</div>
@endsection