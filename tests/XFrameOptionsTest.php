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
        $this->assertSame(XFrameOptions::DENY, $xFrameOption->getXFrameOption());
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

        $this->assertSame([XFrameOptions::SAMEORIGIN], $response->getHeader(XFrameOptions::X_FRAME_OPTIONS));
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

        $this->assertSame(['abc'], $response->getHeader(XFrameOptions::X_FRAME_OPTIONS));
    }
}
