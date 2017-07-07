<div id="mycratesModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="mycratesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h2 class="modal-title" id="mycratesModalLabel">Published Crates</h2>
                <p class="crate-message">Message Placeholder</p>
            </div>

            <div class="modal-body">
                <h3>My Crates</h3>

                <table id="cratelist" class="grid">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Description</th>
                            <th>Publisher</th>
                            <th>Retention Period</th>
                            <th>Submission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach ($_['crateDetails'] as $crateDetail) {
                ?>
                        <tr data-file="<?=basename($crateDetail['manifest']['publish_details']['location']); ?>">
                            <td><?=$crateDetail['name']; ?></td>
                            <td><?=$crateDetail['size']['human']; ?></td>
                            <!-- <td><?=$crateDetail['contents']['0']; ?></td> -->
                            <td><?=$crateDetail['manifest']['description']; ?></td>
                            <td>
                                <?=$crateDetail['manifest']['submitter']['displayname']; ?> (<?=$crateDetail['manifest']['submitter']['email']; ?>)
                            </td>
                            <td><?=$crateDetail['manifest']['data_retention_period']; ?></td>
                            <td><?=$crateDetail['manifest']['publish_details']['submitted_date']; ?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>

                <?php
                    if ($_['isSubAdmin']) {
                        foreach ($_['otherCrateDetails'] as $index => $otherCrateDetail) {
                ?>
                <h3><?=$index; ?></h3>

                <table id="cratelist" class="grid">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Description</th>
                            <th>Publisher</th>
                            <th>Retention Period</th>
                            <th>Submission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach ($otherCrateDetail as $crateDetail) {
                ?>
                        <tr data-file="<?=basename($crateDetail['manifest']['publish_details']['location']); ?>">
                            <td><?=$crateDetail['name']; ?></td>
                            <td><?=$crateDetail['size']['human']; ?></td>
                            <!-- <td><?=$crateDetail['contents']['0']; ?></td> -->
                            <td><?=$crateDetail['manifest']['description']; ?></td>
                            <td>
                                <?=$crateDetail['manifest']['submitter']['displayname']; ?> (<?=$crateDetail['manifest']['submitter']['email']; ?>)
                            </td>
                            <td><?=$crateDetail['manifest']['data_retention_period']; ?></td>
                            <td><?=$crateDetail['manifest']['publish_details']['submitted_date']; ?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>
                <?php
                        }
                    }
                ?>

                <label style="color: red; display: none;">Error: No CRATES available</label>
            </div>
        </div>
    </div>
</div>