@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4">Dashboard</h1>

<div class="row">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Total Events</h5>
                <p class="card-text fs-3">12</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Users</h5>
                <p class="card-text fs-3">50</p>
            </div>
        </div>
    </div>
</div>
@endsection