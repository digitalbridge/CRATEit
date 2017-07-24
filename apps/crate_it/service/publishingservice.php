<?php

namespace OCA\crate_it\Service;

class publishingservice
{
    private $publishers = array();

    public function registerPublishers($endpointsConfig)
    {
        foreach ($endpointsConfig as $publisher => $endpoints) {
            foreach ($endpoints as $endpoint) {
                if ($endpoint['enabled']) {
                    $this->registerPublisher($publisher, $endpoint);
                }
            }
        }
    }

    private function registerPublisher($publisher, $endpoint)
    {
        $className = 'OCA\crate_it\lib\\'.ucfirst($publisher).'Publisher';
        $this->publishers[$endpoint['name']] = new $className($endpoint);
    }

    public function getCollections()
    {
        $result = array();
        foreach ($this->publishers as $name => $publisher) {
            $collections = $publisher->getCollection();
            $result[$name] = $collections;
        }
        return $result;
    }

    public function getCratePath($crateName, $publisher, $collection, $timestamp)
    {
        return $this->publishers[$publisher]->getCratePath($crateName, $collection, $timestamp);
    }

    public function publishCrate($crate, $publisher, $collection, $timestamp)
    {
        return $this->publishers[$publisher]->publishCrate($crate, $collection, $timestamp);
    }
}
