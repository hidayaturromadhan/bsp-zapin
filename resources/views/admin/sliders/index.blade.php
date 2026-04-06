@extends('layouts.admin')

@section('content')
<div class="container">
  <h1>Slider</h1>

  @if(session('success'))
    <div style="margin:10px 0; color:green;">{{ session('success') }}</div>
  @endif

  <p><a href="{{ route('admin.sliders.create') }}">+ Tambah Slider</a></p>

  <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th>Preview</th>
        <th>Title</th>
        <th>Order</th>
        <th>Active</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($sliders as $s)
        <tr>
          <td style="width:160px;">
            <img src="{{ asset($s->image_path) }}" style="width:150px; height:70px; object-fit:cover;">
          </td>
          <td>{{ $s->title }}</td>
          <td>{{ $s->sort_order }}</td>
          <td>{{ $s->is_active ? 'Ya' : 'Tidak' }}</td>
          <td>
            <a href="{{ route('admin.sliders.edit', $s) }}">Edit</a>
            <form method="POST" action="{{ route('admin.sliders.destroy', $s) }}" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" onclick="return confirm('Hapus slider ini?')">Hapus</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top:12px;">
    {{ $sliders->links() }}
  </div>
</div>
@endsection
