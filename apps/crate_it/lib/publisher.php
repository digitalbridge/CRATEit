<?php

namespace OCA\crate_it\lib;

interface publisher
{
    public function getCollection();

    public function getCratePath($crateName, $collection, $timestamp);

    public function publishCrate($package, $collection, $timestamp);
}
