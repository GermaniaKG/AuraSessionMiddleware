<?php
namespace tests;

use Germania\AuraSessionMiddleware\PimpleServiceProvider;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use Aura\Session\SegmentInterface;

class PimpleServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    public $logger_mock;

    public function setUp()
    {
        $logger = $this->prophesize(LoggerInterface::class);
        $this->logger_mock = $logger->reveal();
    }

    /**
     * @dataProvider provideCtorArguments
     */
    public function testRegisteringServiceProvider($session_name, $request_attribute_name, $logger )
    {
        $dic = new Container;

        $sut = new PimpleServiceProvider("session", "session");
        $sut->register( $dic );

        $this->assertInstanceOf(ServiceProviderInterface::class, $sut);

        $this->assertInstanceOf(LoggerInterface::class, $dic['Session.Logger']);
        $this->assertInstanceOf(SegmentInterface::class, $dic['Session']);
        $this->assertTrue( is_callable($dic['Session.Middleware']) );

    }


    public function provideCtorArguments()
    {
        return array(
            ["session", "session", null ],
            ["session", "session", $this->logger_mock ],
            ["session", "session", new NullLogger ]
        );
    }
}
