# UriFactory class

## Constructor

Constructor takes no arguments.

```php
use Phrity\Net\UriFactory;

$factory = new UriFactory();
```

## PSR-7 method

This methods are compatible with the [PSR-17 UriFactoryInterface](https://www.php-fig.org/psr/psr-17/#26-urifactoryinterface).

### Uri creators

#### `createUri(string $uri = ''): UriInterface`

Method return a new Uri instance, empty or bu parsing provided URI string.

```php
$factory = new UriFactory();
$uri = $factory->createUri();
echo "{$uri} \n"; // -> ""
$uri = $factory->createUri('http://example.com');
echo "{$uri} \n"; // -> "http://example.com"
```

#### `createUriFromInterface(UriInterface $uri): UriInterface`

Method return a new Uri instance, based on any class implementing [PSR-7 UriInterface](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface).

```php
$uri_string = 'http://example.com';
$factory = new UriFactory();
$uri = $factory->createUriFromInterface(new GuzzleHttp\Psr7\Uri($uri_string));
$uri = $factory->createUriFromInterface(new Laminas\Diactoros\Uri($uri_string));
$uri = $factory->createUriFromInterface(League\Uri\Uri::createFromString($uri_string));
$uri = $factory->createUriFromInterface(new Nyholm\Psr7\Uri($uri_string));
$uri = $factory->createUriFromInterface(new Phrity\Net\Uri($uri_string));
$uri = $factory->createUriFromInterface((new Slim\Psr7\Factory\UriFactory)->createUri($uri_string));
```
