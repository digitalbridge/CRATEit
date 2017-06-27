<?php

namespace OCA\crate_it\lib;

require 'search_provider.php';

class mint_connector implements SearchProvider
{
    private $url = null;
    private $curl = null;
    private $action = array('activities' => '/Activities/opensearch/lookup?searchTerms=',
                          'FOR' => '/ANZSRC_FOR/opensearch/lookup?searchTerms=',
                          'people' => '/Parties_People/opensearch/lookup?searchTerms=');

    public function __construct($url, $request)
    {
        $this->url = $url;
        $this->curl = $request;
    }

  // TODO: Make static?
  public function search($type, $keywords)
  {
      $result = array();
      $query = $this->url.$this->action[$type].urlencode($keywords);
      \OCP\Util::writeLog("crate_it::search", $query, \OCP\Util::DEBUG);
      $this->curl->setOption(CURLOPT_URL, $query);
      $this->curl->setOption(CURLOPT_RETURNTRANSFER, 1);
      $content = $this->curl->execute();
      $status = $this->curl->getStatus();
      $this->curl->close();
      \OCP\Util::writeLog("crate_it::response", $content, \OCP\Util::DEBUG);
      if (!empty($status)) {
          throw new \Exception("Grant / Author lookups are not available at this time, try again later. (cURL error: ".$status.")");
      }
      if (!empty($content)) {
          $content_array = json_decode($content);
      //\OCP\Util::writeLog("content_array", json_encode($content_array), \OCP\Util::ERROR);
      $result = $content_array->results;
      }
      return $result;
  }
}
