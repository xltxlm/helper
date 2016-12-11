<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-07
 * Time: 下午 10:37
 */

namespace xltxlm\helper\Ctroller;

/**
 * 输出json格式的数据
 * Class OutJson
 * @package xltxlm\helper\Ctroller
 */
trait ResponseJson
{
    /** @var string jsonp的输出函数名,约定就是这个名字 */
    protected $jsfunction = "";

    /**
     * @return string
     */
    public function getJsfunction(): string
    {
        return $this->jsfunction;
    }

    /**
     * @param string $jsfunction
     * @return ResponseJson
     */
    public function setJsfunction(string $jsfunction): ResponseJson
    {
        $this->jsfunction = $jsfunction;
        return $this;
    }

    /**
     * 把整个类当成输出模型,输出Json结构
     * @return string
     */
    final public function __invoke()
    {
        $thisData = [];
        //排除掉对象类型的属性
        foreach ($this as $k => $v) {
            if (is_object($v) || $k == 'jsfunction') {
                continue;
            }
            $thisData[$k] = $v;
        }
        if ($this->jsfunction) {
            $returnData = json_encode($thisData, JSON_UNESCAPED_UNICODE);
            $dataNew = "{$this->jsfunction}(".$returnData.")";
        } else {
            $dataNew = json_encode($thisData, JSON_UNESCAPED_UNICODE);
        }
        if (!$this->jsfunction && !headers_sent()) {
            header("Access-Control-Allow-Origin:*");
            header("Content-type:application/json");
            header('Content-Length: '.strlen($dataNew));
        }
        echo $dataNew;
        return $dataNew;
    }
}
