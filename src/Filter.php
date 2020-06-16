<?php

namespace DucCnzj\ModelFilter;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Database\Eloquent\Builder;
use DucCnzj\ModelFilter\Proxy\HigherOrderWhenProxy;

/**
 * Class Filters
 * @package App\Filters
 */
abstract class Filter
{
    use Macroable;

    /**
     * @var callable|null
     */
    protected static $getFilterCallback;

    /**
     * @var bool
     */
    protected $usePrefix = false;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * Filters constructor.
     * @param $request
     */
    public function __construct($request)
    {
        if (is_object($request) && method_exists($request, 'all')) {
            $request = $request->all();
        }

        $this->request = collect($request);
    }

    /**
     * @param Builder $builder
     * @return Builder
     *
     * @author duc <1025434218@qq.com>
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $key => $value) {
            $method = $this->resolveMethod($key);

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }

        return $this->builder;
    }

    /**
     * @return array
     *
     * @author duc <1025434218@qq.com>
     */
    public function getFilters()
    {
        $inputs = $this->request->only($this->getKeys())->toArray();

        if (static::$getFilterCallback) {
            return call_user_func(static::$getFilterCallback, $inputs);
        }

        return array_filter($inputs, function ($item) {
            return ! is_null($item);
        });
    }

    /**
     * @return array
     *
     * @author duc <1025434218@qq.com>
     */
    private function getKeys(): array
    {
        $prefix = $this->usePrefix
            ? Str::endsWith($this->prefix, '_') ? $this->prefix : $this->prefix . '_'
            : null;

        return array_map(function ($key) use ($prefix) { return $prefix . $key; }, $this->filters);
    }

    /**
     * @param array|mixed $fields
     * @return Filter
     *
     * @author duc <1025434218@qq.com>
     */
    public function only($fields)
    {
        if (is_null($fields)) {
            return $this;
        }

        $fields = is_array($fields) ? $fields : func_get_args();

        $callback = function ($field) use ($fields) {
            return in_array($field, $fields);
        };

        $this->filters = array_filter($this->filters, $callback);

        return $this;
    }

    /**
     * @param string|bool $prefix
     *
     * @return $this
     *
     * @author duc <1025434218@qq.com>
     */
    public function withPrefix($prefix = true)
    {
        if (is_string($prefix)) {
            $this->usePrefix = true;
            $this->prefix = $prefix ?: $this->prefix;
        }

        if (is_bool($prefix)) {
            $this->usePrefix = $prefix;
        }

        return $this;
    }

    /**
     * @param string $key
     * @return string
     *
     * @author duc <1025434218@qq.com>
     */
    protected function resolveMethod(string $key): string
    {
        $field = $this->usePrefix ? Str::after($key, $this->prefix) : $key;

        return Str::camel($field);
    }

    /**
     * @param mixed $getFilterCallback
     */
    public static function setGetFilterCallback($getFilterCallback)
    {
        static::$getFilterCallback = $getFilterCallback;
    }

    /**
     * @return mixed
     */
    public static function getGetFilterCallback()
    {
        return static::$getFilterCallback;
    }

    /**
     * @param $when
     * @param callable|null $callback
     * @return $this|HigherOrderWhenProxy
     *
     * @author duc <1025434218@qq.com>
     */
    public function when($when, callable $callback = null)
    {
        if (! $callback) {
            return new HigherOrderWhenProxy($this, $when);
        }

        if ($when) {
            $callback($this);
        }

        return $this;
    }
}
