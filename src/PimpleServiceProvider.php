<?php
namespace Germania\AuraSessionMiddleware;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Aura\Session\SessionFactory;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

class PimpleServiceProvider implements ServiceProviderInterface
{

    /**
     * @var string
     */
    public $session_name;


    /**
     * @var string
     */
    public $request_attribute_name;


    /**
     * @var LoggerInterface
     */
    public $logger;



    /**
     * @param string $session_name            The Session (segment) name
     * @param string $request_attribute_name  PSR7 Request attribute name, defaults to "session"
     * @param LoggerInterface $logger         Optional: PSR3 Logger instance
     */
    public function __construct( $session_name, $request_attribute_name = "session", LoggerInterface $logger = null )
    {
        $this->session_name = $session_name ?: get_class($this);
        $this->request_attribute_name = $request_attribute_name;
        $this->logger = $logger ?: new NullLogger;
    }


    /**
     * @param  Container $dic Pimple Container
     * @implements ServiceProviderInterface
     */
    public function register(Container $dic)
    {

        /**
         * @return Aura SessionSegment
         */
        $dic['Session'] = function( $dic ) {
            $session_factory = new SessionFactory;
            $session = $session_factory->newInstance( $_COOKIE );
            return $session->getSegment( $this->session_name );
        };


        /**
         * @return  \Psr\Log\LoggerInterface
         */
        $dic['Session.Logger'] = function( $dic ) {
            return $this->logger;
        };



        /**
         * @return Callable|AuraSessionSegmentMiddleware
         */
        $dic['Session.Middleware'] = function( $dic ) {
            $session = $dic['Session'];
            $logger  = $dic['Session.Logger'];

            $middleware = new AuraSessionSegmentMiddleware( $session, $logger );
            $middleware->setRequestAttributeName( $this->request_attribute_name );
            return $middleware;
        };

    }
}

