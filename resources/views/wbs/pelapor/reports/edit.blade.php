@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Edit Laporan WBS</h2>

    <div class="wbs-card">
        <form action="{{ route('wbs.pelapor.reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('wbs.pelapor.reports.partials.form', [
                'report' => $report,
                'categoryOptions' => $categoryOptions,
                'submitLabel' => 'Simpan Perubahan',
            ])
        </form>
    </div>
@endsection