@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:720px;">
  <h1>Edit Slider</h1>

  @if($errors->any())
    <div style="margin:12px 0; color:#b00020;">{{ $errors->first() }}</div>
  @endif

  <div style="margin:10px 0;">
    <img src="{{ asset($slider->image_path) }}" style="width:100%; max-height:240px; object-fit:cover;">
  </div>

  <form method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="margin:10px 0;">
      <label>Title</label>
      <input type="text" name="title" value="{{ old('title', $slider->title) }}" style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Link URL</label>
      <input type="text" name="link_url" value="{{ old('link_url', $slider->link_url) }}" style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>Sort Order</label>
      <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}" style="width:100%;">
    </div>

    <div style="margin:10px 0;">
      <label>
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
        Aktif
      </label>
    </div>

    <div style="margin:10px 0;">
      <label>Ganti Gambar (opsional)</label>
      <input type="file" name="image">
    </div>

    <button type="submit">Update</button>
    <a href="{{ route('admin.sliders.index') }}" style="margin-left:10px;">Kembali</a>
  </form>
</div>
@endsection
