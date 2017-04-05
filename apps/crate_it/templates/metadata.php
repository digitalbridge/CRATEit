<div class="container-metadata">

    <div class="panel-group" id="meta-data">

    	<!-- CRATE Info -->
 	    <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#meta-data" href="#crate-information" id="crate-information-head">
                        Crate Information
                        <i class="pull-right fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>

            <div id="crate-information" class="panel-collapse collapse in standard">
                <div class="panel-body">
                    <div id='description_box'>
                        <h6>
                            Description
                            <button id="edit_description" class="pull-right trans-button" type="button"
                                    placeholder="Edit"><i class="fa fa-edit"></i></button>
                        </h6>
                        <div id="description" style="white-space: pre-wrap;"
                             class="metadata"><?php p($_[trim('description')]) ?></div>
                    </div>
                    <div class='crate-size'>
                        <h6 class="info">
                            Crate Size: <span id="crate_size_human" class="standard"></span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>

		<!-- FOR -->
 		<div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#meta-data" href="#field-of-research" id="field-of-research-head">
                        Field of Research (FOR)
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>
			
            <div id="field-of-research" class="panel-collapse collapse standard">
           		<div class="panel-body">
                	<h6>FIELD OF RESEARCH</h6>
            	</div>
			</div>
		</div>
			
		<!-- Search for Data Contacts -->
         <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#meta-data" href="#data-contacts" id="data-contacts-head">
                        Data Contacts
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>

            <div id="data-contacts" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="contacts_box" class="data-contacts">
                        <h6>Selected Data Contacts (<span id="contacts_count"></span>)
                            <button id="clear_contacts" class="pull-right trans-button" type="button">
                                <i class="fa fa-times muted"></i>
                            </button>
                        </h6>
                        <ul id="selected_contacts" class="metadata"></ul>
                        <h6>Add New Data Contacts
                            <button id="add-contact" class="pull-right trans-button" type="button"
                                    data-toggle="modal" data-target="#addContactModal">
                                <i class="fa fa-plus muted"></i>
                            </button>
                        </h6>
                        <div id="search_contacts_box" class="input-group">
                            <label for="keyword_contact" class="element-invisible">Search Contacts</label>
                            <input id="keyword_contact" class="form-control" type="text" name="keyword"
                                   placeholder="Search Contacts..."/>
                            <span class="input-group-btn">
                                <button id="search_contacts" class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <span id="contacts_search_notification"></span>
                        <div id="search_contacts_result_box">
                            <ul id="search_contacts_results"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

		<!-- Search for Data Creators -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#meta-data" href="#data-creators" id="data-creators-head">
                        Data Creators
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>

            <div id="data-creators" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="creators_box" class="data-creators">
                        <h6>Selected Data Creators (<span id="creators_count"></span>)
                            <button id="clear_creators" class="pull-right trans-button" type="button">
                                <i class="fa fa-times muted"></i>
                            </button>
                        </h6>
                        <ul id="selected_creators" class="metadata"></ul>
                        <h6>Add New Data Creators
                            <button id="add-creator" class="pull-right trans-button" type="button"
                                    data-toggle="modal" data-target="#addCreatorModal">
                                <i class="fa fa-plus muted"></i>
                            </button>
                        </h6>
                        <div id="search_creators_box" class="input-group">
                            <label for="keyword_creator" class="element-invisible">Search Creators</label>
                            <input id="keyword_creator" class="form-control" type="text" name="keyword"
                                   placeholder="Search Creators..."/>
                            <span class="input-group-btn">
                                <button id="search_creators" class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <span id="creators_search_notification"></span>
                        <div id="search_creators_result_box">
                            <ul id="search_creators_results"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<!-- Search for GRANTSs -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#meta-data" href="#grant-numbers" id="grant-numbers-head">
                        Grants
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>

            <div id="grant-numbers" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="activities_box" class="grant-numbers">
                        <h6>Selected Grants (<span id="activities_count"></span>)
                            <button id="clear_grant_numbers" class="pull-right trans-button" type="button">
                                <i class="fa fa-times muted"></i>
                            </button>
                        </h6>
                        <ul id="selected_activities" class="metadata">
                            <!-- TODO: Be more consistent with naming, are they "grants" or "activities"? -->
                        </ul>
                        <h6>Add New Grants
                            <button id="add-activity" class="pull-right trans-button" type="button"
                                    data-toggle="modal" data-target="#addGrantModal">
                                <i class="fa fa-plus muted"></i>
                            </button>
                        </h6>
                        <div id="search_activity_box" class="input-group">
                            <label for="keyword_activity" class="element-invisible">Search Grants</label>
                            <input id="keyword_activity" class="form-control" type="text" name="keyword_activity"
                                   placeholder="Search Grants..."/>
                            <span class="input-group-btn">
                                <button id="search_activity" class="btn btn-default" type="button"
                                        value="Search Grant Number">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <span id="activites_search_notification"></span>

                        <div id="search_activity_result_box">
                            <ul id="search_activity_results"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<!-- DATA RETENTION -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#meta-data" href="#data-retention-period" id="data-retention-period-head">
                        Data Retention Period
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>
            <div id="data-retention-period" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id='retention_peroid_list'>
                        <h6>
                            DATA RETENTION PERIOD (YEARS)
                            <button id="choose_retention_period" class="pull-right trans-button" type="button"
                                    placeholder="Choose"><i class="fa fa-edit"></i></button>
                        </h6>
                        <div id="retention_period_value" style="white-space: pre-wrap;"
                             class="metadata"><?php p(($_['data_retention_period'] !== null) || ($_['data_retention_period'] !== '') ? $_['data_retention_period'] : 'Perpetuity') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACCESS CONDITIONS  -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#meta-data" href="#access-conditions" id="access-conditions-head">
                        Access Conditions
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>
			
            <div id="access-conditions" class="panel-collapse collapse standard">
           		<div class="panel-body">
                	<h6>ACCESS CONDITIONS
                	<button id="choose_access_conditions" class="pull-right trans-button" type="button" placeholder="Choose"><i class="fa fa-edit"></i></button>
                	</h6>
            	
            		<div id="edit_access_conditions" class="metadata">
                		<div>
                        	<div>
								<ul>
									<li>Closed (no public access)</li>
									<li>Open (Public Access)</li>
									<li>Shared</li>
								</ul>	
							</div>
                    	</div>
					</div>
				</div>
			</div>
		</div>

		<!-- EMBARGO DETAILS  -->
    	<div class="panel panel-default">
        	<div class="panel-heading">
            	<h4 class="panel-title">
                	<a data-toggle="collapse" data-parent="#meta-data" href="#embargo-details" id="embargo-details-head">
                    	Embargo Details
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>
			
            <div id="embargo-details" class="panel-collapse collapse standard">
            	<div class="panel-body">
                	<h6>EMBARGO DETAILS
                		<button id="choose_embargo_details" class="pull-right trans-button" type="button" placeholder="Choose"><i class="fa fa-edit"></i></button>
                	</h6>

                	<div id="edit_embargo_details" class="metadata hidden">
                		<div>
                    		Embargo Enabled
                        	<div>
                        		<input id="embargo_enabled_yes" type="radio" name="embargo_enabled" <?php p($_['embargo_enabled'] === 'true' ? 'checked' : '') ?>>
                            	<label for="embargo_enabled_yes">Yes</label>
                            	<input id="embargo_enabled_no"  type="radio" name="embargo_enabled" <?php p($_['embargo_enabled'] === 'false' ? 'checked' : '') ?>>
                            	<label for="embargo_enabled_no">No</label>
                        	</div>
                    	</div>
                    	<br/>
                    	<div class='input-append date' id='embargo_datetime_picker_button'>
                    		Embargo Date
                        	<div>
                        		<input id="embargo_date" name="embargo_date" type='text' data-format="yyyy-MM-dd" disabled="disabled" value="<?php p($_['embargo_date']) ?>">
                            		<span class="add-on">
                                		<i id="embargo_datetimepicker_icon" class="fa fa-calendar embargo_datetime_icon"></i>
                                	</span>
                            	</div>
                        	</div>
                        	<br/>
                        	<div>
                            	Embargo Details
                            	<div>
                                	<textarea id="embargo_details" name="embargo_details" placeholder="Enter a details of what the embargo restrictions are"><?php p($_['embargo_details']) ?></textarea>
                            	</div>
                        	</div>
                        	<br/>
                        	<input id="save_embargo" type="button" value="Save">
                        	<input id="cancel_embargo" type="button" value="Cancel">
                    	</div>

                    	<div id="embargo-summary">
                    		<div class='embargo-enabled'>
                        	<h6 class="info">
                                Embargo Enabled
                            </h6>
                            <span id="embargo_enabled" class="standard"><?php if ($_['embargo_enabled']) {p($_['embargo_enabled'] === 'true' ? 'Yes' : 'No');} ?></span>
                        </div>

                        <div class='embargo-until'>
                            <h6 class="info">
                                Embargo Until
                            </h6>
                            <span id="embargo_until" class="standard"><?php p($_['embargo_date']) ?></span>
                        </div>

                        <div class='embargo-info'>
                            <h6 class="info">
                                Embargo Note
                            </h6>
                            <span id="embargo_note" class="standard"><?php echo str_replace("\n", "<br>", $_['embargo_details']) ?></span>
                        </div>
                    </div>
                </div>
            </div> 
				
			<!--  PUBLISHED CRATES -->
			<div class="panel panel-default">
        		<div class="panel-heading">
            		<h4 class="panel-title">
                		<a data-toggle="collapse" data-parent="#meta-data" href="#contact-person" id="contact-person-head">
                    		Published Crates
                        	<i class="pull-right fa fa-caret-down"></i>
                    	</a>
                	</h4>
            	</div>

            	<div id="contact-person" class="panel-collapse collapse standard">
					<H1>Published Crates for User: <?php echo \OC::$server->getUserSession()->getUser()->getDisplayName(); ?> </H1>
					<?php 
      					foreach($_['published_crates'] as $crate) {  ?> 
      					<p><?php echo $crate; ?></p>
					<?php } ?>
            		</div>
        		</div>
        	</div>	
	
	</div>
</div>		
				
