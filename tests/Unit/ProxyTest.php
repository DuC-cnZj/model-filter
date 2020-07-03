<?php

namespace DucCnzj\ModelFilter\Tests\Unit;

use DucCnzj\ModelFilter\Tests\TestCase;
use DucCnzj\ModelFilter\Proxy\HigherOrderWhenProxy;

class ProxyTest extends TestCase
{
    public function testWhen()
    {
        $m = \Mockery::mock(\Stdclass::class);
        $m->shouldReceive('do')->once()->andReturn('done');
        $when = new HigherOrderWhenProxy($m, true);
        $this->assertEquals('done', $when->do());
    }

    public function testWhen1()
    {
        $m = \Mockery::spy(\Stdclass::class);
        $when = new HigherOrderWhenProxy($m, false);
        $this->assertSame($m, $when->do());
        $m->shouldNotHaveReceived('do');
        \Mockery::close();
    }

    public function testWhen2()
    {
        $m = new \Stdclass;
        $when = new HigherOrderWhenProxy($m, false);
        $this->assertEquals($m, $when->do);
    }

    public function testWhen3()
    {
        $m = new \Stdclass;
        $m->name = 'std';
        $when = new HigherOrderWhenProxy($m, true);
        $this->assertEquals('std', $when->name);
    }
}
