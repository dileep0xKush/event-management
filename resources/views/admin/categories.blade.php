@extends('layouts.app')

@section('title', 'Category Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📁 Category Management</h5>
                </div>
                <div class="card-body">
                    {{-- Add Category Form --}}
                    <form id="addCategoryForm" class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Enter category name"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label for="parent_id" class="form-label">Parent Category (optional)</label>
                            <select id="parent_id" class="form-select">
                                <option value="">None</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-success" type="submit">
                                <i class="bi bi-plus-circle"></i> Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Categories List --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">📋 All Categories</h6>
                </div>
                <div class="card-body" id="categoryList">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    const API_BASE = '/api/categories';

    $(document).ready(function () {
        // Load categories on page load
        loadCategories();

        // Handle form submission
        $('#addCategoryForm').on('submit', function (e) {
            e.preventDefault();

            const data = {
                name: $('#name').val(),
                parent_id: $('#parent_id').val() || null
            };

            $.ajax({
                url: API_BASE,
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Accept': 'application/json'
                },
                data: JSON.stringify(data),
                success: function () {
                    $('#name').val('');
                    $('#parent_id').val('');
                    loadCategories();
                },
                error: function (xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Failed to add category.'));
                }
            });
        });

        // Load categories into list and dropdown
        function loadCategories() {
            $.ajax({
                url: API_BASE,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Accept': 'application/json'
                },
                success: function (categories) {
                    renderCategoryList(categories);
                    populateParentSelect(categories);
                },
                error: function () {
                    $('#categoryList').html('<p class="text-danger">Failed to load categories.</p>');
                }
            });
        }

        function renderCategoryList(categories, level = 0) {
            let html = '';

            function renderRecursive(cats, level) {
                cats.forEach(cat => {
                    html += `
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2" style="padding-left: ${level * 20}px;">
                            <div><i class="bi bi-folder"></i> ${cat.name}</div>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${cat.id})">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>`;
                    if (cat.children && cat.children.length > 0) {
                        renderRecursive(cat.children, level + 1);
                    }
                });
            }

            renderRecursive(categories, level);
            $('#categoryList').html(html || '<em>No categories found.</em>');
        }

        function populateParentSelect(categories, level = 0) {
            const select = $('#parent_id');
            select.html('<option value="">None</option>');

            function addOptions(cats, level) {
                cats.forEach(cat => {
                    let indent = '–'.repeat(level * 2);
                    select.append(`<option value="${cat.id}">${indent} ${cat.name}</option>`);
                    if (cat.children) {
                        addOptions(cat.children, level + 1);
                    }
                });
            }

            addOptions(categories, level);
        }

        // Delete category function
        window.deleteCategory = function (id) {
            if (!confirm('Are you sure you want to delete this category?')) return;

            $.ajax({
                url: `${API_BASE}/${id}`,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Accept': 'application/json'
                },
                success: function () {
                    loadCategories();
                },
                error: function () {
                    alert('Failed to delete category.');
                }
            });
        };
    });
</script>
@endsection