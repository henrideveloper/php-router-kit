<?php

namespace TheHenriDev\PHPRouterKit;

class Middleware implements MiddlewareInterface
{
  protected Middleware|null $handler = null;

  public function handle()
  {
    return $this->getNext()->handle();
  }
  
  public function setNext(MiddlewareInterface $handler): MiddlewareInterface
  {
    $this->handler = $handler;
    
    return $this;
  }
  
  protected function makePseudoMiddleware(): MiddlewareInterface
  {
    return new class implements MiddlewareInterface
    {
      public function setNext(MiddlewareInterface $handler): MiddlewareInterface
      {
        return $this;
      }
      
      public function getNext(): MiddlewareInterface
      {
        return $this;
      }
      
      public function handle()
      {
        return true;
      }
    };
  }
  
  public function getNext(): MiddlewareInterface
  {
    return $this->handler ?? $this->makePseudoMiddleware();
  }

  public function __construct(MiddlewareInterface $handler = null)
  {
    if($handler instanceof MiddlewareInterface) {
      $this->setNext($handler);
    }
  }
}
