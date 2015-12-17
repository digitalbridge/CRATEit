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
        <span
            property="http://schema.org/name http://purl.org/dc/elements/1.1/title"><?php p($_['crate_name']) ?></span>

            <h4>Package Creation Date</h4>
        <span content="<?php p($_['created_date']) ?>"
              property="http://schema.org/dateCreated"><?php p($_['created_date_formatted']) ?></span>

            <h4>Package File Name</h4>
            <span property="http://schema.org/name"><?php p($_['crate_name'] . '.zip') ?></span>

            <h4>ID</h4>
            <span property="http://schema.org/id"><?php p($_['crate_name']) ?></span>

            <h4>Description</h4>
            <span property="http://schema.org/description"><?php p(nl2br($_['description'])) ?></span>

            <?php if (array_key_exists('data_retention_period', $_) && $_['data_retention_period'] != "") { ?>
                <h4>Data Retention Period</h4>
                <span><?php p($_['data_retention_period']) ?> (years)</span>
            <?php } ?>

            <?php if (array_key_exists('embargo_enabled', $_)) { ?>
                <h4>Embargo Details</h4>
                <h5>Embargo Enabled</h5>
                <span><?php if ($_['embargo_enabled']) {p($_['embargo_enabled'] === 'true' ? 'Yes' : 'No');} ?></span>

                <?php if ($_['embargo_enabled'] === 'true') { ?>
                    <h5>Embargo Until</h5>
                    <span><?php p($_['embargo_date']) ?></span>

                    <h5>Embargo Note</h5>
                    <span><?php echo str_replace("\n", "<br>", $_['embargo_details']) ?></span>
                <?php } ?>
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
            <h4>Software Information</h4>
            <section property="http://purl.org/dc/terms/creator" typeof="http://schema.org/softwareApplication"
                     resource="">
                <table border="1">
                    <tbody>
                    <tr>
                        <td>Generating Software Application</td>
                        <td property="http://schema.org/name">Cr8it</td>
                    </tr>
                    <tr>
                        <td>Software Version</td>
                        <td property="http://schema.org/softwareVersion"><?php p($_['version']) ?></td>
                    </tr>
                    <tr>
                        <td>URLs</td>
                        <td>
                            <li><a href="https://github.com/IntersectAustralia/owncloud"
                                   property="http://schema.org/url">
                                    https://github.com/IntersectAustralia/owncloud</a></li>
                            <li><a href="https://github.com/uws-eresearch/apps" property="http://schema.org/url">
                                    https://github.com/uws-eresearch/apps</a></li>
                            <li><a href="http://eresearch.uws.edu.au/blog/projects/projectsresearch-data-repository/"
                                   property="http://schema.org/url">
                                    http://eresearch.uws.edu.au/blog/projects/projectsresearch-data-repository</a></li>
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