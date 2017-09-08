<!-- workaround to make var avalaide to javascript -->
<div id="hidden_vars" hidden="hidden">
    <span id="name_length"><?php p($_['name_length']) ?></span>
    <span id="description_length"><?php p($_['description_length']) ?></span>
    <span id="max_sword_mb"><?php p($_['max_sword_mb']) ?></span>
    <span id="publish_warning_mb"><?php p($_['publish_warning_mb']) ?></span>
    <span id="max_zip_mb"><?php p($_['max_zip_mb']) ?></span>
    <span id="validate_crate_name"><?php p($_['validate_crate_name']) ?></span>
    <span id="validate_crate_description"><?php p($_['validate_crate_description']) ?></span>
    <span id="validate_data_creators_name"><?php p($_['validate_data_creators_name']) ?></span>
    <span id="validate_data_creators_email"><?php p($_['validate_data_creators_email']) ?></span>
    <span id="validate_data_creators_url"><?php p($_['validate_data_creators_url']) ?></span>
    <span id="validate_grants_number"><?php p($_['validate_grants_number']) ?></span>
    <span id="validate_grants_year"><?php p($_['validate_grants_year']) ?></span>
    <span id="validate_grants_institution"><?php p($_['validate_grants_institution']) ?></span>
    <span id="validate_grants_title"><?php p($_['validate_grants_title']) ?></span>
    <span id="validate_for_title"><?php p($_['validate_for_title']) ?></span>
    <span id="validate_data_retention_period"><?php p($_['validate_data_retention_period']) ?></span>
    <span id="validate_embargo_enabled"><?php p($_['validate_embargo_enabled']) ?></span>
    <span id="validate_embargo_date"><?php p($_['validate_embargo_date']) ?></span>
    <span id="validate_embargo_details"><?php p($_['validate_embargo_details']) ?></span>
    <span id="selected_crate"><?php p($_['selected_crate']) ?></span>
    <span id="sword_enabled"><?php
        $swordEnabled = false;
        $publish_endpoints = ($_['publish endpoints']);
        $sword_endpoints = $publish_endpoints['sword'];
        foreach ($sword_endpoints as $sword_endpoint) {
            if ($sword_endpoint['enabled'] === true) {
                $swordEnabled = true;
            }
        }
        p($swordEnabled ? 'true' : 'false');?>
    </span>
 </div>
