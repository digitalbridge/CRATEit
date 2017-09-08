<div id="editActivitiesModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="editActivitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="editActivitiesModalLabel" class="modal-title">Edit Grant</h4>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit-activities-record" />

                <label for="edit-activities-grant_number">Number</label>
                <input type="text" name="edit-activities-grant_number" id="edit-activities-grant_number" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="edit-activities-date">Submit Year</label>
                <input type="text" name="edit-activities-date" id="edit-activities-date" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="edit-activities-institution">Institution</label>
                <input type="text" name="edit-activities-institution" id="edit-activities-institution" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="edit-activities-title">Title</label>
                <input type="text" name="edit-activities-title" id="edit-activities-title" class="modal-input" />
                <span><label class="error"></label></span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->