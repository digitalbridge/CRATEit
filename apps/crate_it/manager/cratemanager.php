<?php

namespace OCA\crate_it\Manager;

use OCA\crate_it\lib\Crate;
use OCA\crate_it\lib\Util;

class cratemanager
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //\OCP\Util::writeLog('CRATE Manager SESSION', $_SESSION['selected_crate'] . " Id " . session_id(), \OCP\Util::WARN);

        if (\OCP\User::isLoggedIn()) {
            $this->ensureDefaultCrateExists();
            $this->ensureCrateIsSelected();
            $this->ensurePrimaryContactsExists();
        }
    }

    // TODO: getCrate and createCrate do just about the same thing, perhaps they can be rolled into one
    public function createCrate($crateName, $description, $data_retention_period)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::createCrate($crateName, $description, $data_retention_period)", \OCP\Util::DEBUG);
        $crateRoot = $this->getCrateRoot();
        new Crate($crateRoot, $crateName, $description, $data_retention_period);
        // TODO: Just returns a parameter that was passed?
        return $crateName;
    }

    /**
     * Create the bag with manifest file for the crate
     * Throws exception when fail
     */
    public function getCrate($crateName)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::getCrate(".$crateName.")", \OCP\Util::DEBUG);
        $crateRoot = $this->getCrateRoot();
        if (!file_exists($crateRoot.'/'.urldecode($crateName))) {
            throw new \Exception("Crate $crateName not found");
        }
        return new Crate($crateRoot, urldecode($crateName));
    }

    public function getCrateList($published = false)
    {
        \OCP\Util::writeLog("crate_it", 'CrateManager::getCrateList()', \OCP\Util::DEBUG);
        $cratelist = array();
        $crateRoot = $this->getCrateRoot();
        if ($handle = opendir($crateRoot)) {
            $filteredlist = array('.', '..', 'packages', '.Trash', '.DS_Store');
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $filteredlist)) {
                    $manifest = $this->getManifest($file);
                    if ($published) {
                        if (array_key_exists('publish_details', $manifest) && ! empty($manifest['publish_details'])) {
                            array_push($cratelist, $file);
                        }
                    } else {
                        // if (! array_key_exists('publish_details', $manifest) || empty($manifest['publish_details'])) {
                            array_push($cratelist, $file);
                        // }
                    }
                }
            }
            closedir($handle);
        }
        return $cratelist;
    }

    public function getCrateFiles($crateName)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::getCrateFiles(".$crateName.")", \OCP\Util::DEBUG);
        $contents = $this->getManifest($crateName);
        return json_encode($contents['vfs']);
    }

    public function createMetadata($crateName)
    {
        $crate = $this->getCrate($crateName);
        return $crate->createMetadata();
    }

    public function validateCrateMetadata($crateName)
    {
        $crate = $this->getCrate($crateName);
        return $crate->validateMetadata();
    }

    private function ensureDefaultCrateExists()
    {
        $crateRoot = $this->getCrateRoot();
        if (!file_exists($crateRoot)) {
            mkdir($crateRoot, 0755, true);
        }
        $crateList = $this->getCrateList(false);
        if (empty($crateList)) {
            $this->createCrate('default_crate', 'Example Description', 'None');
        }
    }

    private function ensureCrateIsSelected()
    {
        $crateList = $this->getCrateList(false);
        if (! array_key_exists('selected_crate', $_SESSION) || ! in_array($_SESSION['selected_crate'], $crateList)) {
            $_SESSION['selected_crate'] = $crateList[0];
            session_commit();
        }
    }

    private function ensurePrimaryContactsExists()
    {
        $crate = $this->getCrate($_SESSION['selected_crate']);
        $manifest = $crate->getManifest();
        if(! array_key_exists('primarycontacts', $manifest)) {
            $manifest['primarycontacts'] = array();
            $crate->setManifest($manifest);
        }
    }

    public function getReadme($crateName)
    {
        $crate = $this->getCrate($crateName);
        return $crate->getReadme();
    }

    private function getCrateRoot()
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::getCrateRoot()", \OCP\Util::DEBUG);
        return Util::joinPaths(Util::getUserPath(), 'crates');
    }

    public function getManifest($crateName)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::getManifest(".$crateName.")", \OCP\Util::DEBUG);
        $crate = $this->getCrate($crateName);
        return $crate->getManifest();
    }

    public function getCrateDetailsList()
    {
        $crateNamesList = $this->getCrateList(true);
        $crateDetails = array();
        $crateDetailsList = array();

        foreach ($crateNamesList as $crateName) {
            $crate = $this->getCrate($crateName);
            $crateDetails['name'] = $crateName;
            $crateDetails['size'] = $this->getCrateSize($crateName);
            $crateDetails['contents'] = $crate->getBagContents();
            $crateDetails['manifest'] = $this->getManifest($crateName);
            $crateDetailsList[$crateName] = $crateDetails;
        }
        return $crateDetailsList;
    }

    public function addToCrate($crateName, $path)
    {
        \OCP\Util::writeLog('crate_it', "Crate::addToCrate(".$crateName.','.$path.")", \OCP\Util::DEBUG);
        $crate = $this->getCrate($crateName);
        $crate->addToCrate($path);
    }

    public function getCrateSize($crateName)
    {
        $crate = $this->getCrate($crateName);
        $total = $crate->getSize();
        \OCP\Util::writeLog('crate_it', "CrateManager::getCrateSize() - Crate size: ".$total, \OCP\Util::DEBUG);
        $data = array('size' => $total, 'human' => \OCP\Util::humanFileSize($total));
        return $data;
    }

    public function updateCrate($crateName, $field, $value)
    {
        $crate = $this->getCrate($crateName);
        $crate->updateCrate($field, $value);
    }

    public function deleteCrate($crateName)
    {
        $crate = $this->getCrate($crateName);
        $crate->deleteCrate();
        $this->ensureDefaultCrateExists();
        $this->ensureCrateIsSelected();
        $this->ensurePrimaryContactsExists();
    }

    public function renameCrate($crateName, $newCrateName)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::renameCrate($crateName, $newCrateName)", \OCP\Util::DEBUG);
        $crate = $this->getCrate($crateName);
        $crate->renameCrate($newCrateName);
    }

    public function packageCrate($crateName)
    {
        $this->updateCrateCheckIcons($crateName);
        $crate = $this->getCrate($crateName);
        $tempdir = Util::joinPaths(\OC::$server->getUserSession()->getUser()->getUID());
        if (!file_exists($tempdir)) {
            mkdir($tempdir, 0755, true);
        }
        return $crate->packageCrate($tempdir);
    }

    public function generateEPUB($crateName)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::generateEPUB() - ".$crateName, \OCP\Util::DEBUG);
        $crate = $this->getCrate($crateName);
        return $crate->generateEPUB();
    }

    private function updateCrateCheckIcons($crateName)
    {
        $crate = $this->getCrate($crateName);
        $manifest = $crate->getManifest();
        $rootfolder = &$manifest['vfs'][0];
        $children = &$rootfolder['children'];
        if ($children === null) {
            $children = array();
            $rootfolder['valid'] = var_export(true, true);
            $crate->setManifest($manifest);
        }
        $valid = true;
        foreach ($children as &$child) {
            $childValid = $this->validateNode($child, $crate, $manifest);
            $valid =$valid && $childValid;
            $rootfolder['valid'] = var_export($valid, true);
            $crate->setManifest($manifest);
        }
        \OCP\Util::writeLog('crate_it', "CrateManager::validateNode() - rootfolder is ".var_export($valid, true), \OCP\Util::DEBUG);
    }

    private function validateNode(&$node, $crate, &$manifest)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::validateNode() - ".$node['name'], \OCP\Util::DEBUG);
        $valid = true;
        if ($node['id'] === 'folder') {
            $children = &$node['children'];
            foreach ($children as &$child) {
                $childValid = $this->validateNode($child, $crate, $manifest);
                $valid = $valid && $childValid;
            }
            $node['valid'] = var_export($valid, true);
            $crate->setManifest($manifest);
        } else {
            $filename = $node['filename'];
            $valid = \OC\Files\Filesystem::file_exists($filename);
            $node['valid'] = var_export($valid, true);
            $crate->setManifest($manifest);
        }
        \OCP\Util::writeLog('crate_it', "CrateManager::validateNode() - ".$node['name']." is ".var_export($valid, true), \OCP\Util::DEBUG);

        return $valid;
    }

    public function checkCrate($crateName)
    {
        \OCP\Util::writeLog('crate_it', "CrateManager::checkCrate() - " . $crateName, \OCP\Util::DEBUG);
        $crate = $this->getCrate($crateName);
        $files = $crate->getAllFilesAndFolders();
        $result = array();

        $msg = '';
        $valid = false;
        $config = Util::getConfig();
        $manifest = json_decode($this->getManifestFileContent($crateName), true);

        if ($config['validate_crate_name'] && empty($crateName)) {
            $msg .= 'Crate name cannot be blank.<br />';
        }

        if ($config['validate_crate_description'] && empty($manifest['description'])) {
            $msg .= 'Crate description cannot be blank.<br />';
        }

        if ($config['validate_data_creators'] && empty($manifest['creators'])) {
            $msg .= 'There must be at least 1 Data Creator.<br />';
        }

        if ($config['validate_data_retention_period'] && $manifest['data_retention_period'] === "Please Select") {
            $msg .= 'Data Retention Period must be set.<br />';
        }

        if ($config['validate_access_conditions'] && empty($manifest['access_conditions'])) {
            $msg .= 'Access Conditions must be set.<br />';
        }

        if ($config['validate_access_permissions_statement'] && empty($manifest['access_permissions_statement'])) {
            $msg .= 'Access Permissions Statement cannot be blank.<br />';
        }

        if (count($files) === 0) {
            $msg .= 'This crate contains no files.<br />';
        } else {
            foreach ($files as $filepath) {
                \OCP\Util::writeLog('crate_it', "CrateManager::checkCrate() - checking " . $filepath, \OCP\Util::DEBUG);
                $file_exist = \OC\Files\Filesystem::file_exists($filepath);
                if (! $file_exist) {
                    \OCP\Util::writeLog('crate_it', "CrateManager::checkCrate() - file does not exist: " . $filepath, \OCP\Util::DEBUG);
                    $result[basename($filepath)] = $file_exist;
                    $missing_files[] = basename($filepath);
                }
            }

            if (! empty($missing_files)) {
                $msg .= 'The following item(s) no longer exist:<br />';

                foreach($missing_files as $file) {
                    $msg .= '&nbsp; - &nbsp;' . $file . '<br />';
                }
            }
        }

        if (empty($msg)) {
            $msg = 'All items are valid.';
            $valid = true;
        }

        $result['msg'] = $msg;
        $result['valid'] = $valid;

        $this->updateCrateCheckIcons($crateName);
        return $result;
    }

    public function getManifestFileContent($crateName)
    {
        $crate = $this->getCrate($crateName);
        return $crate->getManifestFileContent();
    }

    public function getOtherCrateDetailsList($user)
    {
        $userRoot = Util::getPathByuser($user->getUID());

        $crateDetails = array();
        $crateDetailsList = array();
        $crateRoot = Util::joinPaths($userRoot, 'crates');
        if ($handle = opendir($crateRoot)) {
            $filteredlist = array('.', '..', 'packages', '.Trash', '.DS_Store');
            while (false !== ($file = readdir($handle))) {
                if (! in_array($file, $filteredlist)) {
                    $crate = new Crate($crateRoot, urldecode($file));
                    $crateDetails['name'] = $file;
                    $crateDetails['size'] = array('size' => $crate->getSize(), 'human' => \OCP\Util::humanFileSize($crate->getSize()));
                    $crateDetails['contents'] = $crate->getBagContents();
                    $crateDetails['manifest'] = $crate->getManifest();
                    if (array_key_exists('publish_details', $crateDetails['manifest']) && ! empty($crateDetails['manifest']['publish_details'])) {
                        array_push($crateDetailsList, $crateDetails);
                    }
                }
            }
            closedir($handle);
        }

        return $crateDetailsList;
    }
}
