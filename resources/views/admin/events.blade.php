@extends('layouts.app')

@section('title', 'Event Management')

@section('content')
<div class="container py-5">


    {{-- Add Event Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-balloon me-2"></i> Add New Event
            </h5>
        </div>
        <div class="card-body">
            <form id="addEventForm" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Enter event title"
                        required>
                </div>

                <div class="col-md-6">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="" disabled selected>Select category</option>
                        {{-- Categories loaded dynamically --}}
                    </select>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="3" class="form-control"
                        placeholder="Enter event description"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="publish_at" class="form-label">Publish Date & Time <span
                            class="text-danger">*</span></label>
                    <input type="datetime-local" id="publish_at" name="publish_at" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="photos" class="form-label">Event Photos (Multiple)</label>
                    <input type="file" id="photos" name="photos[]" class="form-control" multiple accept="image/*">
                </div>

                <div class="col-12 d-grid">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Event List Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-folder me-2"></i> Event List</h6>
            <div>
                <select id="filterStatus" class="form-select form-select-sm">
                    <option value="all" selected>All Events</option>
                    <option value="published">Published</option>
                    <option value="unpublished">Waiting to Publish</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Publish At</th>
                        <th>Photos</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="eventList">
                    <tr>
                        <td colspan="7" class="text-center py-3">Loading events...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="{{ asset('js/event.js') }}"></script>

@endsection