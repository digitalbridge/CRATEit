<div id="addCreatorModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="addCreatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="addCreatorModalLabel" class="modal-title">Add Creator</h4>
            </div>

            <div class="modal-body">
                <label for="add-creator-name">Name</label>
                <input type="text" name="add-creator-name" id="add-creator-name" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="add-creator-email">Email</label>
                <input type="text" name="add-creator-email" id="add-creator-email" class="modal-input" />
                <span><label class="error"></label></span>

                <label for="add-creator-identifier">Creator Identifier URL</label>
                <input type="text" name="add-creator-identifier" id="add-creator-identifier" class="modal-input" />
                <span><label class="error"></label></span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" disabled>Add</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->