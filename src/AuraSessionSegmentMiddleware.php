<?php
namespace Germania\AuraSessionMiddleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Aura\Session\SegmentInterface;


class AuraSessionSegmentMiddleware
{

    /**
     * @var SegmentInterface
     */
    public $segment;

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var string
     */
    public $request_attribute_name = 'session';



    /**
     * @param SegmentInterface        $segment  Aura.Session Segment
     * @param LoggerInterface|null    $logger   Optional: PSR-3 Logger
     */
    public function __construct( SegmentInterface $segment, LoggerInterface $logger = null)
    {
        $this->segment   = $segment;
        $this->logger    = $logger ?: new NullLogger;
    }


    /**
     * Sets the PSR7 Request attribute name for the Aura.Session Segment.
     * Must be called before middelware invokation!
     *
     * @param  string $request_attribute_name
     * @return self   Fluent Interface
     */
    public function setRequestAttributeName( $request_attribute_name )
    {
        $this->request_attribute_name = $request_attribute_name;
        $this->logger->info("Set PS7 Request attribute name for Aura.Session segment", [
            'attribute_name' => $request_attribute_name
        ]);
        return $this;
    }

    /**
     * Returns the PSR7 Request attribute name for the Aura.Session Segment.
     *
     * @return string
     */
    public function getRequestAttributeName( $request_attribute_name )
    {
        return $this->request_attribute_name;
    }


    /**
     * @param  Psr\Http\Message\ServerRequestInterface  $request  PSR7 request
     * @param  Psr\Http\Message\ResponseInterface       $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        // ---------------------------------------
        //  1. Set session attribute
        // ---------------------------------------

        // Add attribute to Request
        // This will be available within any follow-up middleware and routes.
        $this->logger->info("Before Route: Inject Aura.Session segment to Request");

        $request_attribute_name = $this->getRequestAttributeName();
        $request = $request->withAttribute($request_attribute_name, $this->segment);


        // ---------------------------------------
        //  2. Call next middleware.
        // ---------------------------------------
        $response = $next($request, $response);


        // ---------------------------------------
        // 3. Return response
        // ---------------------------------------
        $this->logger->debug("After Route: noop");
        return $response;
    }



}
