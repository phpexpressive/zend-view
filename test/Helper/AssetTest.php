<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zendframework for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\View\Helper;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Exception;
use Zend\View\Helper\Asset;
use Zend\View\HelperPluginManager;

class AssetTest extends TestCase
{
    /** @var array */
    protected $resourceMap = [
        'css/style.css' => 'css/style-3a97ff4ee3.css',
        'js/vendor.js' => 'js/vendor-a507086eba.js',
    ];

    /** @var Asset */
    protected $asset;

    protected function setUp()
    {
        parent::setUp();

        $this->asset = new Asset();
        $this->asset->setResourceMap($this->resourceMap);
    }

    public function testHelperPluginManagerReturnsAssetHelper()
    {
        $helpers = $this->getHelperPluginManager();
        $asset = $helpers->get('asset');

        $this->assertInstanceOf(Asset::class, $asset);
    }

    public function testHelperPluginManagerReturnsAssetHelperByClassName()
    {
        $helpers = $this->getHelperPluginManager();
        $asset = $helpers->get(Asset::class);

        $this->assertInstanceOf(Asset::class, $asset);
    }

    public function testInvalidAssetName()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class, 'Asset is not defined.');

        $this->asset->__invoke('unknown');
    }

    /**
     * @dataProvider assets
     *
     * @param string $name
     * @param string $expected
     */
    public function testInvokeResult($name, $expected)
    {
        $result = $this->asset->__invoke($name);

        $this->assertEquals($expected, $result);
    }

    public function assets()
    {
        $data = [];
        foreach ($this->resourceMap as $key => $value) {
            $data[] = [$key, $value];
        }
        return $data;
    }

    protected function getHelperPluginManager(array $config = [])
    {
        $services = $this->prophesize(ServiceManager::class);
        $services->get('config')->willReturn($config);

        return new HelperPluginManager($services->reveal());
    }
}
