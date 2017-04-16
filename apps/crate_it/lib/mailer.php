<?php

namespace OCA\crate_it\lib;

class Mailer {
  
  public function send($to, $from, $subject, $content) {
    $result = mail($to, $subject, $content, "From: $from\n");
  	if(!$result) {
  		\OCP\Util::writeLog('Unable to send email: ', $result, \OCP\Util::ERROR);
  	}
    return $result;
  }

  public function sendHtml($to, $from, $subject, $content) {
    $headers = 'From: $from' . "\r\n";
  	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  	$result = mail($to, $subject, $content, $headers);
    if(!$result) {
    	\OCP\Util::writeLog('Unable to send email: ', $result, \OCP\Util::ERROR);
    	//\OCP\Util::writeLog('To: ', $to, \OCP\Util::ERROR);
    	//\OCP\Util::writeLog('Subject: ', $subject, \OCP\Util::ERROR);
    	//\OCP\Util::writeLog('Content: ', $content, \OCP\Util::ERROR);
    	//\OCP\Util::writeLog('Headers: ', $headers, \OCP\Util::ERROR);
    }
    return $result;
  }

}


