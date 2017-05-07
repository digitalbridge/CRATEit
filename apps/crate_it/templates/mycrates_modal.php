<div class="modal" id="mycratesModal" tabindex="-1" role="dialog" aria-labelledby="mycratesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title" id="mycratesModalLabel">My Crates</h2>
        <p>
          <br />
			Message Placeholder<br />
        </p>
      </div>
      <div class="modal-body">
      <h3>My Crates</h3> 
      
      <ul>
      <?php foreach($_['crateDetails'] as $crateDetail) {
      	echo "<strong>" . $crateDetail['name'] . "</strong>";
      	echo " | ";
      	echo $crateDetail['size']['human'];
      	echo " | ";
      	//echo $crateDetail['contents']['0'];
      	//echo " | ";
      	echo $crateDetail[0]['description'];
      	echo " | ";
      	echo $crateDetail[0]['submitter']['email'];
      	echo " | ";
      	echo $crateDetail[0]['submitter']['displayname'];
      	echo " | ";
      	echo $crateDetail[0]['data_retention_period'];
      	echo " | ";
      	echo "<br />";
      }?>
      </ul>
      
       <label style="color:red;display:none">Error: No CRATES available</label>
      </div>
  	</div>
  </div>
</div>
<style>

</style>