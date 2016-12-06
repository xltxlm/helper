<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-06
 * Time: 下午 9:42
 */

namespace xltxlm\helper\tests\Resource\LoadFromArray;

use xltxlm\helper\Hclass\LoadFromArray;

/**
 * 带有下划线的特殊类
 * Class DemoLine
 * @package xltxlm\helper\tests\Resource\LoadFromArray
 */
class DemoLine
{
    use LoadFromArray;
    protected $name;
    protected $my_id;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMyId()
    {
        return $this->my_id;
    }
}