<?php

namespace OCA\crate_it\Service;

use OCA\crate_it\lib\Util;

class setupservice
{
    private $crateManager;

    private $publisher;

    private $userManager;

    // TODO add other defaults here
    private static $params = array('description_length' => 6000, 'max_sword_mb' => 0, 'max_zip_mb' => 0,);

    private static $loaded = false;

    public function __construct($crateManager, $publisher, $userManager)
    {
        $this->crateManager = $crateManager;
        $this->publisher = $publisher;
        $this->userManager = $userManager;
    }


    public function getParams()
    {
        if (!self::$loaded) {
            $this->loadParams();
        }
        return self::$params;
    }


    private function loadParams()
    {
        $this->loadConfigParams();
        $selectedCrate = $this->getSelectedCrate();
        self::$params['selected_crate'] = $selectedCrate;
        $this->publisher->registerPublishers(self::$params['publish endpoints']);
        self::$params['collections'] = $this->publisher->getCollections();
        self::$params['crates'] = $this->crateManager->getCrateList(false);

        $manifestData = $this->crateManager->getManifest($selectedCrate);
        self::$params['description'] = $manifestData['description'];
        self::$params['data_retention_period'] = $manifestData['data_retention_period'];
        self::$params['embargo_enabled'] = array_key_exists('embargo_enabled', $manifestData) ? $manifestData['embargo_enabled'] : '';
        self::$params['embargo_date'] = array_key_exists('embargo_date', $manifestData) ? $manifestData['embargo_date'] : '';
        self::$params['embargo_details'] = array_key_exists('embargo_details', $manifestData) ? $manifestData['embargo_details'] : '';
        self::$params['access_conditions'] = array_key_exists('access_conditions', $manifestData) ? $manifestData['access_conditions'] : '';

        self::$params['crateDetails'] = $this->crateManager->getCrateDetailsList();
        //$info = self::getAppInfo('crate_it');
        self::$params['version'] = \OCP\App::getAppVersion('crate_it');

        $userObject = \OC::$server->getUserSession()->getUser();
        $isSubAdmin = false;
        if ($userObject !== null) {
            $isSubAdmin = \OC::$server->getGroupManager()->getSubAdmin()->isSubAdmin($userObject);
        }
        self::$params['isSubAdmin'] = $isSubAdmin;

        if ($isSubAdmin) {
            self::$params['otherCrateDetails'] = [];
            $users = $this->userManager->search('');
            foreach ($users as $user) {
                if ($user->getDisplayName() !== $userObject->getDisplayName()) {
                    self::$params['otherCrateDetails'][$user->getDisplayName()] = $this->crateManager->getOtherCrateDetailsList($user);
                }
            }
        }
    }

    private function getSelectedCrate()
    {
        if (!isset($_SESSION['selected_crate'])) {
            $_SESSION['selected_crate'] = self::$params['crates'][0];
        }

        return $_SESSION['selected_crate'];
    }

    private function loadConfigParams()
    {
        $config = Util::getConfig();
        // TODO: No error handling if config is null
        foreach ($config as $key => $value) {
            \OCP\Util::writeLog('crate_it', "SetupService::loadConfigParams() - loading $key:".json_encode($value), \OCP\Util::DEBUG);
            self::$params[$key] = $value;
        }
    }
}
