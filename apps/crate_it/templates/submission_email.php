<html>
<head>
    <title><?php p($_['crate_name']) ?></title>
</head>
<body>
<article>
    <div>
        <p>You have submitted your Crate which will enable your dataset to be published and archived on University
            storage.</p>
        The metadata associated with your Crate (data creators, description, grant, etc) has been sent to the Library.
        A metadata record describing your dataset and the conditions under which other researchers can access or use the
        data will be created and sent to Research Data Australia (<a href="https://researchdata.ands.org.au/">researchdata.ands.org.au</a>)
        by the Library.
        A Data Librarian will contact you shortly to complete this process. If you have any further questions please
        email <a href="mailto:researchdata@newcastle.edu.au">researchdata@newcastle.edu.au</a>.<br/>
    </div>
    <div>
        <p>
            Your crate submission report is listed below:
        </p>

        <h4>"<?php p($_['crate_name']) ?>" Submission Report</h4>
        <section resource="creative work" typeof="http://schema.org/CreativeWork">
            <h4>Package Title</h4>
            <span property="http://schema.org/name http://purl.org/dc/elements/1.1/title"><?php p($_['crate_name']) ?></span>

            <h4>Package Creation Date</h4>
            <span content="<?php p($_['created_date']) ?>" property="http://schema.org/dateCreated"><?php p($_['created_date_formatted']) ?></span>

            <h4>Package File Name</h4>
            <span property="http://schema.org/name"><?php p($_['file_name']) ?></span>

            <h4>ID</h4>
            <span property="http://schema.org/id"><?php p($_['crate_name']) ?></span>

            <h4>Description</h4>
            <span property="http://schema.org/description"><?php p(nl2br($_['description'])) ?></span>

            <h4>URL</h4>
            <span property="http://schema.org/url"><?php p(nl2br($_['url'])) ?></span>

            <h4>Location</h4>
            <span property="http://schema.org/location"><?php p(nl2br($_['location'])) ?></span>

            <?php if (array_key_exists('data_retention_period', $_) && $_['data_retention_period'] !== "") { ?>
                <h4>Data Retention Period</h4>
                <span><?php p($_['data_retention_period']) ?> (years)</span>
            <?php } ?>

            <?php if (array_key_exists('access_conditions', $_)) { ?>
                <h4>Access Conditions</h4>
                <span><?php p($_['access_conditions']) ?></span>
            <?php } ?>

            <h4>Creators</h4>
            <?php if (array_key_exists('creators', $_) && !empty($_['creators']))  { ?>
                <table border="1">
                    <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Identifier</th>
                    <th>Source</th>
                    </thead>
                    <tbody>
                    <?php foreach ($_['creators'] as $creator) {
                        if (array_key_exists('overrides', $creator)) {
                            $c = $creator['overrides'];
                        } else {
                            $c = $creator;
                        }
                        print_unescaped('<tr>');
                        print_unescaped('<td>');
                        p($c['name']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($c['email']);
                        print_unescaped('</td>');
                        print_unescaped('<td xmlns:dc="http://purl.org/dc/elements/1.1/">');
                        if (array_key_exists('url', $c)) {
                            print_unescaped();
                            print_unescaped('<a href="' . $c['identifier'] . '"><span property="dc:identifier">' . $c['identifier'] . '</span></a>');
                        } else {
                            print_unescaped('<span property="dc:identifier">' . $c['identifier'] . '</span>');
                        }
                        print_unescaped('</td>');
                        print_unescaped('<td>' . $creator['source'] . '</td>');
                        print_unescaped('</tr>');
                    } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <span>None.</span>
            <?php } ?>

            <h4>Primary Contacts</h4>
            <?php if (array_key_exists('primarycontacts', $_) && !empty($_['primarycontacts']))  { ?>
                <table border="1">
                    <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Identifier</th>
                    <th>Source</th>
                    </thead>
                    <tbody>
                    <?php foreach ($_['primarycontacts'] as $primarycontact) {
                        if (array_key_exists('overrides', $primarycontact)) {
                            $p = $primarycontact['overrides'];
                        } else {
                            $p = $primarycontact;
                        }
                        print_unescaped('<tr>');
                        print_unescaped('<td>');
                        p($p['name']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($p['email']);
                        print_unescaped('</td>');
                        print_unescaped('<td xmlns:dc="http://purl.org/dc/elements/1.1/">');
                        if (array_key_exists('url', $p)) {
                            print_unescaped();
                            print_unescaped('<a href="' . $p['identifier'] . '"><span property="dc:identifier">' . $p['identifier'] . '</span></a>');
                        } else {
                            print_unescaped('<span property="dc:identifier">' . $p['identifier'] . '</span>');
                        }
                        print_unescaped('</td>');
                        print_unescaped('<td>' . $primarycontact['source'] . '</td>');
                        print_unescaped('</tr>');
                    } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <span>None.</span>
            <?php } ?>

            <h4>Grants</h4>
            <?php if (array_key_exists('activities', $_) && !empty($_['activities'])) { ?>
                <table border="1">
                    <thead>
                    <th>Grant Number</th>
                    <th>Grant Title</th>
                    <th>Description</th>
                    <th>Date Granted</th>
                    <th>Date Submitted</th>
                    <th>Institution</th>
                    <th>Identifier</th>
                    <th>Source</th>
                    <th>Subject</th>
                    <th>Format</th>
                    <th>OAI Set</th>
                    <th>Repository Name</th>
                    <th>Repository Type</th>
                    <th>Display Type</th>
                    <th>Contributors</th>
                    </thead>
                    <tbody>
                    <?php foreach ($_['activities'] as $activity) {
                        if (array_key_exists('overrides', $activity)) {
                            $a = $activity['overrides'];
                        } else {
                            $a = $activity;
                        }

                        print_unescaped('<tr>');
                        print_unescaped('<td>');
                        p($a['grant_number']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($a['title']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($a['description']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($a['date']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($a['date_submitted']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($a['institution']);
                        print_unescaped('</td>');

                        $activity_identifier = $a['identifier'];
                        $http = substr($activity_identifier, 0, strlen('http')) === 'http';
                        $https = substr($activity_identifier, 0, strlen('https')) === 'https';

                        if ($http || $https) {
                            print_unescaped('<td>');
                            print_unescaped('<a href="' . $activity_identifier . '">' . $activity_identifier . '</a>');
                            print_unescaped('</td>');
                        } else {
                            print_unescaped('<td>');
                            print_unescaped($a['identifier']);
                            print_unescaped('</td>');
                        }

                        print_unescaped('<td>');
                        p($activity['source']);
                        print_unescaped('</td>');

                        print_unescaped('<td>');
                        p($a['subject']);
                        print_unescaped('</td>');

                        print_unescaped('<td>');
                        p($a['format']);
                        print_unescaped('</td>');

                        print_unescaped('<td>');
                        p($a['oai_set']);
                        print_unescaped('</td>');

                        print_unescaped('<td>');
                        p($a['repository_name']);
                        print_unescaped('</td>');

                        print_unescaped('<td>');
                        p($a['repository_type']);
                        print_unescaped('</td>');

                        print_unescaped('<td>');
                        p($a['display_type']);
                        print_unescaped('</td>');

                        print_unescaped('<td>');
                        p($a['contributors']);
                        print_unescaped('</td>');

                        print_unescaped('</tr>');
                    } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <span>None.</span>
            <?php } ?>

            <h4>Field of Research</h4>
            <?php if ((array_key_exists('for_keywords', $_) && !empty($_['for_keywords'])) || (array_key_exists('fors', $_) && !empty($_['fors']))) { ?>
                <?php if (array_key_exists('for_keywords', $_) && !empty($_['for_keywords']))  { ?>
                    <p>
                        Keywords: <span property="http://schema.org/name"><?php p($_['for_keywords']) ?></span>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('fors', $_) && !empty($_['fors']))  { ?>
                    <table border="1">
                        <thead>
                        <th>Id</th>
                        <th>Title</th>
                        </thead>
                        <tbody>
                        <?php foreach ($_['fors'] as $fieldOfResearch) {
                            print_unescaped('<tr>');
                            print_unescaped('<td>');
                            p($fieldOfResearch['id']);
                            print_unescaped('</td>');
                            print_unescaped('<td>');
                            p($fieldOfResearch['title']);
                            print_unescaped('</td>');
                            print_unescaped('</tr>');
                        } ?>
                        </tbody>
                    </table>
                <?php } ?>
            <?php } else { ?>
                <span>None.</span>
            <?php } ?>

            <h4>Software Information</h4>
            <section property="http://purl.org/dc/terms/creator" typeof="http://schema.org/softwareApplication" resource="">
                <table border="1">
                    <tbody>
                    <tr>
                        <td>Generating Software Application</td>
                        <td property="http://schema.org/name">CRATEIt</td>
                    </tr>
                    <tr>
                        <td>Software Version</td>
                        <td property="http://schema.org/softwareVersion"><?php p($_['version']) ?></td>
                    </tr>
                    <tr>
                        <td>URLs</td>
                    	<td>
                        	<li><a href="https://github.com/digitalbridge/crateit" property="http://schema.org/url">
                                https://github.com/digitalbridge/crateit</a></li>
                    	</td>
                    </tr>
                    </tbody>
                </table>
            </section>
        </section>

        <h4>Files</h4>
        <?php
        if (array_key_exists('files', $_) && !empty($_['files'])) {
            print_unescaped($_['filetree']);
        } else {
            print_unescaped('<span>None.</span>');
        }
        ?>
    </div>
</article>
</body>
</html>