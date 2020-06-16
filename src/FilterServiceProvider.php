<?php

namespace DucCnzj\ModelFilter;

use Illuminate\Support\ServiceProvider;
use DucCnzj\ModelFilter\Console\FilterMakeCommand;

class FilterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([FilterMakeCommand::class]);
        }
    }
}
