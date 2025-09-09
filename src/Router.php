<?php

namespace Henrideveloper\PHPRouterKit;

class Router
{
  public Request $request;
  public array $routes;
  public middlewareEngine|null $middlewareEngine;
  
  public function __construct()
  {
    $this->request = new Request();
    $this->routes = array();
    $this->middlewareEngine = new MiddlewareEngine();
  }

  public function makeRoute(string $method = null, string $path = null, MiddlewareEngine $middlewareEngine = null, \Closure $callback = null)
  {
    $route = new Route();
    $route->method = $method;
    $route->path = $path;
    $route->middlewareEngine = $middlewareEngine;
    $route->callback = $callback;

    return $route;
  }
  
  public function setMiddlewareEngine(MiddlewareEngine $middlewareEngine, string $route = null): MiddlewareEngine
  {
    if($route) {
      if(is_int($offset = strpos($route, ":"))) {
        list($method, $path) = explode(":", $route);

        if(empty($method) or empty($route)) {
          throw new \Exception("Missing method ou path", 1);
        } else {
          if(isset($this->routes[$route])) {
            $this->routes[$route]->middlewareEngine = $middlewareEngine;
          } else {
            $this->routes[$route] = $this->makeRoute($method, $path, $middlewareEngine);
          }
        }
      } else {
        throw new \Exception("Invalid route", 1);
      }
    } else {
      $this->middlewareEngine = $middlewareEngine;
    }
    
    return $middlewareEngine;
  }

  public function run()
  {
    foreach($this->routes as $path => $route) {
      $args = null;
      if($this->request->isMethod($route->method) and $this->request->isPath($route->path, $args)) {
        $callback = $route->callback;
        if(is_null($route->middlewareEngine) or $route->middlewareEngine->runStack()) {
          $callback($this->request, count($args), $args);
        }

        Document::finish(200);
      }
    }
  }

  public function __call(string $method, array $args)
  {
    if(isset($args[0], $args[1])) {
      if(!is_string($args[0])) {
        throw new \Exception("Argument 1 is not a route.");
      } elseif(!is_callable($args[1])) {
        throw new \Exception("Argument 2 is not callable.");
      }

      $route = "{$method}:{$args[0]}";

      if(isset($this->routes[$route]) and is_null($this->routes[$route]->callback)) {
        $this->routes[$route]->callback = $args[1];
      } else {
        $this->routes[$route] = $this->makeRoute($method, $args[0], $this->middlewareEngine, $args[1]);
      }

      return $this;
    } else {
      throw new \Exception("Missing uri or callback function.");
    }
  }
}
