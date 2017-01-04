<?php
/**
 * Created by PhpStorm.
 * User: xlt
 * Date: 2016/3/21
 * Time: 9:16.
 */

namespace xltxlm\helper\Ctroller;

use Psr\Log\LogLevel;
use xltxlm\helper\Ctroller\Logger\LoadClassLogger;
use xltxlm\helper\Ctroller\Logger\LoadError;
use xltxlm\helper\Util;
use xltxlm\logger\Logger;

/**
 * 调起路由类,两种加载方式 1: 直接指定类 2: 命名空间 + 命名空间下面的相对路径
 * Class LoadClass.
 */
final class LoadClass
{
    /** @var string 当前正在运行的类 */
    public static $runClass = '';
    /** @var string 根命名空间 */
    public static $rootNamespce = '';
    /** @var string 根命名空间 */
    public static $rootDir = '';

    /** @var string 启动的类 */
    private $className = '';
    /** @var string 相对于根命名空间的路径 */
    private $urlPath = '';

    /**
     * @return string
     */
    public function getRunClass()
    {
        return self::$runClass;
    }

    /**
     * @param string $className
     *
     * @return LoadClass
     */
    public function setClassName(string $className): LoadClass
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @param string $urlPath
     *
     * @return LoadClass
     */
    public function setUrlPath($urlPath)
    {
        $this->urlPath = strtr($urlPath, ['/' => '\\']);

        return $this;
    }

    /**
     * @return string
     */
    public function getRootNamespce(): string
    {
        return self::$rootNamespce;
    }

    /**
     * @param string $rootNamespce
     *
     * @return LoadClass
     */
    public function setRootNamespce($rootNamespce)
    {
        $reflectionClass = new \ReflectionClass($rootNamespce);
        self::$rootNamespce = $reflectionClass->getNamespaceName();
        self::$rootDir = dirname($reflectionClass->getFileName());
        return $this;
    }

    /**
     * The __invoke method is called when a script tries to call an object as a function.
     *
     * @throws \Exception
     *
     * @return $this
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
     */
    public function __invoke()
    {
        //自动加载请求类
        spl_autoload_register(function ($class) {
            $request = substr($class, -7);
            if ($request == 'Request') {
                $filepath = LoadClass::$rootDir.strtr($class, [LoadClass::$rootNamespce => '', '\\' => '/', 'Request' => '.request.php']);
                eval('include_once  $filepath;');
            }
        });
        if (!$this->className) {
            $this->className = '\\'.self::$rootNamespce.'\\'.$this->urlPath;
        }
        try {
            $start = microtime(true);
            /** @var \xltxlm\helper\Ctroller\Unit\RunInvoke $classNameObject */
            $classNameObject = new $this->className();
            //声明代码正确找到位置,找到类
            self::$runClass = get_class($classNameObject);
            call_user_func([$classNameObject, '__invoke']);
            $time = microtime(true) - $start;
            //记录网页执行时间,如果超过1秒,标记为超时
            (new Logger(
                (new LoadClassLogger)
                    ->setType($time > 1 ? LogLevel::EMERGENCY : LogLevel::INFO)
                    ->setTime($time)
                    ->setClassName($this->className)
            ))
                ->setSuffix(".access.log")
                ->__invoke();
        } finally {
            $this->className = substr(strtr($this->className, ['/' => '\\']), 1);
            if (!in_array($this->className, get_declared_classes())) {
                //记录无法加载的路径 - 有来源的时候才记录
                $_SERVER['HTTP_REFERER'] && ((new Logger())
                    ->setDefine(
                        (new LoadError())
                            ->setMissClassName($this->className)
                            ->setType(LogLevel::DEBUG)
                    ))();
                header('HTTP/1.1 588 APP ERROR@'.$this->className);
                header('Status: 588 APP ERROR@'.$this->className);
            }
        }

        return $this;
    }
}
