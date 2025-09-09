<?php

namespace TheHenriDev\PHPRouterKit;

class Route
{
  public string|null $method = null;
  public string|null $path = null;
  public MiddlewareEngine|null $middlewareEngine;
  public \Closure|null $callback;
}
