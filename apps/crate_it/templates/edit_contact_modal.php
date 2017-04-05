<div class="modal" id="editContactsModal" tabindex="-1" role="dialog" aria-labelledby="editContactsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="editContactsModalLabel">Edit Contact</h4>
      </div>
      <div>
      </div>
      <div class="modal-body">
        <div id="original-contacts">
          <p>Orignal Name</p>
          <input id="original-contacts-name" type="text"  class="modal-input" readonly></input>
          <p>Original Email</p>
          <input id="original-contacts-email" type="text" class="modal-input" readonly></input>
        </div>
        <input id="edit-contacts-record" type="hidden"></input>
        <p>Name</p>
        <input id="edit-contacts-name" type="text" class="modal-input"></input>
        <p>
          <label style="color:red;display:none"></label>
        <p>
        <p>Email</p>
        <input id="edit-contacts-email" type="text" class="modal-input"></input>
        <p>
          <label style="color:red;display:none"></label>
        <p>
        <div id="manual-contacts">
          <p>Contact Identifier URL</p>
          <input id="edit-contacts-identifier" type="text" class="modal-input"></input>
          <p>
            <label style="color:red;display:none"></label>
          <p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="save_editor" type="button" class="btn btn-primary">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->