<?php

namespace Henrideveloper\PHPRouterKit;

class MiddlewareEngine
{
  protected MiddlewareInterface|null $stack = null;
  protected MiddlewareInterface|null $current = null;
  
  public function stackUp(MiddlewareInterface $handler)
  {
    if(is_null($this->stack)) {
      $this->stack = $this->current = $handler;
    } else {
      $this->current->setNext($handler);
      $this->current = $handler;
    }
    
    return $this;
  }
  
  public function runStack()
  {
    return $this->stack instanceof MiddlewareInterface ? $this->stack->handle() : true;
  }
}
