<?php

namespace DucCnzj\ModelFilter\Proxy;

class HigherOrderWhenProxy
{
    protected $target;

    protected $condition;

    public function __construct($target, $condition)
    {
        $this->condition = $condition;
        $this->target = $target;
    }

    public function __get($key)
    {
        return $this->condition
            ? $this->target->{$key}
            : $this->target;
    }

    public function __call($method, $parameters)
    {
        return $this->condition
            ? $this->target->{$method}(...$parameters)
            : $this->target;
    }
}
