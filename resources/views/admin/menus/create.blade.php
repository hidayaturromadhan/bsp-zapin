@extends('layouts.admin')

@section('content')

<h2>Buat Menu</h2>

<form method="POST" action="{{ route('admin.menus.store') }}">

@csrf

<div>
<label>Label ID</label>
<input type="text" name="label_id" required>
</div>


<div>
<label>Label EN</label>
<input type="text" name="label_en">
</div>


<div>
<label>Type</label>

<select name="type" required>

<option value="url">Custom URL</option>

<option value="page">Page</option>

<option value="news">News</option>

</select>

</div>


<div>
<label>Pilih Page</label>

<select name="page_id">

<option value="">None</option>

@foreach($pages as $page)

@php
$t = $page->translations->where('locale','id')->first();
@endphp

<option value="{{ $page->id }}">
{{ $t ? $t->title : 'Page '.$page->id }}
</option>

@endforeach

</select>

</div>


<div>
<label>Pilih News</label>

<select name="news_id">

<option value="">None</option>

@foreach($news as $n)

@php
$t = $n->translations->where('locale','id')->first();
@endphp

<option value="{{ $n->id }}">
{{ $t ? $t->title : 'News '.$n->id }}
</option>

@endforeach

</select>

</div>


<div>
<label>Custom URL</label>
<input type="text" name="url">
</div>


<div>
<label>Parent Menu</label>

<select name="parent_id">

<option value="">None</option>

@foreach($parents as $parent)

<option value="{{ $parent->id }}">
{{ $parent->label_id }}
</option>

@endforeach

</select>

</div>


<div>
<label>Sort Order</label>
<input type="number" name="sort_order" value="0">
</div>


<div>
<label>

<input type="checkbox" name="is_active" value="1">

Active

</label>
</div>

<br>

<button type="submit">
Simpan
</button>

<a href="{{ route('admin.menus.index') }}">
Kembali
</a>

</form>

@endsection