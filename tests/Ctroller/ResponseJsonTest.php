<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-08
 * Time: 上午 9:38.
 */

namespace xltxlm\helper\tests\Ctroller;

use PHPUnit\Framework\TestCase;
use xltxlm\helper\tests\Resource\Ctroller\Demo;
use xltxlm\helper\tests\Resource\Ctroller\SomeObject;

class ResponseJsonTest extends TestCase
{
    /**
     * @test
     */
    public function test1()
    {
        $this->markTestSkipped('单独运行,看输出内容');
        ((new Demo())
            ->setId(100.1)
            ->setName('hello')
            ->setObect(new SomeObject())
        )
        ();
    }
}
