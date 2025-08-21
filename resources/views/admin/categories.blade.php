@extends('layouts.app')

@section('title', 'Category Management')

@section('content')
<div class="container py-5">


    {{-- Category Management Header --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-folder me-2"></i> Category Management
            </h5>
        </div>

        {{-- Add Category Form --}}
        <div class="card-body">
            <form id="addCategoryForm" class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" id="name" class="form-control" placeholder="Enter category name" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="col-md-4">
                    <label for="parent_id" class="form-label">Parent Category (optional)</label>
                    <select id="parent_id" class="form-select">
                        <option value="">None</option>
                    </select>
                </div>

                <div class="col-md-2 mt-5 align-items-end">
                    <button class="btn btn-success w-100" type="submit">
                        <i class="bi bi-plus-circle me-1"></i> Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- All Categories --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-folder me-2"></i> All Categories</h6>
        </div>
        <div class="card-body" id="categoryList">
            <p>Loading...</p>
        </div>

    </div>
</div>

<script src="{{ asset('js/category.js') }}"></script>

@endsection