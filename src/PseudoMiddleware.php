<?php

namespace Henrideveloper\PHPRouterKit;

class PseudoMiddleware implements MiddlewareInterface
{
  public function handle()
  {
    return true;
  }

  public function setNext(MiddlewareInterface $handler): MiddlewareInterface
  {
    return $this;
  }
  
  public function getNext(): MiddlewareInterface
  {
    return $this;
  }
}
