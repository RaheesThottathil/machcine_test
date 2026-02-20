<div id="view-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0;">Entry Details</h2>
            <button onclick="closeViewModal()" class="secondary" style="padding: 0.25rem 0.5rem;">&times;</button>
        </div>
        <div id="view-details" class="form-grid">
            <div class="form-group">
                <label>Name</label>
                <p id="view-name" style="margin: 0; font-weight: 500;"></p>
            </div>
            <div class="form-group">
                <label>Email</label>
                <p id="view-email" style="margin: 0; font-weight: 500;"></p>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <p id="view-amount" style="margin: 0; font-weight: 500;"></p>
            </div>
            <div class="form-group">
                <label>Assigned Staff</label>
                <p id="view-staff" style="margin: 0; font-weight: 500;"></p>
            </div>
        </div>
        <div id="view-image-container" style="margin-top: 1.5rem; text-align: center;">
            <label style="display: block; text-align: left; margin-bottom: 0.5rem;">Image</label>
            <img id="view-image" src="" alt="Entry Image" style="max-width: 100%; border-radius: 0.5rem; max-height: 300px; object-fit: contain; cursor: pointer;" onclick="showLightbox(this.src)">
        </div>
        <div style="margin-top: 2rem; text-align: right;">
            <button onclick="closeViewModal()" class="secondary">Close</button>
        </div>
    </div>
</div>
