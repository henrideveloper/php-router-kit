<?php

namespace TheHenriDev\PHPRouterKit;

class Request
{
  public string $method;
  public URI $uri;
  public Payload $payload;

  public function __construct()
  {
    $this->method = $_SERVER["REQUEST_METHOD"];
    $this->uri = new URI();
    $this->payload = new Payload();
  }

  public function isPath(string $uri, &$argv)
  {
    $argv = array();
    $args = array();
    if(preg_match_all("/\{[^{}\/]+\}/m", $uri = str_replace("{}", "", $uri), $m, PREG_OFFSET_CAPTURE)) {
      foreach($m[0] as $tag) {
        array_push($args, substr($tag[0], 1, -1));
            
        $replace = "{";
        foreach(range(1, ($length = strlen($tag[0])) - 2) as $i) {
            $replace .= 0;
        }
        $replace .= "}";
          
        $uri = substr_replace($uri, $replace, $tag[1], $length);
      }
          
      $uri = preg_replace("/\\\\\{0+\\\\\}/m",  "([^\/]*)", preg_quote($uri, "/"));
    } else {
      $uri = preg_quote($uri, "/");
    }
    
    if($status = preg_match("/^{$uri}/m", $this->uri->path, $m)) {
      $argv = array_combine($args, array_slice($m, 1));
    }
    
    return $status;
  }

  public function isMethod(string $method) {
    return (strtoupper($method) == $this->method);
  }
}
