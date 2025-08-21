@extends('layouts.app')

@section('title', 'Event Management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">

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
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="Enter event title" required>
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category <span
                                    class="text-danger">*</span></label>
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
                            <input type="datetime-local" id="publish_at" name="publish_at" class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="photos" class="form-label">Event Photos (Multiple)</label>
                            <input type="file" id="photos" name="photos[]" class="form-control" multiple
                                accept="image/*">
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
                    <h6 class="mb-0">📋 Event List</h6>
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
    </div>
</div>



<script>
    const API_BASE = '/api/events';

    $(document).ready(function () {
        loadCategories();
        loadEvents();

        // Filter change
        $('#filterStatus').on('change', loadEvents);

        // Add event form submission
        $('#addEventForm').on('submit', function(e) {
            e.preventDefault();
            const files = $('#photos')[0].files;
    if(files.length > 5) {
        alert('You can upload a maximum of 5 photos at a time.');
        return;  // stop form submission
    }


            let formData = new FormData(this);

            $.ajax({
                url: API_BASE,
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Accept': 'application/json'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    alert('Event added successfully!');
                    $('#addEventForm')[0].reset();
                    loadEvents();
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Failed to add event.';
                    alert('Error: ' + msg);
                }
            });
        });
    });

    // Load categories for the select dropdown
    function loadCategories() {
        $.ajax({
            url: '/api/categories',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: function(categories) {
                let select = $('#category_id');
                select.empty().append('<option value="" disabled selected>Select category</option>');

                function addOptions(cats, level = 0) {
                    cats.forEach(cat => {
                        let indent = '–'.repeat(level * 2);
                        select.append(`<option value="${cat.id}">${indent} ${cat.name}</option>`);
                        if(cat.children) addOptions(cat.children, level + 1);
                    });
                }

                addOptions(categories);
            },
            error: function() {
                alert('Failed to load categories.');
            }
        });
    }

    // Load events with filter
    function loadEvents() {
        let filter = $('#filterStatus').val();

        $.ajax({
            url: API_BASE,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: function(events) {
                renderEventList(events, filter);
            },
            error: function() {
                $('#eventList').html('<tr><td colspan="7" class="text-danger text-center py-3">Failed to load events.</td></tr>');
            }
        });
    }

    // Render events in table
    function renderEventList(events, filter) {
    let html = '';

    if(events.length === 0) {
        html = `<tr><td colspan="6" class="text-center py-3">No events found.</td></tr>`;
        $('#eventList').html(html);
        return;
    }

    const now = new Date();

    events.forEach(event => {
        let publishDate = new Date(event.publish_at);
        let isPublished = publishDate <= now;

        if(filter === 'published' && !isPublished) return;
        if(filter === 'unpublished' && isPublished) return;

        let photosHtml = '';
        if(event.event_images && event.event_images.length) {
            photosHtml = event.event_images.slice(0,5).map(img => `
               <img src="/storage/${img.photo_path}" alt="Photo" class="img-thumbnail me-1" style="width: 50px; height: 50px; object-fit: cover;">
            `).join('');
        } else {
            photosHtml = '<small class="text-muted">No photos</small>';
        }

        // Removed userName since no Created By column now

        let statusBadge = isPublished
            ? '<span class="badge bg-success">Published</span>'
            : '<span class="badge bg-warning text-dark">Waiting to Publish</span>';

        html += `
            <tr>
                <td>${event.title}</td>
                <td>${event.category?.name || 'N/A'}</td>
                <td>${publishDate.toLocaleString()}</td>
                <td>${photosHtml}</td>
                <td>${statusBadge}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteEvent(${event.id})">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
            </tr>
        `;
    });

    $('#eventList').html(html);
}


    // Delete event with confirmation
    function deleteEvent(id) {
        if(!confirm('Are you sure you want to delete this event?')) return;

        $.ajax({
            url: API_BASE + '/' + id,
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: function() {
                alert('Event deleted successfully!');
                loadEvents();
            },
            error: function() {
                alert('Failed to delete event.');
            }
        });
    }
</script>
@endsection