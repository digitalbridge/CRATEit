<div id="addPrimaryContactModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="addPrimaryContactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="addPrimaryContactModalLabel" class="modal-title">Add Primary Contact</h4>
            </div>

            <div class="modal-body">
                <label for="add-primarycontact-name">Name</label>
                <input type="text" name="add-primarycontact-name" id="add-primarycontact-name" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="add-primarycontact-email">Email</label>
                <input type="text" name="add-primarycontact-email" id="add-primarycontact-email" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="add-primarycontact-identifier">Primary Contact Identifier URL</label>
                <input type="text" name="add-primarycontact-identifier" id="add-primarycontact-identifier" class="modal-input" />
                <span><label class="error"></label></span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" disabled>Add</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->