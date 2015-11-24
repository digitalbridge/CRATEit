<?php

namespace OCA\crate_it\lib;

class ErrorResponse {
  
  public $errorMessage;
  public $status;

  public function __construct($errorMessage, $status=501) {
    $this->errorMessage = $errorMessage;
    $this->status = $status;
  }

}