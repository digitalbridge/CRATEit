<div class="modal" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="addContactModalLabel">Add Contact</h4>
      </div>
      <div class="modal-body">
        <p>Name</p>
        <input id="add-contact-name" type="text" name="add-contact-name" class="modal-input"></input>
        <p>
          <label style="color:red;display:none"></label>
        <p>
        <p>Email</p>
        <input id="add-contact-email" type="text" class="modal-input"></input>
        <p>
          <label style="color:red;display:none"></label>
        <p>
        <p>Contact Identifier URL</p>
        <input id="add-contact-identifier" type="text" class="modal-input"></input>
        <p>
          <label style="color:red;display:none"></label>
        <p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" disabled>Add</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->