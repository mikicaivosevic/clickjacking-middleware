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
        $response = $middleware($request, $response, function ($request, $response) {
            return $response;
        });

        $this->assertSame([XFrameOptions::SAMEORIGIN], $response->getHeader(XFrameOptions::X_FRAME_OPTIONS));
    }

    public function testMiddlewareFunctionalityWithXFrameOptionsHeader()
    {
        $middleware = new XFrameOptions();
        $request = ServerRequestFactory::fromGlobals();
        $response = new Response();

        /** @var Response $response */
        $response = $middleware($request, $response, function ($request, $response) {
            return $response->withHeader(XFrameOptions::X_FRAME_OPTIONS, XFrameOptions::DENY);
        });

        $this->assertSame([XFrameOptions::SAMEORIGIN], $response->getHeader(XFrameOptions::X_FRAME_OPTIONS));
    }

    public function testMiddlewareFunctionalityNewResponse()
    {
        $middleware = new XFrameOptions();
        $request = ServerRequestFactory::fromGlobals();
        $response = new Response();

        /** @var Response $response */
        $response = $middleware($request, $response, function ($request, $response) {
            return new Response();
        });

        $this->assertSame([XFrameOptions::SAMEORIGIN], $response->getHeader(XFrameOptions::X_FRAME_OPTIONS));
    }
}
