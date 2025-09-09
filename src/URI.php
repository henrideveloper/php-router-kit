<?php

namespace TheHenriDev\PHPRouterKit;

class URI
{
  public string|null $scheme = "http";
  public string|null $host = null;
  public int|null $port = 80;
  public string|null $path = null;
  public array|null $query = null;
  public string|null $fragment = null;

  public function __construct()
  {
    $uri = parse_url($_SERVER["REQUEST_URI"]);
    
    if(isset($uri["query"])) {
      list($query, $uri["query"]) = array(explode("&", $uri["query"]), []);

      foreach($query as $token) {
        $element = array(null, "");
        foreach(explode("=", $token) as $key => $value) {
          $element[$key] = $value;
        }
        $uri["query"][$element[0]] = $element[1];
      }
    }

    if($uri["path"]) {
      $uri["path"] = preg_replace("/^\/api\/v1/m", "", $uri["path"]);

      if(! str_starts_with(strrev($uri["path"]), "/")) {
        $uri["path"] .= "/";
      }
    }

    foreach($uri as $token => $value) {
      $this->$token = $value;
    }
  }
}
