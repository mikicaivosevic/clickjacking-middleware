<?php
namespace Clickjacking\Middleware\Test;

use Clickjacking\Middleware\XFrameOptions;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class XFrameOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testWithEmptyConstructor()
    {
        $xFrameOption = new XFrameOptions();
        $this->assertEquals(XFrameOptions::SAMEORIGIN, $xFrameOption->getXFrameOption());
    }

    public function testWithPassedParamToConstructor()
    {
        $xFrameOption = new XFrameOptions(XFrameOptions::DENY);
        $this->assertEquals(XFrameOptions::DENY, $xFrameOption->getXFrameOption());
    }

    public function testMiddlewareFunctionalityWithoutXframeOptionsHeader()
    {
        $middleware = new XFrameOptions();
        $request = ServerRequestFactory::fromGlobals();
        $response = new Response();

        /** @var Response $response */
        $response = $middleware($request, $response, function($request, $response){
            return $response;
        });

        $this->assertEquals($response->getHeader(XFrameOptions::X_FRAME_OPTIONS)[0], XFrameOptions::SAMEORIGIN);
    }

    public function testMiddlewareFunctionalityWithXFrameOptionsHeader()
    {
        $middleware = new XFrameOptions();
        $request = ServerRequestFactory::fromGlobals();
        $response = new Response();
        $response = $response->withAddedHeader(XFrameOptions::X_FRAME_OPTIONS, 'abc');

        /** @var Response $response */
        $response = $middleware($request, $response, function($request, $response){
            return $response;
        });

        $this->assertEquals($response->getHeader(XFrameOptions::X_FRAME_OPTIONS)[0], 'abc');
    }
}