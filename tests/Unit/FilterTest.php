<?php

namespace DucCnzj\ModelFilter\Tests\Unit;

use Illuminate\Http\Request;
use DucCnzj\ModelFilter\Filter;
use Illuminate\Foundation\Application;
use DucCnzj\ModelFilter\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;
use DucCnzj\ModelFilter\Tests\Models\TestModel;
use DucCnzj\ModelFilter\Tests\Filters\TestModelFilter;
use DucCnzj\ModelFilter\Exceptions\ClassResolveException;
use DucCnzj\ModelFilter\Tests\Filters\TestModelWithPrefixFilter;

class FilterTest extends TestCase
{
    /**
     * @dataProvider dataCustom
     * @param $expect
     * @param $input
     * @author       duc <1025434218@qq.com>
     */
    public function testUserCallback($expect, $input)
    {
        Filter::setGetFilterCallback(null);
        $this->assertNull(Filter::getGetFilterCallback());
        Filter::setGetFilterCallback('array_filter');
        $this->assertNotNull(Filter::getGetFilterCallback());
        $f = new TestModelFilter($input);
        $this->assertEquals($expect, $f->getFilters());
        Filter::setGetFilterCallback(null);
    }

    /**
     * @dataProvider data
     * @param $expect
     * @param $input
     * @author       duc <1025434218@qq.com>
     */
    public function test($expect, $input)
    {
        $f = new TestModelFilter($input);
        $this->assertEquals($expect, $f->getFilters());
    }

    public function data()
    {
        return [
            [
                ['name' => 'duc', 'age' => 25],
                ['name' => 'duc', 'age' => 25],
            ],
            [
                ['name' => []],
                ['name' => []],
            ],
            [
                ['age' => 0],
                ['age' => 0],
            ],
            [
                ['age' => 0.0],
                ['age' => 0.0],
            ],
            [
                ['name' => 'duc', 'age' => 25],
                ['name' => 'duc', 'age' => 25, 'other' => 111],
            ],
            [
                ['age' => 25],
                ['name' => null, 'age' => 25, 'other' => 111],
            ],
            [
                ['name' => false, 'age' => 25],
                ['name' => false, 'age' => 25, 'other' => 111],
            ],
        ];
    }

    public function dataCustom()
    {
        return [
            [
                ['name' => 'duc', 'age' => 25],
                ['name' => 'duc', 'age' => 25],
            ],
            [
                ['name' => 'duc', 'age' => 25],
                ['name' => 'duc', 'age' => 25, 'other' => 111],
            ],
            [
                ['age' => 25],
                ['name' => null, 'age' => 25, 'other' => 111],
            ],
            [
                ['age' => 25],
                ['name' => false, 'age' => 25, 'other' => 111],
            ],
        ];
    }

    public function test3()
    {
        $f = new TestModelFilter(Request::create('/', 'GET', ['name' => 'duc', 'age' => false]));
        $this->assertEquals(['name'=>'duc', 'age' => false], $f->getFilters());
    }

    public function test4()
    {
        Application::getInstance()->setBasePath(realpath(__DIR__ . '/fixtures/exist'));

        TestModel::filter(Request::create('/', 'GET', ['name' => 'duc', 'age' => false]));

        $this->assertTrue(true);
    }

    public function test5()
    {
        $this->expectException(ClassResolveException::class);
        $this->expectErrorMessage("error resolve filter class for DucCnzj\ModelFilter\Tests\Models\TestModel");
        Application::getInstance()->setBasePath(realpath(__DIR__ . '/fixtures/notexist'));

        TestModel::filter(Request::create('/', 'GET', ['name' => 'duc', 'age' => false]));
    }

    public function test6()
    {
        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), new TestModelFilter(Request::create('/', 'GET', ['name' => 'duc', 'age' => 17, 'is_admin' => false])));
        $m->shouldHaveReceived('where')->with('name', 'duc')->once();
        $m->shouldHaveReceived('where')->with('age', 17)->once();
        $m->shouldNotHaveReceived('where', ['is_admin', false]);
    }

    public function testPrefix()
    {
        $r = $this->createRequest(['name' => 'duc', 'age' => 17, 'is_admin' => false]);
        $filter = (new TestModelWithPrefixFilter($r))->withPrefix();
        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $filter);
        $m->shouldNotHaveReceived('where', ['name', 'duc']);
        $m->shouldNotHaveReceived('where', ['age', 17]);
        $m->shouldNotHaveReceived('where', ['is_admin', false]);

        $r = $this->createRequest(['t_name' => 'duc', 't_age' => 17, 't_is_admin' => false]);
        $filter = (new TestModelWithPrefixFilter($r))->withPrefix();
        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $filter);
        $m->shouldHaveReceived('where', ['name', 'duc']);
        $m->shouldHaveReceived('where', ['age', 17]);
        $m->shouldNotHaveReceived('where', ['is_admin', false]);

        $r = $this->createRequest(['a_name' => 'duc', 'a_age' => 17, 'a_is_admin' => false]);
        $filter = (new TestModelWithPrefixFilter($r))->withPrefix('a');
        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $filter);
        $m->shouldHaveReceived('where', ['name', 'duc']);
        $m->shouldHaveReceived('where', ['age', 17]);
        $m->shouldNotHaveReceived('where', ['is_admin', false]);
    }

    public function testOnly()
    {
        $r = $this->createRequest(['name' => 'duc', 'a_age' => 17]);
        $filter = (new TestModelFilter($r))->only('age')->withPrefix('a_');
        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $filter);
        $m->shouldNotHaveReceived('where', ['name', 'duc']);
        $m->shouldHaveReceived('where', ['age', 17]);
    }

    private function createRequest($params)
    {
        return Request::create('/', 'GET', $params);
    }

    public function test7()
    {
        Application::getInstance()->setBasePath(realpath(__DIR__ . '/fixtures/exist'));
        $r = $this->createRequest(['name' => 'duc', 'age' => false]);

        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $r, ['name']);
        $m->shouldHaveReceived('where', ['name', 'duc']);
        $m->shouldNotHaveReceived('where', ['age', 17]);

        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $r, ['name'], true);
        $m->shouldNotHaveReceived('where', ['name', 'duc']);
        $m->shouldNotHaveReceived('where', ['age', 17]);

        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $r, ['name'], 'a');
        $m->shouldNotHaveReceived('where', ['name', 'duc']);
        $m->shouldNotHaveReceived('where', ['age', 17]);

        $r1 = $this->createRequest(['a_name' => 'duc', 'a_age' => false]);

        (new TestModel)->scopeFilter($m = \Mockery::spy(Builder::class), $r1, null, 'a');
        $m->shouldHaveReceived('where', ['name', 'duc']);
        $m->shouldHaveReceived('where', ['age', false]);
    }
}
