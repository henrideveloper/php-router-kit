<?php

namespace TheHenriDev\PHPRouterKit;

class Document
{
  protected static function make(int $code, array $body)
  {
    http_response_code($code);

    die(json_encode($body));
  }

  public static function setHeader(string $name, string $value)
  {
    header("${name}: ${value}");
  }

  public static function finish(int $code = 404, string $digest = null, $payload = null)
  {
    $body = array();
    if($digest) {
      $body["digest"] = $digest;
    }
    
    if($payload) {
      $body["data"] = $payload;
    }

    self::setHeader("Content-Type", "application/json; charset=utf-8");
    self::make($code, $body);
  }
}
