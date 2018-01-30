<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2018/1/29
 * Time: 13:13
 */

namespace xltxlm\helper\Shell;

use xltxlm\helper\Util;

/**
 * 执行远程/本地的shell代码
 * Class Exec
 * @package xltxlm\helper\Shell
 */
class Exec
{
    /** @var string 远程机器的ip */
    protected $host = '';
    /** @var string 执行的命令 */
    protected $cmd = "";

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return Exec
     */
    public function setHost(string $host): Exec
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getCmd(): string
    {
        return $this->cmd;
    }

    /**
     * @param string $cmd
     * @return Exec
     */
    public function setCmd(string $cmd): Exec
    {
        $this->cmd = $cmd;
        return $this;
    }


    public function __invoke()
    {
        Util::d($this->getCmd());
        exec($this->getCmd(), $out, $return);
        if ($return) {
            throw new \Exception("执行命令错误:" . join($out) . ",错误值:$return");
        }
    }
}