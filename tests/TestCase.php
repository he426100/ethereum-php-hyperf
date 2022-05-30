<?php

namespace Ethereum\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Contract\ApplicationInterface;
use Mockery;

abstract class TestCase extends BaseTestCase
{

    protected $container;
    protected $config;
    protected $app;

    protected function setUp(): void
    {
        $this->container = ApplicationContext::getContainer();
        $this->config = $this->container->get(ConfigInterface::class);
        $this->app = $this->container->get(ApplicationInterface::class);
    }

    protected function tearDown(): void
    {
        $this->delDir(BASE_PATH . '/runtime/container');
        Mockery::close();
    }

    public function delDir($path)
    {
        if (is_dir($path)) {
            $dirs = scandir($path);
            foreach ($dirs as $dir) {
                if ($dir != '.' && $dir != '..') {
                    $sonDir = $path . '/' . $dir;
                    if (is_dir($sonDir)) {
                        $this->delDir($sonDir);
                        @rmdir($sonDir);
                    } else {
                        @unlink($sonDir);
                    }
                }
            }
            @rmdir($path);
        }
    }

}
