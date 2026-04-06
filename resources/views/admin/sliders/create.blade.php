@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:720px;">
  <h1>Tambah Slider</h1>

  @if($errors->any())
    <div style="margin:12px 0; color:#b00020;">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="margin:10px 0;">
      <label>Title</label>
      <input type="text" name="title" value="{{ old('title') }}" style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Link URL</label>
      <input type="text" name="link_url" value="{{ old('link_url') }}" style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Sort Order</label>
      <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>
        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
        Aktif
      </label>
    </div>

    <div style="margin:10px 0;">
      <label>Gambar (jpg/png/webp, max 2MB) *</label>
      <input type="file" name="image" required>
    </div>

    <button type="submit">Simpan</button>
    <a href="{{ route('admin.sliders.index') }}" style="margin-left:10px;">Batal</a>
  </form>
</div>
@endsection
