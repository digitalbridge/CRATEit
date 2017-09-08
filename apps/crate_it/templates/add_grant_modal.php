<div id="addGrantModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="addGrantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="addGrantModalLabel" class="modal-title">Add Grant</h4>
            </div>

            <div class="modal-body">
                <label for="add-grant-number">Number</label>
                <input type="text" name="add-grant-number" id="add-grant-number" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="add-grant-year">Submit Year</label>
                <input type="text" name="add-grant-year"  id="add-grant-year" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="add-grant-institution">Institution</label>
                <input type="text" name="add-grant-institution" id="add-grant-institution" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="add-grant-title">Title</label>
                <input type="text" name="add-grant-title" id="add-grant-title" class="modal-input" />
                <span><label class="error"></label></span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" disabled>Add</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->