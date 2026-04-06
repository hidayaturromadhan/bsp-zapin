@extends('layouts.admin')

@section('content')

<h2>Dashboard</h2>

<div class="dashboard-grid">

<div class="dashboard-stat">
<h3>Total Pages</h3>
<strong>{{ $totalPages }}</strong>
</div>

<div class="dashboard-stat">
<h3>Total News</h3>
<strong>{{ $totalNews }}</strong>
</div>

<div class="dashboard-stat">
<h3>Total Menu</h3>
<strong>{{ $totalMenus }}</strong>
</div>

</div>


<div class="admin-card">

<h3>Quick Actions</h3>

<div class="dashboard-actions">

<a href="{{ route('admin.pages.index') }}">
Manage Pages
</a>

<a href="{{ route('admin.news.index') }}">
Manage News
</a>

<a href="{{ route('admin.menus.index') }}">
Manage Menu
</a>

</div>

</div>

@endsection