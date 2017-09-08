<div id="editPrimaryContactsModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="editPrimaryContactsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="editPrimaryContactsModalLabel" class="modal-title">Edit Primary Contact</h4>
            </div>

            <div class="modal-body">
                <div id="original-primarycontacts">
                    <label for="original-primarycontacts-name">Orignal Name</label>
                    <input type="text" name="original-primarycontacts-name" id="original-primarycontacts-name" class="modal-input" readonly />

                    <label for="original-primarycontacts-email">Original Email</label>
                    <input type="text" name="original-primarycontacts-email" id="original-primarycontacts-email" class="modal-input" readonly />
                </div>

                <input type="hidden" id="edit-primarycontacts-record" />

                <label for="edit-primarycontacts-name">Name</label>
                <input type="text" name="edit-primarycontacts-name" id="edit-primarycontacts-name" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="edit-primarycontacts-email">Email</label>
                <input type="text" name="edit-primarycontacts-email" id="edit-primarycontacts-email" class="modal-input" />
                <span><label class="error"></label></span>

                <div id="manual-primarycontacts">
                    <label for="edit-primarycontacts-identifier">Primary Contact Identifier URL</label>
                    <input type="text" name="edit-primarycontacts-identifier" id="edit-primarycontacts-identifier" class="modal-input" />
                    <span><label class="error"></label></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="save_editor" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->