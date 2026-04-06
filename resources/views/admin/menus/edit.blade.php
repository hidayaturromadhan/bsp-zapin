@extends('layouts.admin')

@section('content')

<h2>Edit Menu</h2>

<form method="POST" action="{{ route('admin.menus.update',$menu) }}">

@csrf
@method('PUT')


<div>
<label>Label ID</label>

<input type="text"
name="label_id"
value="{{ old('label_id',$menu->label_id) }}"
required>

</div>



<div>
<label>Label EN</label>

<input type="text"
name="label_en"
value="{{ old('label_en',$menu->label_en) }}">

</div>



<div>
<label>Type</label>

<select name="type" required>

<option value="url"
@if($menu->type=='url') selected @endif>
Custom URL
</option>

<option value="page"
@if($menu->type=='page') selected @endif>
Page
</option>

<option value="news"
@if($menu->type=='news') selected @endif>
News
</option>

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

<option value="{{ $page->id }}"
@if($menu->page_id==$page->id) selected @endif>

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

<option value="{{ $n->id }}"
@if($menu->news_id==$n->id) selected @endif>

{{ $t ? $t->title : 'News '.$n->id }}

</option>

@endforeach

</select>

</div>



<div>
<label>Custom URL</label>

<input type="text"
name="url"
value="{{ old('url',$menu->url) }}">

</div>



<div>
<label>Parent Menu</label>

<select name="parent_id">

<option value="">None</option>

@foreach($parents as $parent)

<option value="{{ $parent->id }}"
@if($menu->parent_id==$parent->id) selected @endif>

{{ $parent->label_id }}

</option>

@endforeach

</select>

</div>



<div>
<label>Sort Order</label>

<input type="number"
name="sort_order"
value="{{ old('sort_order',$menu->sort_order) }}">

</div>



<div>

<label>

<input type="checkbox"
name="is_active"
value="1"
@if($menu->is_active) checked @endif>

Active

</label>

</div>


<br>

<button type="submit">
Update
</button>

<a href="{{ route('admin.menus.index') }}">
Kembali
</a>

</form>

@endsection