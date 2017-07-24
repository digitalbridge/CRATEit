<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 21/06/15
 * Time: 1:21 PM
 */

namespace OCA\crate_it\lib;

class folderpublisher implements Publisher
{
    private $endpoint;
    private $collection;

    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->collection = array($this->endpoint['name'] => $this->endpoint['path']);
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getCratePath($crateName, $collection, $timestamp)
    {
        return $this->endpoint['url prefix'] . $collection . rawurlencode($crateName) . "_$timestamp.zip";
    }

    public function publishCrate($package, $collection, $timestamp)
    {
        \OCP\Util::writeLog('crate_it', "FolderPublisher::publishCrate()", \OCP\Util::DEBUG);
        $destination = $collection . basename($package, '.zip') . "_$timestamp.zip";
        \OCP\Util::writeLog('crate_it', "Publishing to $destination", \OCP\Util::DEBUG);
        rename($package, $destination);
    }
}
