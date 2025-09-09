<?php

namespace TheHenriDev\PHPRouterKit;

interface MiddlewareInterface
{
  public function setNext(MiddlewareInterface $handler): MiddlewareInterface;
  
  public function getNext(): MiddlewareInterface;
  
  public function handle();
}
