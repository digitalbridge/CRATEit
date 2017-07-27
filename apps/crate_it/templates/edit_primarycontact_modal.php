<div id="editPrimaryContactsModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="editPrimaryContactsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="editPrimaryContactsModalLabel" class="modal-title">Edit Primary Contact</h4>
            </div>

            <div></div>

            <div class="modal-body">
                <div id="original-primarycontacts">
                    <p>Orignal Name</p>
                    <input type="text" id="original-primarycontacts-name" class="modal-input" readonly />

                    <p>Original Email</p>
                    <input type="text" id="original-primarycontacts-email" class="modal-input" readonly />
                </div>

                <input type="hidden" id="edit-primarycontacts-record" />

                <p>Name</p>
                <input type="text" id="edit-primarycontacts-name" class="modal-input" />
                <p><label style="display: none;color: red;"></label></p>

                <p>Email</p>
                <input type="text" id="edit-primarycontacts-email" class="modal-input" />
                <p><label style="display: none; color: red;"></label></p>

                <div id="manual-primarycontacts">
                    <p>Primary Contact Identifier URL</p>
                    <input type="text" id="edit-primarycontacts-identifier" class="modal-input" />
                    <p><label style="display: none; color: red;"></label></p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="save_editor" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->