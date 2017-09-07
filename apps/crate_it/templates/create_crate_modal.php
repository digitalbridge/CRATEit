<div id="createCrateModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="createCrateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="createCrateModalLabel" class="modal-title">New Crate</h4>
            </div>

            <div class="modal-body">
                <label for="crate_input_name">New Crate Name</label>
                <input type="text" name="New Crate Name" id="crate_input_name" class="modal-input" />
                <label id="crate_name_validation_error" class="error" validates="New Crate Name"></label>

                <label for="crate_input_description">New Crate Description</label>
                <textarea name="New Crate Description" id="crate_input_description" class="modal-input" maxlength="8001"></textarea>
                <label id="crate_description_validation_error" class="error" validates="New Crate Description"></label>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="create_crate_submit" class="btn btn-primary">Create</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->