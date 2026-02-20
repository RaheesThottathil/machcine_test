<div id="entry-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 id="modal-title" style="margin: 0;">Create New Entry</h2>
            <button onclick="closeEntryModal()" class="secondary" style="padding: 0.25rem 0.5rem;">&times;</button>
        </div>
        <form id="entry-form">
            <input type="hidden" id="entry_id" name="entry_id">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="staff_id">Assign to Staff</label>
                    <select id="staff_id" name="staff_id" required>
                        <option value="">Select Staff</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeEntryModal()" class="secondary">Cancel</button>
                <button type="submit" id="modal-submit-btn">Create Entry</button>
            </div>
        </form>
    </div>
</div>
