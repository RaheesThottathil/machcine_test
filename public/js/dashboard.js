// Show Toast
function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

// Toggle Loader
function toggleLoader(show) {
    document.getElementById('loading-overlay').style.display = show ? 'flex' : 'none';
}

// Show Lightbox
function showLightbox(src) {
    const lightbox = document.getElementById('lightbox');
    lightbox.querySelector('img').src = src;
    lightbox.style.display = 'flex';
}

// Modal Handlers
function openEntryModal(entry = null) {
    const modal = document.getElementById('entry-modal');
    const title = document.getElementById('modal-title');
    const submitBtn = document.getElementById('modal-submit-btn');
    const form = document.getElementById('entry-form');
    const entryIdInput = document.getElementById('entry_id');

    if (entry) {
        title.textContent = 'Edit Entry';
        submitBtn.textContent = 'Update Entry';
        entryIdInput.value = entry.id;
        form.name.value = entry.name;
        form.email.value = entry.email;
        form.amount.value = entry.amount;
        form.staff_id.value = entry.staff_id;
    } else {
        title.textContent = 'Create New Entry';
        submitBtn.textContent = 'Create Entry';
        form.reset();
        entryIdInput.value = '';
    }

    if (modal) modal.style.display = 'flex';
}

function closeEntryModal() {
    const modal = document.getElementById('entry-modal');
    if (modal) modal.style.display = 'none';
}

function openViewModal(entry) {
    document.getElementById('view-name').textContent = entry.name;
    document.getElementById('view-email').textContent = entry.email;
    document.getElementById('view-amount').textContent = `${parseFloat(entry.amount).toLocaleString()}`;
    document.getElementById('view-staff').textContent = entry.staff ? entry.staff.name : 'Unassigned';

    const viewImage = document.getElementById('view-image');
    if (entry.image) {
        viewImage.src = `/storage/${entry.image}`;
        document.getElementById('view-image-container').style.display = 'block';
    } else {
        document.getElementById('view-image-container').style.display = 'none';
    }

    document.getElementById('view-modal').style.display = 'flex';
}

function closeViewModal() {
    document.getElementById('view-modal').style.display = 'none';
}

// Actions
async function viewEntry(id) {
    toggleLoader(true);
    try {
        const response = await axios.get(`${API_URL}/${id}`);
        openViewModal(response.data.entry);
    } catch (error) {
        showToast('Failed to fetch entry details');
    } finally {
        toggleLoader(false);
    }
}

async function editEntry(id) {
    toggleLoader(true);
    try {
        const response = await axios.get(`${API_URL}/${id}`);
        openEntryModal(response.data.entry);
    } catch (error) {
        showToast('Failed to fetch entry data');
    } finally {
        toggleLoader(false);
    }
}

async function deleteEntry(id) {
    if (!confirm('Are you sure you want to delete this entry?')) return;

    toggleLoader(true);
    try {
        await axios.delete(`${API_URL}/${id}`);
        showToast('Entry deleted successfully');
        if (window.dataTable) window.dataTable.ajax.reload();
    } catch (error) {
        showToast('Failed to delete entry');
    } finally {
        toggleLoader(false);
    }
}

// DataTables Initialization
let dataTable;

function initDataTable() {
    dataTable = $('#entries-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: API_URL,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataSrc: function (json) {

                if (USER_ROLE === 'admin' && json.staff_members) {
                    const staffSelect = document.getElementById('staff_id');
                    if (staffSelect) {
                        const currentVal = staffSelect.value;
                        staffSelect.innerHTML = '<option value="">Select Staff</option>';
                        json.staff_members.forEach(staff => {
                            const option = document.createElement('option');
                            option.value = staff.id;
                            option.textContent = staff.name;
                            staffSelect.appendChild(option);
                        });
                        staffSelect.value = currentVal;
                    }
                }
                return json.data;
            }
        },
        columns: [
            {
                data: 'image_url',
                name: 'image',
                render: function (data, type, row) {
                    return `<img src="${data}" class="avatar" alt="${row.name}" onclick="showLightbox('${data}')">`;
                },
                orderable: false,
                searchable: false
            },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            {
                data: 'amount',
                name: 'amount',
                render: function (data) {
                    return parseFloat(data).toLocaleString();
                }
            },
            { data: 'staff_name', name: 'staff.name' },
            {
                data: 'id',
                name: 'id',
                render: function (data, type, row) {
                    let actions = `<button onclick="viewEntry(${data})" class="secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">View</button>`;

                    if (USER_ROLE === 'admin') {
                        actions += `
                            <button onclick="editEntry(${data})" class="btn-edit" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Edit</button>
                            <button onclick="deleteEntry(${data})" class="btn-delete" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Delete</button>
                        `;
                    } else if (USER_ROLE === 'staff') {
                        actions += `
                            <input type="file" id="file-${data}" style="display: none;" onchange="uploadImage(${data})">
                            <button onclick="document.getElementById('file-${data}').click()" class="secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                ${row.image ? 'Change Image' : 'Add Image'}
                            </button>
                        `;
                    }
                    return `<div style="display: flex; gap: 0.5rem;">${actions}</div>`;
                },
                orderable: false,
                searchable: true,
            }
        ],
        language: {
            processing: '<div class="spinner"></div>'
        }
    });
    window.dataTable = dataTable;
}

// Create/Update 
const entryForm = document.getElementById('entry-form');
if (entryForm) {
    entryForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        const entryId = data.entry_id;

        toggleLoader(true);
        try {
            if (entryId) {
                await axios.put(`${API_URL}/${entryId}`, data);
                showToast('Entry updated successfully!');
            } else {
                await axios.post(API_URL, data);
                showToast('Entry created successfully!');
            }
            e.target.reset();
            closeEntryModal();
            if (window.dataTable) window.dataTable.ajax.reload();
        } catch (error) {
            console.error('Error saving entry:', error);
            showToast('Failed to save entry');
        } finally {
            toggleLoader(false);
        }
    });
}

// Upload Image (Staff Only)
async function uploadImage(entryId) {
    const fileInput = document.getElementById(`file-${entryId}`);
    if (!fileInput || !fileInput.files[0]) return;

    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('image', file);

    toggleLoader(true);
    try {
        await axios.post(`${API_URL}/${entryId}/image`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        showToast('Image uploaded successfully!');
        if (window.dataTable) window.dataTable.ajax.reload();
    } catch (error) {
        console.error('Error uploading image:', error);
        const message = error.response?.data?.message || 'Failed to upload image';
        showToast(message);
    } finally {
        toggleLoader(false);
        fileInput.value = ''; // Reset input after attempt
    }
}

// Initial Load
document.addEventListener('DOMContentLoaded', () => {
    initDataTable();
});
