<div class="container-metadata">
    <div id="meta-data" class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a href="#crate-information" id="crate-information-head" data-toggle="collapse" data-parent="#meta-data">
                        Crate Information
                        <i class="pull-right fa fa-caret-up"></i>
                    </a>
                </h4>
            </div>

            <div id="crate-information" class="panel-collapse collapse in standard">
                <div class="panel-body">
                    <div id="description_box">
                        <h6>
                            Description
                            <button type="button" id="edit_description" class="pull-right trans-button" placeholder="Edit"><i class="fa fa-edit"></i></button>
                        </h6>
                        <div id="description" class="metadata" style="white-space: pre-wrap;"><?php p($_[trim('description')]) ?></div>
                    </div>

                    <div class="crate-size">
                        <h6 class="info">
                            Crate Size: <span id="crate_size_human" class="standard"></span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a href="#data-creators" id="data-creators-head" data-toggle="collapse" data-parent="#meta-data">
                        Data Creators
                        <i class="pull-right fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>

            <div id="data-creators" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="creators_box" class="data-creators">
                        <h6>
                            Selected Data Creators (<span id="creators_count"></span>)
                            <button type="button" id="clear_creators" class="pull-right trans-button"><i class="fa fa-times muted"></i></button>
                        </h6>
                        <ul id="selected_creators" class="metadata"></ul>

                        <h6>
                            Add New Data Creators
                            <button type="button" id="add-creator" class="pull-right trans-button" data-toggle="modal" data-target="#addCreatorModal"><i class="fa fa-plus muted"></i></button>
                        </h6>
                        <div id="search_people_box" class="input-group">
                            <label for="keyword_creator" class="element-invisible">Search Creators</label>
                            <input type="text" name="keyword" id="keyword_creator" class="form-control" placeholder="Search Creators..." />
                            <span class="input-group-btn">
                                <button type="button" id="search_people" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </span>
                        </div>

                        <span id="creators_search_notification"></span>
                        <div id="search_people_result_box">
                            <ul id="search_people_results"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a href="#data-primarycontacts" id="data-primarycontacts-head" data-toggle="collapse" data-parent="#meta-data">
                        Primary Contacts
                        <i class="pull-right fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>

            <div id="data-primarycontacts" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="primarycontacts_box" class="data-primarycontacts">
                        <h6>
                            Selected Primary Contacts (<span id="primarycontacts_count"></span>)
                            <button type="button" id="clear_primarycontacts" class="pull-right trans-button"><i class="fa fa-times muted"></i></button>
                        </h6>
                        <ul id="selected_primarycontacts" class="metadata"></ul>

                        <h6>
                            Add New Primary Contact
                            <button type="button" id="add-primarycontact" class="pull-right trans-button" data-toggle="modal" data-target="#addPrimaryContactModal"><i class="fa fa-plus muted"></i></button>
                        </h6>
                        <div id="search_primarycontacts_box" class="input-group">
                            <label for="keyword_primarycontact" class="element-invisible">Search Primary Contacts</label>
                            <input type="text" name="keyword" id="keyword_primarycontact" class="form-control" placeholder="Search Primary Contacts..." />
                            <span class="input-group-btn">
                                <button type="button" id="search_primarycontacts" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </span>
                        </div>

                        <span id="primarycontacts_search_notification"></span>
                        <div id="search_primarycontacts_result_box">
                            <ul id="search_primarycontacts_results"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a href="#data-fors" id="data-fors-head" data-toggle="collapse" data-parent="#meta-data">
                        Fields of Research
                        <i class="pull-right fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>

            <div id="data-fors" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="for_box" class="data-fors">
                        <h6>
                            Selected FORs (<span id="fors_count"></span>)
                            <button type="button" id="clear_fors" class="pull-right trans-button"><i class="fa fa-times muted"></i></button>
                        </h6>

                        <ul id="selected_fors" class="metadata"></ul>

                        <h6>
                            Add New FORs
                            <button type="button" id="add-for" class="pull-right trans-button" data-toggle="modal" data-target="#addForModal"><i class="fa fa-plus muted"></i></button>
                        </h6>
                        <div id="search_for_box" class="input-group">
                            <label for="keyword_for" class="element-invisible">Search FORs</label>
                            <input type="text" name="keyword_for" id="keyword_for" class="form-control" placeholder="Search FORs..."/>
                            <span class="input-group-btn">
                                <button type="button" id="search_for" value="Search FOR" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                        <span id="for_search_notification"></span>

                        <div id="search_for_result_box">
                            <ul id="search_for_results"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a href="#grant-numbers" id="grant-numbers-head" data-toggle="collapse" data-parent="#meta-data">
                        Grants
                        <i class="pull-right fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>

            <div id="grant-numbers" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="activities_box" class="grant-numbers">
                        <h6>
                            Selected Grants (<span id="activities_count"></span>)
                            <button type="button" id="clear_grant_numbers" class="pull-right trans-button"><i class="fa fa-times muted"></i></button>
                        </h6>
                        <ul id="selected_activities" class="metadata">
                            <!-- TODO: Be more consistent with naming, are they "grants" or "activities"? -->
                        </ul>
                        <h6>
                            Add New Grants
                            <button type="button" id="add-activity" class="pull-right trans-button" data-toggle="modal" data-target="#addGrantModal"><i class="fa fa-plus muted"></i></button>
                        </h6>
                        <div id="search_activity_box" class="input-group">
                            <label for="keyword_activity" class="element-invisible">Search Grants</label>
                            <input type="text" name="keyword_activity" id="keyword_activity" class="form-control" placeholder="Search Grants..." />
                            <span class="input-group-btn">
                                <button type="button" id="search_activity" value="Search Grant Number" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                        <span id="activities_search_notification"></span>

                        <div id="search_activity_result_box">
                            <ul id="search_activity_results"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a href="#data-retention-period" id="data-retention-period-head" data-toggle="collapse" data-parent="#meta-data">
                        Data Retention Period
                        <i class="pull-right fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>

            <div id="data-retention-period" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <div id="retention_period_list">
                        <h6>
                            DATA RETENTION PERIOD (YEARS)
                            <button type="button" id="choose_retention_period" class="pull-right trans-button" placeholder="Choose"><i class="fa fa-edit"></i></button>
                        </h6>
                        <div id="retention_period_value" class="metadata" style="white-space: pre-wrap;"><?php
                             if (($_['data_retention_period'] !== null) && ($_['data_retention_period'] !== '')) {
                                echo $_['data_retention_period'];
                             } else {
                                echo 'Please Select';
                             }
                        ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a href="#embargo-details" id="embargo-details-head" data-toggle="collapse" data-parent="#meta-data">
                        Embargo Details
                        <i class="pull-right fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>

            <div id="embargo-details" class="panel-collapse collapse standard">
                <div class="panel-body">
                    <h6>
                        EMBARGO DETAILS
                        <button type="button" id="choose_embargo_details" class="pull-right trans-button" placeholder="Choose"><i class="fa fa-edit"></i></button>
                    </h6>

                    <div id="edit_embargo_details" class="metadata hidden">
                        <div>
                            Embargo Enabled
                            <div>
                                <input type="radio" name="embargo_enabled" id="embargo_enabled_yes" value="yes" <?php p($_['embargo_enabled'] === 'true' ? 'checked' : '') ?>>
                                <label for="embargo_enabled_yes">Yes</label>
                                <input type="radio" name="embargo_enabled" id="embargo_enabled_no" value="no" <?php p($_['embargo_enabled'] === 'false' ? 'checked' : '') ?>>
                                <label for="embargo_enabled_no">No</label>
                            </div>
                        </div>

                        <br />

                        <div id="embargo_datetime_picker_button" class="input-append date">
                            Embargo Date
                            <div>
                                <input type="text" name="embargo_date" id="embargo_date" value="<?php p($_['embargo_date']) ?>" data-format="yyyy-MM-dd" disabled="disabled">
                                <span class="add-on">
                                    <i id="embargo_datetimepicker_icon" class="fa fa-calendar embargo_datetime_icon"></i>
                                </span>
                            </div>
                        </div>

                        <br />

                        <div>
                            Embargo Details
                            <div>
                                <textarea name="embargo_details" id="embargo_details" placeholder="Enter details of what the embargo restrictions are"><?php p($_['embargo_details']) ?></textarea>
                            </div>
                        </div>

                        <div>
                            Embargo Access Conditions
                            <div>
                                <input type="radio" name="embargo_access_conditions" id="embargo_closed" value="closed" <?php p($_['embargo_access_conditions'] === 'closed' ? 'checked' : '') ?>>
                                <label for="embargo_closed">Closed (No Public Access)</label>
                                <input type="radio" name="embargo_access_conditions" id="embargo_open" value="open" <?php p($_['embargo_access_conditions'] === 'open' ? 'checked' : '') ?>>
                                <label for="embargo_open">Open (Public Access)</label>
                                <input type="radio" name="embargo_access_conditions" id="embargo_shared" value="shared" <?php p($_['embargo_access_conditions'] === 'shared' ? 'checked' : '') ?>>
                                <label for="embargo_shared">Shared</label>
                            </div>
                        </div>

                        <br />

                        <input type="button" id="save_embargo" value="Save">
                        <input type="button" id="cancel_embargo" value="Cancel">
                    </div>

                    <div id="embargo-summary">
                        <div class="embargo-enabled">
                            <h6 class="info">Embargo Enabled</h6>
                            <span id="embargo_enabled" class="standard"><?php if ($_['embargo_enabled']) { p($_['embargo_enabled'] === 'true' ? 'Yes' : 'No'); } ?></span>
                        </div>

                        <div class="embargo-until">
                            <h6 class="info">Embargo Until</h6>
                            <span id="embargo_until" class="standard"><?php p($_['embargo_date']) ?></span>
                        </div>

                        <div class="embargo-info">
                            <h6 class="info">Embargo Note</h6>
                            <span id="embargo_note" class="standard"><?php echo str_replace('\n', '<br />', $_['embargo_details']) ?></span>
                        </div>

                        <div class="embargo-access-conditions">
                            <h6 class="info">Embargo Access Conditions</h6>
                            <span id="embargo_access_conditions" class="standard"><?php p($_['embargo_access_conditions']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>