<?php

namespace OCA\crate_it\lib;

use \OCP\AppFramework\Http;
use \OCP\AppFramework\Http\Response;

/**
 * Provides an error response to the user
 */
class ErrorResponse extends Response {

    private $errorMessage;

    public function __construct($errorMessage, $statusCode=Http::STATUS_INTERNAL_SERVER_ERROR){
        $this->errorMessage = $errorMessage;
        $this->setStatus($statusCode);
    }


    public function render(){
        return $this->errorMessage;
    }
}