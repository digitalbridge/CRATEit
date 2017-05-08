<div id="mycratesModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="mycratesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h2 class="modal-title" id="mycratesModalLabel">My Crates</h2>
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
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach ($_['crateDetails'] as $crateDetail) {
                ?>
                        <tr>
                            <td><?=$crateDetail['name']; ?></td>
                            <td><?=$crateDetail['size']['human']; ?></td>
                            <!-- <td><?=$crateDetail['contents']['0']; ?></td> -->
                            <td><?=$crateDetail[0]['description']; ?></td>
                            <td>
                                <?=$crateDetail[0]['submitter']['displayname']; ?> (<?=$crateDetail[0]['submitter']['email']; ?>)
                            </td>
                            <td><?=$crateDetail[0]['data_retention_period']; ?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>

                <h3>User 1 Crates</h3>

                <table id="cratelist" class="grid">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Description</th>
                            <th>Publisher</th>
                            <th>Retention Period</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach ($_['crateDetails'] as $crateDetail) {
                ?>
                        <tr>
                            <td><?=$crateDetail['name']; ?></td>
                            <td><?=$crateDetail['size']['human']; ?></td>
                            <!-- <td><?=$crateDetail['contents']['0']; ?></td> -->
                            <td><?=$crateDetail[0]['description']; ?></td>
                            <td>
                                <?=$crateDetail[0]['submitter']['displayname']; ?> (<?=$crateDetail[0]['submitter']['email']; ?>)
                            </td>
                            <td><?=$crateDetail[0]['data_retention_period']; ?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>

                <h3>User 2 Crates</h3>

                <table id="cratelist" class="grid">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Description</th>
                            <th>Publisher</th>
                            <th>Retention Period</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach ($_['crateDetails'] as $crateDetail) {
                ?>
                        <tr>
                            <td><?=$crateDetail['name']; ?></td>
                            <td><?=$crateDetail['size']['human']; ?></td>
                            <!-- <td><?=$crateDetail['contents']['0']; ?></td> -->
                            <td><?=$crateDetail[0]['description']; ?></td>
                            <td>
                                <?=$crateDetail[0]['submitter']['displayname']; ?> (<?=$crateDetail[0]['submitter']['email']; ?>)
                            </td>
                            <td><?=$crateDetail[0]['data_retention_period']; ?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>

                <label style="color: red; display: none;">Error: No CRATES available</label>
            </div>
        </div>
    </div>
</div>