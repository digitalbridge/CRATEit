<html>
<head>
    <title><?php p($_['crate_name']) ?></title>
</head>
<body>
<article>
    <h1>"<?php p($_['crate_name']) ?>" Data Package README file</h1>
    <section resource="creative work" typeof="http://schema.org/CreativeWork">
        <h1>Package Title</h1>
        <span property="http://schema.org/name http://purl.org/dc/elements/1.1/title"><?php p($_['crate_name']) ?></span>

        <h1>Package Creation Date</h1>
        <span content="<?php p($_['created_date']) ?>" property="http://schema.org/dateCreated"><?php p($_['created_date_formatted']) ?></span>

        <h1>Package File Name</h1>
        <span property="http://schema.org/name"><?php p($_['crate_name'].'.zip') ?></span>

        <h1>ID</h1>
        <span property="http://schema.org/id"><?php p($_['crate_name']) ?></span>

        <h1>Description</h1>
        <span property="http://schema.org/description"><?php p(nl2br($_['description'])) ?></span>

     	<h1>URL</h1>
        <span property="http://schema.org/url"><?php p(nl2br($_['url'])) ?></span>

        <h1>Location</h1>
        <span property="http://schema.org/location"><?php p(nl2br($_['location'])) ?></span>

        <?php if (array_key_exists('data_retention_period', $_) && $_['data_retention_period'] !== "") { ?>
            <h1>Data Retention Period</h1>
            <span><?php p($_['data_retention_period']) ?> (years)</span>
        <?php } ?>

        <?php if (array_key_exists('access_conditions', $_)) { ?>
            <h1>Access Conditions</h1>
            <span><?php p($_['access_conditions']) ?></span>
        <?php } ?>

        <?php if (array_key_exists('access_permissions_statement', $_)) { ?>
            <h1>Access Permissions Statement</h1>
            <span><?php p($_['access_permissions_statement']) ?></span>
        <?php } ?>

        <h1>Creators</h1>
        <?php if (array_key_exists('creators',$_) && !empty($_['creators'])) { ?>
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
                    if (array_key_exists('url',$c)) {
                        print_unescaped();
                        print_unescaped('<a href="'.$c['identifier'].'"><span property="dc:identifier">'.$c['identifier'].'</span></a>');
                    } else {
                        print_unescaped('<span property="dc:identifier">'.$c['identifier'].'</span>');
                    }
                    print_unescaped('</td>');
                    print_unescaped('<td>'.$creator['source'].'</td>');
                    print_unescaped('</tr>');
                } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <span>None.</span>
        <?php } ?>

        <h1>Primary Contacts</h1>
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

        <h1>Grants</h1>
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

        <h1>Field of Research</h1>
        <?php if ((array_key_exists('for_keywords', $_) && !empty($_['for_keywords'])) || (array_key_exists('fors', $_) && !empty($_['fors']))) { ?>
            <?php if (array_key_exists('fors', $_) && !empty($_['fors']))  { ?>
                <table border="1">
                    <thead>
                    <th>Id</th>
                    <th>Title</th>
                    </thead>
                    <tbody>
                    <?php foreach ($_['fors'] as $fieldOfResearch) {
                        if (array_key_exists('overrides', $fieldOfResearch)) {
                            $f = $fieldOfResearch['overrides'];
                        }
                        print_unescaped('<tr>');
                        print_unescaped('<td>');
                        p($fieldOfResearch['id']);
                        print_unescaped('</td>');
                        print_unescaped('<td>');
                        p($f['title']);
                        print_unescaped('</td>');
                        print_unescaped('</tr>');
                    } ?>
                    </tbody>
                </table>
            <?php } ?>
            <?php if (array_key_exists('for_keywords', $_) && !empty($_['for_keywords']))  { ?>
                <p>
                    Keywords: <span property="http://schema.org/name"><?php p(implode(', ', $_['for_keywords'])); ?></span>
                </p>
            <?php } ?>
        <?php } else { ?>
            <span>None.</span>
        <?php } ?>

        <h1>Software Information</h1>
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

    <h1>Files</h1>
    <?php
    if (array_key_exists('files',$_) && !empty($_['files'])) {
        print_unescaped($_['filetree']);
    } else {
        print_unescaped('<span>None.</span>');
    }
    ?>
</article>
</body>
</html>
