<?php

namespace DucCnzj\ModelFilter\Traits;

use DucCnzj\ModelFilter\Filter;
use Illuminate\Database\Eloquent\Builder;
use DucCnzj\ModelFilter\Exceptions\ClassResolveException;

/**
 * Trait Filterable
 * @package DucCnzj\ModelFilter\Traits
 *
 * @method static Builder filter($filters, $only = null, $prefix = null)
 */
trait HasFilter
{
    /**
     * @param Builder $query
     * @param Filter|string $filters
     * @param null $only
     * @param bool $prefix
     * @return mixed
     *
     * @throws ClassResolveException
     * @author duc <1025434218@qq.com>
     */
    public function scopeFilter($query, $filters, $only = null, $prefix = null)
    {
        if (! $filters instanceof Filter) {
            $class = $this->guessFilterClassName();

            $filters = new $class($filters);
        }

        return $filters
            ->when(! is_null($only))->only($only)
            ->when(! is_null($prefix))->withPrefix($prefix)
            ->apply($query);
    }

    public function guessFilterClassName()
    {
        $namespace = rtrim(app()->getNamespace(), '\\');

        $class = "{$namespace}\\Filters\\" . class_basename(static::class) . 'Filter';

        if (class_exists($class)) {
            return $class;
        }

        throw new ClassResolveException(sprintf('error resolve filter class for %s', static::class));
    }
}
