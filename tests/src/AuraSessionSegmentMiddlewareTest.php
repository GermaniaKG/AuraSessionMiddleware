<?php
namespace tests;

use Germania\AuraSessionMiddleware\AuraSessionSegmentMiddleware;
use Psr\Log\LoggerInterface;

use Slim\Http\Environment;
use Slim\Http\Response;
use Slim\Http\Request;
use Aura\Session\SegmentInterface;
use Aura\Session\Segment;

/**
 * @coversDefaultClass \Germania\Authorization\Authorization
 */
class AuraSessionSegmentMiddlewareTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideSessionVars
     */
    public function testInstantiation( $session_key, $session_value )
    {
        // Mock Env as seen on Slim3 docs:
        // http://www.slimframework.com/docs/cookbook/environment.html
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/foo/bar',
            'QUERY_STRING' => 'abc=123&foo=bar',
            'SERVER_NAME' => 'example.com',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
            'CONTENT_LENGTH' => 15
        ]);

        // Build Middleware constructor arguments
        $segment_mock = $this->prophesize( SegmentInterface::class );
        $segment_mock->get( $session_key )->willReturn( $session_value );
        $segment = $segment_mock->reveal();


        // Build "inner" Middleware arguments
        $request  = Request::createFromEnvironment( $env );
        $response = new Response;


        $next = function( $request, $response ) use ($session_key) {
            $inner_session = $request->getAttribute('session');
            $response->write( $inner_session->get( $session_key ) );
            return $response;
        };

        // Build SUT and invoke
        $sut = new AuraSessionSegmentMiddleware( $segment );
        $response = $sut($request, $response, $next);

        // Evaluate
        $this->assertEquals( (string) $response->getBody(), $session_value );

        $this->assertTrue( $request->hasAttribute('session'));
        return $sut;
    }

    public function provideSessionVars()
    {
        return array(
            [ "foo",    "bar" ],
            [ "user_id", 42   ]
        );
    }

}
