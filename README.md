# Clickjacking Protection Middleware
PSR-7 Middleware that provides clickjacking protection via the X-Frame-Options header.

Middleware that sets the X-Frame-Options HTTP header in HTTP responses. Does not set the header if it's already set.
By default, sets the X-Frame-Options header to 'SAMEORIGIN', meaning the response can only be loaded on a frame within the same site.

Note: older browsers will quietly ignore this header, thus other clickjacking protection techniques should be used if protection in those browsers is required.


##Installation
`composer require mikica/clickjacking-middleware`

##Usage

In Slim 3:

```php
$app->add(new Clickjacking\Middleware\XFrameOptions());

$app->get('/', function ($request, $response, $args) {
    return $response;
});
```
