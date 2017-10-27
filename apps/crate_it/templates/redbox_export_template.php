<?xml version="1.0" encoding="US-ASCII"?>
<my:RedboxCollection xmlns:my="http://schemas.microsoft.com/office/infopath/2003/myXSD/2016-09-26T07:17:47"
                     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                     xmlns:xd="http://schemas.microsoft.com/office/infopath/2003">

    <my:Title><?php p($_['crate_name']) ?></my:Title>
    <my:ID><?php p($_['crate_name']) ?></my:ID>
    <my:DateSubmitted><?php p($_['submitted_date']) ?></my:DateSubmitted>
    <my:TimeSubmitted><?php p($_['submitted_time']) ?></my:TimeSubmitted>
    <my:Description><?php p($_['description']) ?></my:Description>
    <my:DataRetentionPeriod><?php p($_['data_retention_period']) ?></my:DataRetentionPeriod>
    <my:Location><?php print_unescaped($_['location']) ?></my:Location>
    <my:URL><?php print_unescaped($_['url']) ?></my:URL>
    <my:Type>dataset</my:Type>
    <my:WorkflowSource>Owncloud-CRATEit</my:WorkflowSource>
    <my:WorkflowSourceVersion><?php p($_['version']) ?></my:WorkflowSourceVersion>
    <?php if (array_key_exists('creators', $_)) { ?>
        <my:Creators>
            <?php foreach ($_['creators'] as $creator) { ?>
                <?php $isOverride = isset($creator["overrides"]) ?>
                <my:Creator>
                    <my:CreatorName>
                        <?php if ($isOverride) {
                            print_unescaped($creator['overrides']['name']);
                        } else {
                            print_unescaped($creator['name']);
                        }
                        ?>
                    </my:CreatorName>
                    <my:CreatorEmail>
                        <?php if ($isOverride) {
                            print_unescaped($creator['overrides']['email']);
                        } else {
                            print_unescaped($creator['email']);
                        }
                        ?>
                    </my:CreatorEmail>
                </my:Creator>
            <?php } ?>
        </my:Creators>
    <?php } ?>
    <?php if (array_key_exists('primarycontacts', $_)) { ?>
        <my:PrimaryContacts>
            <?php foreach ($_['primarycontacts'] as $primarycontact) { ?>
                <?php $isOverride = isset($primarycontact["overrides"]) ?>
                <my:PrimaryContact>
                    <my:PrimaryContactName>
                        <?php if ($isOverride) {
                            print_unescaped($primarycontact['overrides']['name']);
                        } else {
                            print_unescaped($primarycontact['name']);
                        }
                        ?>
                    </my:PrimaryContactName>
                    <my:PrimaryContactEmail>
                        <?php if ($isOverride) {
                            print_unescaped($primarycontact['overrides']['email']);
                        } else {
                            print_unescaped($primarycontact['email']);
                        }
                        ?>
                    </my:PrimaryContactEmail>
                </my:PrimaryContact>
            <?php } ?>
        </my:PrimaryContacts>
    <?php } ?>
    <?php if (array_key_exists('activities', $_)) { ?>
        <my:GrantNumbers>
            <?php foreach ($_['activities'] as $activity) { ?>
                <?php $isOverride = isset($activity["overrides"]) ?>
                <my:GrantNumber>
                    <my:GrantNumberID>
                        <?php if ($isOverride) {
                            print_unescaped($activity['overrides']['grant_number']);
                        } else {
                            print_unescaped($activity['grant_number']);
                        }
                        ?>
                    </my:GrantNumberID>
                    <my:GrantNumberDescription>
                        <?php if ($isOverride) {
                            print_unescaped($activity['overrides']['title']);
                        } else {
                            print_unescaped($activity['title']);
                        }
                        ?>
                    </my:GrantNumberDescription>
                </my:GrantNumber>
            <?php } ?>
        </my:GrantNumbers>
    <?php } ?>
    <?php if (array_key_exists('fors', $_)) { ?>
        <my:FieldOfResearch>
            <?php foreach ($_['fors'] as $fieldOfResearch) { ?>
                <?php $isOverride = isset($fieldOfResearch["overrides"]) ?>
                <my:Research>
                    <my:ResearchID>
                        <?php print_unescaped($fieldOfResearch['id']) ?>
                    </my:ResearchID>
                    <my:ResearchTitle>
                        <?php if ($isOverride) {
                            print_unescaped($fieldOfResearch['overrides']['title']);
                        } else {
                            print_unescaped($fieldOfResearch['title']);
                        }
                        ?>
                    </my:ResearchTitle>
                </my:Research>
            <?php } ?>
        </my:FieldOfResearch>
    <?php } ?>
    <?php if(array_key_exists('for_keywords',$_)) { ?>
        <my:FieldOfResearchKeywords>
            <?php p(implode(', ', $_['for_keywords'])) ?>
        </my:FieldOfResearchKeywords>
    <?php } ?>
    <my:Submitter>
        <my:SubmitterDisplayname><?php p($_['submitter']['displayname']) ?></my:SubmitterDisplayname>
        <my:SubmitterEmail><?php p($_['submitter']['email']) ?></my:SubmitterEmail>
    </my:Submitter>
    <my:Contents>
        <?php foreach ($_['files'] as $file) { ?>
            <my:File>
                <my:FileName>
                    <?php print_unescaped($file['name']) ?>
                </my:FileName>
                <my:FileSize>
                    <?php print_unescaped($file['size']) ?>
                </my:FileSize>
            </my:File>
        <?php } ?>
    </my:Contents>
    <my:AccessConditions><?php if(array_key_exists('access_conditions',$_)) {p($_['access_conditions']);} ?></my:AccessConditions>
</my:RedboxCollection>
