<?php

namespace TheHenriDev\PHPRouterKit;

class Payload
{
  public string $mimetype;
  public array $params;
  public $data;

  public function parseMimetype($contentType)
  {
    $parts = explode(";", $contentType);
    $mimetype = trim($parts[0]);
    $params = [];

    if(count($parts) > 1) {
      for($i = 1; $i < count($parts); $i++) {
        list($key, $value) = explode("=", trim($parts[$i]), 2);
        $params[trim($key)] = trim($value);
      }
    }
    
    return array($mimetype, $params);
  }

  public function __construct()
  {
    if(in_array($_SERVER["REQUEST_METHOD"], ["POST", "PUT", "PATCH"])) {
      $this->mimetype = "application/x-www-form-urlencoded";
      $this->params = array();

      if(isset($_SERVER["CONTENT_TYPE"])) {
        list($this->mimetype, $this->params) = $this->parseMimetype($_SERVER["CONTENT_TYPE"]);
      }

      if("application/json" === strtolower($this->mimetype)) {
        $this->data = json_decode(file_get_contents("php://input"), true);
      }
    }
  }
}
