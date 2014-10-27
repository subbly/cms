<?php

namespace Subbly\Tests\Api\Service;

use Subbly\Subbly;
use Subbly\Api\Api;
use Subbly\Api\Service\SettingService;
use Subbly\Core\Container;

class SettingServiceTest extends \Subbly\Tests\Support\TestCase
{
    private function getService()
    {
        return Subbly::api('subbly.setting');
    }

    public function testConstruct()
    {
        $api = new Api(new Container(), array());
        $s   = new SettingService($api);

        $this->assertNotNull($s);
    }

    public function testAll()
    {
        $all = $this->getService()->all();

        $this->assertInstanceOf('ArrayObject', $all);
    }

    public function testGet()
    {
        $setting = $this->getService()->get('subbly.shop_name');

        $this->assertEquals('My first Subbly Shop', $setting);
    }

    public function testHas()
    {
        $hasSetting = $this->getService()->has('subbly.totaly_undefined_setting_key');
        $this->assertFalse($hasSetting);

        $hasSetting = $this->getService()->has('subbly.shop_name');
        $this->assertTrue($hasSetting);
    }

    public function testUpdate()
    {
        $tests = array(
            '-- a awesome string value --',
            1,
            20.20,
            true,
            false,
            null,
            array(1, 2, 3, 4),
        );

        foreach ($tests as $value)
        {
            $this->getService()->update('subbly.test_entry_setting', $value);
            $this->assertSame($this->getService()->get('subbly.test_entry_setting'), $value);
            // TODO must be false
        }
    }

    public function testName()
    {
        $this->assertEquals($this->getService()->name(), 'subbly.setting');
    }
}
