@extends('layouts.admin')

@section('content')

<h2>Navbar Menu</h2>

<a href="{{ route('admin.menus.create') }}">
Tambah Menu
</a>

<br><br>

@if(session('success'))
    <div style="margin-bottom:12px; color:green;">
        {{ session('success') }}
    </div>
@endif

<table border="1" cellpadding="8">

<tr>
    <th>ID</th>
    <th>Label ID</th>
    <th>Label EN</th>
    <th>Type</th>
    <th>Parent</th>
    <th>Sort</th>
    <th>Active</th>
    <th>Action</th>
</tr>

@foreach($menus as $menu)
<tr>
    <td>{{ $menu->id }}</td>
    <td>{{ $menu->label_id }}</td>
    <td>{{ $menu->label_en }}</td>
    <td>{{ $menu->type }}</td>

    <td>
        @if($menu->parent)
            {{ $menu->parent->label_id }}
        @endif
    </td>

    <td>{{ $menu->sort_order }}</td>

    <td>
        {{ $menu->is_active ? 'Yes' : 'No' }}
    </td>

    <td>
        <a href="{{ route('admin.menus.edit', $menu) }}">
            Edit
        </a>

        <form
            method="POST"
            action="{{ route('admin.menus.destroy', $menu) }}"
            style="display:inline"
            onsubmit="return confirm('Hapus menu?')"
        >
            @csrf
            @method('DELETE')

            <button type="submit">
                Delete
            </button>
        </form>
    </td>
</tr>
@endforeach

</table>

@endsection