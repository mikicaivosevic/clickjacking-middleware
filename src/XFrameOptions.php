<?php
namespace Clickjacking\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class XFrameOptions
{
    const DENY = "DENY";
    const SAMEORIGIN = "SAMEORIGIN";
    const X_FRAME_OPTIONS = 'X-Frame-Options';

    /**
     * @var string
     */
    private $xFrameOption;

    /**
     * @param string $xFrameOption
     */
    public function __construct($xFrameOption = self::SAMEORIGIN)
    {
        $this->xFrameOption = $xFrameOption;
    }

    /**
     *  Middleware that sets the X-Frame-Options HTTP header in HTTP responses.
     *  Does not set the header if it's already set.
     *  By default, sets the X-Frame-Options header to 'SAMEORIGIN', meaning the
     *  response can only be loaded on a frame within the same site.
     *
     * @param ServerRequestInterface $request PSR7 request
     * @param ResponseInterface $response     PSR7 response
     * @param callable $next                  Next middleware
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $response = $next($request, $response);

        if ($response->hasHeader(self::X_FRAME_OPTIONS)) {
            return $response;
        }

        return $response->withAddedHeader(self::X_FRAME_OPTIONS, $this->getXFrameOption());
    }


    /**
     * @return string
     */
    public function getXFrameOption()
    {
        return $this->xFrameOption;
    }

    /**
     * @param string $xFrameOption
     */
    public function setXFrameOption($xFrameOption)
    {
        $this->xFrameOption = $xFrameOption;
    }
}
