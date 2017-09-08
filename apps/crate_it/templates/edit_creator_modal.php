<div id="editCreatorsModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="editCreatorsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="editCreatorsModalLabel" class="modal-title">Edit Creator</h4>
            </div>

            <div class="modal-body">
                <div id="original-creators">
                    <label for="original-creators-name">Orignal Name</label>
                    <input type="text" id="original-creators-name" class="modal-input" readonly />

                    <label for="original-creators-email">Original Email</label>
                    <input type="text" id="original-creators-email" class="modal-input" readonly />
                </div>

                <input type="hidden" id="edit-creators-record" />

                <label for="edit-creators-name">Name</label>
                <input type="text" name="edit-creators-name" id="edit-creators-name" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="edit-creators-email">Email</label>
                <input type="text" name="edit-creators-email" id="edit-creators-email" class="modal-input" />
                <span><label class="error"></label></span>

                <div id="manual-creators">
                    <label for="edit-creators-identifier">Creator Identifier URL</label>
                    <input type="text" name="edit-creators-identifier" id="edit-creators-identifier" class="modal-input" />
                    <span><label class="error"></label></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="save_editor" type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->