<?php

namespace DucCnzj\ModelFilter\Tests;

use DucCnzj\ModelFilter\FilterServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [FilterServiceProvider::class];
    }
}
