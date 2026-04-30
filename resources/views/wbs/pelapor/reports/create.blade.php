@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Buat Laporan WBS</h2>

    <div class="wbs-card">
        <form action="{{ route('wbs.pelapor.reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('wbs.pelapor.reports.partials.form', [
                'report' => null,
                'categoryOptions' => $categoryOptions,
                'submitLabel' => 'Kirim Laporan',
            ])
        </form>
    </div>
@endsection