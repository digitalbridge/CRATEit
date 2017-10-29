<?php

namespace OCA\crate_it\lib;

class mailer
{
    public function send($to, $from, $subject, $content)
    {
        $result = mail($to, $subject, $content, "From: " . $from,  '-f ' . $from);
        if (!$result) {
            \OCP\Util::writeLog('Unable to send email: ', $result, \OCP\Util::ERROR);
        }
        return $result;
    }

    public function sendHtml($to, $cc, $from, $subject, $content)
    {
        if (!$from) {
            $from = 'noreply@crateit.app';
        }
        $headers = "From: " . $from . "\r\n";
        $headers = "Cc: " . $cc . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $result = mail($to, $subject, $content, $headers, '-f ' . $from);
        if (!$result) {
            \OCP\Util::writeLog('Unable to send email: ', $result, \OCP\Util::ERROR);
            \OCP\Util::writeLog('Headers: ', $headers, \OCP\Util::ERROR);
        }
        return $result;
    }
}
