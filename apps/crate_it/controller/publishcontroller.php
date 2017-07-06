<?php

namespace OCA\crate_it\Controller;

use \OCA\crate_it\lib\Util;
use \OCP\AppFramework\Controller;
use \OCP\AppFramework\Http\JSONResponse;

class publishcontroller extends Controller
{
    private $publishingService;
    private $alertingService;
    private $crateManager;
    private $loggingService;
    private $mailer;

    public function __construct($appName, $request, $crateManager, $setupService, $publishingService, $alertingService, $loggingService, $mailer)
    {
        parent::__construct($appName, $request);
        $this->crateManager = $crateManager;
        $this->publishingService = $publishingService;
        $this->alertingService = $alertingService;
        $this->loggingService = $loggingService;
        $this->mailer = $mailer;
        $params = $setupService->getParams();
        // TODO: Some duplication here with SetupService methods, try to refactor out
        $this->publishingService->registerPublishers($params['publish endpoints']);
        $this->alertingService->registerAlerters($params['alerts']);
    }

    public function getCollections()
    {
        \OCP\Util::writeLog('crate_it', "PublishController::getCollections()", \OCP\Util::DEBUG);
        return $this->publishingService->getCollections();
    }

    /**
     * Email crate
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function emailReceipt()
    {
        $data = array();
        if ($_SESSION['last_published_status'] === 201) {
            $to = $this->params('address');
            $metadata = $this->params('metadata');

            $config = Util::getConfig();
            if ($config['crate_from_email']) {
                $from = $config['crate_from_email'];
            } else {
                $from = 'noreply@crate.app';
            }

            $subject = 'CRATEit Submit Status Receipt';
            try {
                $content = $this->getEmailContent($metadata);

                if ($this->mailer->sendHtml($to, null, $from, $subject, $content)) {
                    $data['msg'] = "A confirmation email has been sent to $to";
                    $status = 200;
                } else {
                    \OCP\Util::writeLog('Unable to send email: ', $result, \OCP\Util::DEBUG);
                }
            } catch (\Exception $e) {
                $data['msg'] = 'Error: ' . $e->getMessage();
                $status = 500;
            }
        } else {
            $data['msg'] = 'Error: No recently submitted crates';
            $status = 500; // NOTE: should this be in the 400 range?
        }
        return new JSONResponse($data, $status);
    }

    /**
     * Publish crate
     *
     * @Ajax
     * @NoAdminRequired
     */
    public function publishCrate()
    {
        \OCP\Util::writeLog('crate_it', "PublishController::publishCrate()", \OCP\Util::DEBUG);
        $crateName = $this->params('name');
        $endpoint = $this->params('endpoint');
        $collection = $this->params('collection');
        $this->loggingService->log("Attempting to publish crate $crateName to collection: $collection");
        $this->loggingService->logManifest($crateName);
        $config = Util::getConfig();
        $data = array();
        $metadata = array();

        try {
            $timestamp = Util::getTimestamp();
            $metadata = $this->crateManager->createMetadata($crateName);
            $cratePath = $this->publishingService->getCratePath($crateName, $endpoint, $collection, $timestamp);
            $metadata['location'] = $cratePath;
            $metadata['url'] = str_replace('${crate_name}', basename($cratePath), $config['submitted_crate_url']);
            $metadata['submitted_date'] = Util::getTimestamp("Y-m-d");
            $metadata['submitted_time'] = Util::getTimestamp("H:i:s");

            $publishDetails = [
                'version'        => $metadata['version'],
                'location'       => $metadata['location'],
                'url'            => $metadata['url'],
                'size'           => $metadata['size'],
                'submitted_date' => $metadata['submitted_date'],
                'submitted_time' => $metadata['submitted_time']
            ];
            $this->crateManager->updateCrate($crateName, 'publish_details', $publishDetails);

            $package = $this->crateManager->packageCrate($crateName);
            $this->loggingService->log("Zipped content into '" . basename($package) . "'");

            $this->publishingService->publishCrate($package, $endpoint, $collection, $timestamp);
            $this->loggingService->log("Submitting crate $crateName (" . basename($package) . ")..");

            session_start();
            $_SESSION['location'] = $metadata['location'];
            $_SESSION['url'] = $metadata['url'];

            $this->alertingService->alert($metadata);
            $this->loggingService->logPublishedDetails($cratePath, $crateName);
            $data['msg'] = "The Crate '$crateName' has been successfully submitted and a confirmation eMail has been sent to you.";
        } catch (\Exception $e) {
            $this->loggingService->log("Submitting crate '$crateName' failed.");
            // roll back publish details
            $this->crateManager->updateCrate($crateName, 'publish_details', '');
            $data['msg'] = "Error: failed to submit crate '$crateName': {$e->getMessage()}";
            $status = 500;
        }

        # Publish complete. Email the submitter if an email address has been configured.
        if ($metadata['submitter']['email']) {
            $to = $metadata['submitter']['email'];
        } else {
            $to = $config['crate_to_email_fallback'];
        }
        if ($config['crate_cc_email']) {
            $cc = $config['crate_cc_email'];
        }
        if ($config['crate_from_email']) {
            $from = $config['crate_from_email'];
        } else {
            $from = 'noreply@crate.app';
        }

        $metadata['file_name'] = basename($cratePath);
        $data['metadata'] = $metadata;
        $subject = 'CRATEit Submit Status Receipt';
        $content = $this->getEmailContent($metadata);
        $this->mailer->sendHtml($to, $cc, $from, $subject, $content);
        $status = 201;

        $this->loggingService->log($data['msg']);
        $_SESSION['last_published_status'] = $status;
        return new JSONResponse($data, $status);
    }


    private function getEmailContent($metadata)
    {
        $content = Util::renderTemplate('submission_email', $metadata);
        return $content;
    }
}
