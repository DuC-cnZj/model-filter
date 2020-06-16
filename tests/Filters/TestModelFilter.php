<?php

namespace DucCnzj\ModelFilter\Tests\Filters;

use DucCnzj\ModelFilter\Filter;

class TestModelFilter extends Filter
{
    protected $filters = ['name', 'age'];

    public function name($name)
    {
        $this->builder->where('name', $name);
    }

    public function age($age)
    {
        $this->builder->where('age', $age);
    }
}
