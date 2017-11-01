<?php

namespace OCA\crate_it\Controller;

use \OCP\AppFramework\Controller;
use \OCP\AppFramework\Http\JSONResponse;
use \OCP\AppFramework\Http;
use OCA\crate_it\lib\ErrorResponse;
use OCA\crate_it\lib\ZipDownloadResponse;
use OCA\crate_it\lib\XSendFileDownloadResponse;
use OCA\crate_it\lib\Util;

class cratecontroller extends Controller
{
    private $crateManager;

    private $userManager;

    public function __construct($appName, $request, $crateManager, $userManager)
    {
        parent::__construct($appName, $request);
        $this->crateManager = $crateManager;
        $this->userManager = $userManager;
    }

    /**
     * Create crate with name and description
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function createCrate()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::create()", \OCP\Util::DEBUG);
        $name = $this->params('name');
        $description = $this->params('description');
        $data_retention_period = 'Please Select';
        try {
            // TODO: maybe this selection stuff should be in a switchcrate method
            $msg = $this->crateManager->createCrate($name, $description, $data_retention_period);
            $_SESSION['selected_crate'] = $name;
            session_commit();
            return new JSONResponse(array('crateName' => $msg, 'crateDescription' => $description, 'crateDataRetentionPeriod' => $data_retention_period));
        } catch (\Exception $e) { // TODO: This is currently unreachable
            return new JSONResponse(array('msg' => $e->getMessage()), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get crate items
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function getManifest() // NOTE: this now return the entire manifest, should we change the name of the method?
    {
        \OCP\Util::writeLog('crate_it', "CrateController::get_manifest()", \OCP\Util::DEBUG);
        try {
            $crateName = $this->params('crate_id');
            session_start();
            $_SESSION['selected_crate'] = urldecode($crateName);
            session_commit();
            //\OCP\Util::writeLog('Get Manifest SESSION', urldecode($crateName) . " Id: " . session_id(), \OCP\Util::WARN);
            $data = $this->crateManager->getManifest($crateName);
            return new JSONResponse($data);
        } catch (\Exception $e) {
            return new JSONResponse(array('msg' => $e->getMessage()), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Add To Crate
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function add()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::add()", \OCP\Util::DEBUG);
        try {
            // TODO check if this error handling works
            $file = $this->params('file');
            \OCP\Util::writeLog('crate_it', "Adding ".$file, \OCP\Util::DEBUG);
            if ($file === '_html' && \OC\Files\Filesystem::is_dir($file)) {
                throw new \Exception("$file ignored by Crate it");
            }
            $crateName = $_SESSION['selected_crate'];
            session_commit();
            // TODO: naming consistency, add vs addToBag vs addToCrate
            $this->crateManager->addToCrate($crateName, $file);
            return new JSONResponse(array('msg' => "$file added to crate $crateName"));
        } catch (\Exception $e) {
            return new JSONResponse(array('msg' => $e->getMessage()), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Crate Name
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function getCrateName()
    {
        \OCP\Util::writeLog('Get Crate Name', \OC::$server->getUserSession()->getSession()->get('crateName'), \OCP\Util::DEBUG);

        $status = Http::STATUS_OK;
        if (array_key_exists('selected_crate', $_SESSION)) {
            $content = $_SESSION['selected_crate'];
        } else {
            $content = array('msg' => 'No selected crate.');
            $status = Http::STATUS_INTERNAL_SERVER_ERROR;
        }
        return new JSONResponse($content, $status);
    }

    /**
     * Get Crate Size
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function getCrateSize()
    {
        \OCP\Util::writeLog('Get Crate Size', \OC::$server->getUserSession()->getSession()->get('crateName'), \OCP\Util::DEBUG);

        \OCP\Util::writeLog('crate_it', "CrateController::getCrateSize()", \OCP\Util::DEBUG);
        try {
            $selectedCrate = $_SESSION['selected_crate'];
            $data = $this->crateManager->getCrateSize($selectedCrate);
            return new JSONResponse($data);
        } catch (\Exception $e) {
            return new JSONResponse(array('msg' => $e->getMessage()), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Crate
     * TODO change to not just return description but all fields?
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function updateCrate()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::updateCrate()", \OCP\Util::DEBUG);
        $fieldsets = $this->params('fields');
        $savedFields = array();

        if (is_array($fieldsets)) {
            foreach ($fieldsets as $fieldset) {
                $field = $fieldset['field'];
                $value = $fieldset['value'];

                // TODO: This is an ugly workaround to avoid the max_input_vars ceiling
                // the vfs field is a json string inside a json object
                if ($field === 'vfs') {
                    $value = json_decode($value, true);
                }
                try {
                    $this->crateManager->updateCrate($_SESSION['selected_crate'], $field, $value);
                    $savedFields[$field] = $value;
                } catch (\Exception $e) {
                    return new JSONResponse(array('msg' => $e->getMessage()), Http::STATUS_INTERNAL_SERVER_ERROR);
                }
            }
        }
        return new JSONResponse(array('msg' => "crate successfully updated", 'values' => $savedFields));
    }

    /**
     * Delete Crate
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function deleteCrate()
    {
        // TODO: all of these methods always return successfully, which shouldn't happen
        //       unfortunately this means rewriting methods in the bagit library
        \OCP\Util::writeLog('crate_it', "CrateController::deleteCrate()", \OCP\Util::DEBUG);
        $selected_crate = $_SESSION['selected_crate'];
        try {
            $this->crateManager->deleteCrate($selected_crate);
            return new JSONResponse(array('msg' => "Crate $selected_crate has been deleted"));
        } catch (\Exception $e) {
            return new JSONResponse(array('msg' => $e->getMessage()), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Rename Crate
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function renameCrate()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::renameCrate()", \OCP\Util::DEBUG);
        $oldCrateName = $_SESSION['selected_crate'];
        $newCrateName = $this->params('newCrateName');
        try {
            $this->crateManager->renameCrate($oldCrateName, $newCrateName);
            session_start();
            $_SESSION['selected_crate'] = $newCrateName;
            session_commit();
            return new JSONResponse(array('msg' => "Renamed $oldCrateName to $newCrateName"));
        } catch (\Exception $e) {
            return new JSONResponse(array('msg' => $e->getMessage()), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Package Crate as a Zip
     *
     * @NoAdminRequired
     */
    public function packageCrate()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::packageCrate()", \OCP\Util::DEBUG);
        try {
            $packagePath = $this->crateManager->packageCrate($_SESSION['selected_crate']);
            $filename = basename($packagePath);
            $response = new XSendFileDownloadResponse($packagePath, $filename);
        } catch (\Exception $e) {
            $message = 'Internal Server Error: '.$e->getMessage();
            \OCP\Util::writeLog('crate_it', $message, \OCP\Util::ERROR);
            $response = new ErrorResponse($message);
        }
        return $response;
    }

    /**
     * Export Crates as CSV
     *
     * @NoAdminRequired
     */
    public function exportCrate()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::exportCrate()", \OCP\Util::DEBUG);

        // output headers so that the file is downloaded rather than displayed
        $filename = 'usage_report_' . date('d_m_Y', time()) . '.csv';
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $fp = fopen('php://output', 'w');
        fputcsv($fp, ['User Name', 'Crate Name', 'Size', 'Description', 'Publisher', 'Retention Period', 'Access Conditions', 'Access Permissions Statement', 'Submission Date']);

        $userObject = \OC::$server->getUserSession()->getUser();
        $crateDetails = $this->crateManager->getCrateDetailsList();
        foreach ($crateDetails as $crateDetail) {
            fputcsv($fp, [
                $userObject->getDisplayName(),
                $crateDetail['name'],
                $crateDetail['size']['human'],
                $crateDetail['manifest']['description'],
                $crateDetail['manifest']['submitter']['displayname'] . '(' . $crateDetail['manifest']['submitter']['email'] . ')',
                $crateDetail['manifest']['data_retention_period'],
                $crateDetail['manifest']['access_conditions'],
                $crateDetail['manifest']['access_permissions_statement'],
                $crateDetail['manifest']['publish_details']['submitted_date']
            ]);
        }

        $isSubAdmin = false;
        if ($userObject !== null) {
            $isSubAdmin = \OC::$server->getGroupManager()->getSubAdmin()->isSubAdmin($userObject);
        }

        if ($isSubAdmin) {
            $otherCrateDetails = [];
            $users = $this->userManager->search('');
            foreach ($users as $user) {
                if ($user->getDisplayName() !== $userObject->getDisplayName()) {
                    $otherCrateDetails[$user->getDisplayName()] = $this->crateManager->getOtherCrateDetailsList($user);
                }
            }
            foreach ($otherCrateDetails as $index => $otherCrateDetail) {
                foreach ($otherCrateDetail as $crateDetail) {
                    fputcsv($fp, [
                        $index,
                        $crateDetail['name'],
                        $crateDetail['size']['human'],
                        $crateDetail['manifest']['description'],
                        $crateDetail['manifest']['submitter']['displayname'] . '(' . $crateDetail['manifest']['submitter']['email'] . ')',
                        $crateDetail['manifest']['data_retention_period'],
                        $crateDetail['manifest']['access_conditions'],
                        $crateDetail['manifest']['access_permissions_statement'],
                        $crateDetail['manifest']['publish_details']['submitted_date']
                    ]);
                }
            }
        }

        fclose($fp);
    }

    /**
     * Create ePub
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function generateEPUB()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::generateEPUB()", \OCP\Util::DEBUG);
        try {
            $epubPath = $this->crateManager->generateEPUB($_SESSION['selected_crate']);
            $filename = basename($epubPath);
            $response = new ZipDownloadResponse($epubPath, $filename);
        } catch (\Exception $e) {
            $message = 'Internal Server Error: '.$e->getMessage();
            \OCP\Util::writeLog('crate_it', $message, \OCP\Util::ERROR);
            $response = new ErrorResponse($message);
        }
        return $response;
    }


    /**
     * README previewer - this is for debugging purposes.
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function readmePreview()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::readmePreview()", \OCP\Util::DEBUG);
        $readme = $this->crateManager->getReadme($_SESSION['selected_crate']);
        return new ErrorResponse($readme, Http::STATUS_OK);
    }

    /**
     * README previewer - this is for debugging purposes.
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function xml()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::readmePreview()", \OCP\Util::DEBUG);
        $readme = $this->crateManager->getReadme($_SESSION['selected_crate']);
        return new ErrorResponse($readme, Http::STATUS_OK);
    }

    /**
     * Check crate
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function checkCrate()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::checkCrate()", \OCP\Util::DEBUG);
        try {
            $selected_crate = $_SESSION['selected_crate'];
            session_commit();
            $result = $this->crateManager->checkCrate($selected_crate);
            if(! is_array($result)) {
                $msg = 'This crate contains no files';
            } else {
                if (empty($result)) {
                    $msg = 'All items are valid.';
                } elseif (sizeof($result) === 1) {
                    $msg = 'The following item no longer exists:';
                } else {
                    $msg = 'The following items no longer exist:';
                }
            }
            return new JSONResponse(
                array('msg' => $msg,
                      'result' => $result)
            );
        } catch (\Exception $e) {
            return new JSONResponse(array($e->getMessage(), 'error' => $e), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Sync crate files back to owncloud
     *
     * @NoCSRFRequired
     * @Ajax
     * @NoAdminRequired
     */
    public function syncCrateFiles()
    {
        \OCP\Util::writeLog('crate_it', "CrateController::syncCrateFiles()", \OCP\Util::DEBUG);
        try {
            $filename = $this->params('file_name');
            $file = Util::getpublishPath() . '/' . $filename;
            $path = Util::getpublishPath() . '/' . basename($filename, '.zip');

            if (! file_exists($file)) {
                return new JSONResponse(array('msg' => 'published files not found'), Http::STATUS_NOT_FOUND);
            }

            if (! file_exists($path)) {
                mkdir($path, 0755, true);
                exec('unzip ' . $file . ' -d ' . $path, $zipOutput, $zipReturn);
                if ($zipReturn) {
                    return new JSONResponse(array('msg' => 'canot unzip the file'), Http::STATUS_INTERNAL_SERVER_ERROR);
                }
            }

            exec('cp -r ' . $path . ' ' . Util::getUserPath() . '/files/', $cpOutput, $cpReturn);
            if ($cpReturn) {
                return new JSONResponse(array('msg' => 'cannot copy the files'), Http::STATUS_INTERNAL_SERVER_ERROR);
            }

            exec('rm -rf ' . $path, $rmOutput, $rmReturn);

            exec('php /vagrant/owncloud/occ files:scan ' . \OC::$server->getUserSession()->getUser()->getUID(), $indexOutput, $indexReturn);
            if ($indexReturn) {
                return new JSONResponse(array('msg' => 'reindexing files failed'), Http::STATUS_INTERNAL_SERVER_ERROR);
            }

            return new JSONResponse(array('msg' => 'files successfully synced'), Http::STATUS_OK);
        } catch (\Exception $e) {
            return new JSONResponse(array($e->getMessage(), 'error' => $e), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }
}
