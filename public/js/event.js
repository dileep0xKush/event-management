const API_BASE = '/api/events';

$(document).ready(function () {
    loadCategories();
    loadEvents();

    const form = $('#addEventForm');
    const title = $('#title');
    const category = $('#category_id');
    const publishAt = $('#publish_at');
    const photos = $('#photos');
    const submitBtn = form.find('button[type="submit"]');

    let touched = {
        title: false,
        category: false,
        publishAt: false,
        photos: false
    };

    title.on('input blur', () => {
        touched.title = true;
        validateForm();
    });

    category.on('change blur', () => {
        touched.category = true;
        validateForm();
    });

    publishAt.on('input change blur', () => {
        touched.publishAt = true;
        validateForm();
    });

    photos.on('change blur', () => {
        touched.photos = true;
        validateForm();
    });

    function validateForm() {
        let isValid = true;

        if (touched.title || title.val().trim().length > 0) {
            if (!title.val().trim() || title.val().trim().length < 3) {
                setInvalid(title, 'Title must be at least 3 characters.');
                isValid = false;
            } else {
                setValid(title);
            }
        } else {
            setValid(title);
        }

        if (touched.category || category.val()) {
            if (!category.val()) {
                setInvalid(category, 'Please select a category.');
                isValid = false;
            } else {
                setValid(category);
            }
        } else {
            setValid(category);
        }

        if (touched.publishAt || publishAt.val()) {
            const publishDate = new Date(publishAt.val());
            const now = new Date();
            if (!publishAt.val()) {
                setInvalid(publishAt, 'Publish date and time is required.');
                isValid = false;
            } else if (publishDate < now) {
                setInvalid(publishAt, 'Publish date & time cannot be in the past.');
                isValid = false;
            } else {
                setValid(publishAt);
            }
        } else {
            setValid(publishAt);
        }

        if (touched.photos || photos[0].files.length > 0) {
            if (photos[0].files.length > 5) {
                setInvalid(photos, 'You can upload a maximum of 5 photos.');
                isValid = false;
            } else {
                let allImages = true;
                for (let file of photos[0].files) {
                    if (!file.type.startsWith('image/')) {
                        allImages = false;
                        break;
                    }
                }
                if (!allImages) {
                    setInvalid(photos, 'Only image files are allowed.');
                    isValid = false;
                } else {
                    setValid(photos);
                }
            }
        } else {
            setValid(photos);
        }

        submitBtn.prop('disabled', !isValid);
        return isValid;
    }

    function setInvalid(element, message) {
        element.addClass('is-invalid');
        if (element.next('.invalid-feedback').length === 0) {
            element.after(`<div class="invalid-feedback">${message}</div>`);
        } else {
            element.next('.invalid-feedback').text(message);
        }
    }

    function setValid(element) {
        element.removeClass('is-invalid');
        element.next('.invalid-feedback').remove();
    }

    validateForm();

    // Filter change
    $('#filterStatus').on('change', loadEvents);

    // Form submit handler
    form.on('submit', function (e) {
        e.preventDefault();

        if (!validateForm()) return;

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
            success: function () {
                showToast("success", "Event added successfully");
                form[0].reset();

                // Reset touched state on successful submission
                touched = {
                    title: false,
                    category: false,
                    publishAt: false,
                    photos: false
                };

                validateForm(); // reset validation state
                loadEvents();
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message || 'Failed to add event.';
                showToast("error", 'Error: ' + msg);
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
        success: function (categories) {
            let select = $('#category_id');
            select.empty().append('<option value="" disabled selected>Select category</option>');

            function addOptions(cats, level = 0) {
                cats.forEach(cat => {
                    let indent = '–'.repeat(level * 2);
                    select.append(`<option value="${cat.id}">${indent} ${cat.name}</option>`);
                    if (cat.children) addOptions(cat.children, level + 1);
                });
            }

            addOptions(categories);
        },
        error: function () {
            showToast("error", "Failed to load categories.");
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
        success: function (events) {
            renderEventList(events, filter);
        },
        error: function () {
            $('#eventList').html('<tr><td colspan="7" class="text-danger text-center py-3">Failed to load events.</td></tr>');
        }
    });
}

// Render events in table
function renderEventList(events, filter) {
    let html = '';

    if (events.length === 0) {
        html = `<tr><td colspan="6" class="text-center py-3">No events found.</td></tr>`;
        $('#eventList').html(html);
        return;
    }

    const now = new Date();

    events.forEach(event => {
        let publishDate = new Date(event.publish_at);
        let isPublished = publishDate <= now;

        if (filter === 'published' && !isPublished) return;
        if (filter === 'unpublished' && isPublished) return;

        let photosHtml = '';
        if (event.event_images && event.event_images.length) {
            photosHtml = event.event_images.slice(0, 5).map(img => `
                <img src="/storage/${img.photo_path}" alt="Photo" class="img-thumbnail me-1" style="width: 50px; height: 50px; object-fit: cover;">
            `).join('');
        } else {
            photosHtml = '<small class="text-muted">No photos</small>';
        }

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
    if (!confirm('Are you sure you want to delete this event?')) return;

    $.ajax({
        url: API_BASE + '/' + id,
        method: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
            'Accept': 'application/json'
        },
        success: function () {
            showToast("success", "Event deleted successfully");
            loadEvents();
        },
        error: function () {
            showToast("error", "Failed to delete event.");
        }
    });
}
