
const API_BASE = '/api/categories';

$(document).ready(function () {
    loadCategories();

    const form = $('#addCategoryForm');
    const name = $('#name');
    const parent = $('#parent_id');
    const submitBtn = form.find('button[type="submit"]');

    let touched = {
        name: false
    };

    // Bind events to mark fields as touched
    name.on('input blur', () => {
        touched.name = true;
        validateForm();
    });

    // Validate form fields
    function validateForm() {
        let isValid = true;

        if (touched.name || name.val().trim()) {
            const val = name.val().trim();
            if (!val || val.length < 3) {
                setInvalid(name, 'Category name must be at least 3 characters.');
                isValid = false;
            } else {
                setValid(name);
            }
        } else {
            setValid(name);
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

    validateForm(); // On page load

    // Form submit with validation
    form.on('submit', function (e) {
        e.preventDefault();

        if (!validateForm()) return;

        const data = {
            name: name.val().trim(),
            parent_id: parent.val() || null
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
                name.val('');
                parent.val('');
                touched.name = false;
                validateForm();
                loadCategories();
            },
            error: function (xhr) {
                showToast("error", 'Error: ' + (xhr.responseJSON?.message || 'Failed to add category.'));
            }
        });
    });

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
                showToast('success', 'Category deleted successfully!');
                loadCategories();

            },
            error: function () {
                showToast("error", "Failed to delete category.");
            }
        });
    };



});
