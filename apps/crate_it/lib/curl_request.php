<?php

namespace OCA\crate_it\lib;

require 'curl_wrapper.php';

class CurlRequest implements CurlWrapper {

    private $handle = null;

    public function __construct() {
        $this->handle = curl_init();
    }

    public function setOption($name, $value) {
        curl_setopt($this->handle, $name, $value);
    }

    public function execute() {
        return curl_exec($this->handle);
    }

    public function getInfo($name) {
        return curl_getinfo($this->handle, $name);
    }

    public function getStatus() {
        return curl_error($this->handle);
    }

    public function close() {
        curl_close($this->handle);
    }

}