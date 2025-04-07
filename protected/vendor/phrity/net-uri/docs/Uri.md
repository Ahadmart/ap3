# Uri class

## Introduction

The Uri class represents a [Uniform Resource Identifier](https://en.wikipedia.org/wiki/Uniform_Resource_Identifier)
used to identify an abstract or physical resource.
The class is fully compatible with the [PSR-7 UriInterface](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface).

## Class synopsis

```php
class Phrity\Net\Uri implements JsonSerializable, Stringable, Psr\Http\Message\UriInterface
{
    // Constructor

    public function __construct(string $uri_string = '');

    // PSR-7 methods

    public function getScheme(int $flags = 0): string;
    public function withScheme(string $scheme, int $flags = 0): Psr\Http\Message\UriInterface;

    public function getAuthority(int $flags = 0): string;

    public function getUserInfo(int $flags = 0): string;
    public function withUserInfo(string $user, string|null $password = null, int $flags = 0): Psr\Http\Message\UriInterface;

    public function getHost(int $flags = 0): string;
    public function withHost(string $host, int $flags = 0): Psr\Http\Message\UriInterface;

    public function getPort(int $flags = 0): int|null;
    public function withPort(int|null $port, int $flags = 0): Psr\Http\Message\UriInterface;

    public function getPath(int $flags = 0): string;
    public function withPath(string $path, int $flags = 0): Psr\Http\Message\UriInterface;

    public function getQuery(int $flags = 0): string;
    public function withQuery(string $query, int $flags = 0): Psr\Http\Message\UriInterface;

    public function getFragment(int $flags = 0): string;
    public function withFragment(string $fragment, int $flags = 0): Psr\Http\Message\UriInterface;

    // Stringable and JsonSerializable methods

    public function __toString(): string;
    public function jsonSerialize(): string;

    // Extension: Component methods

    public function getComponents(int $flags = 0): array;
    public function withComponents(array $components, int $flags = 0): Psr\Http\Message\UriInterface;

    // Extension: Query methods

    public function getQueryItems(int $flags = 0): array;
    public function getQueryItem(string $name, int $flags = 0): array|string|null;
    public function withQueryItems(array $items, int $flags = 0): Psr\Http\Message\UriInterface;
    public function withQueryItem(string $name, array|string|null $value, int $flags = 0): Psr\Http\Message\UriInterface;

    // Extension: String method

    public function toString(int $flags = 0): string;

}
```

## PSR-7 component methods

These methods are compatible with the [PSR-7 UriInterface](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface).

### Scheme

This library is not restricted to certain schemes, but allow all schemes in correct format.

```php
$uri = new Uri('my-own-scheme:');
echo "{$uri} \n"; // -> "my-own-scheme:"

$uri = new Uri('this is not allowed as scheme:');
echo "{$uri} \n"; // -> InvalidArgumentException
```

#### `getScheme(int $flags = 0): string`

Method return scheme, or empty string if not set.

```php
$uri = new Uri('http://example.com');
$uri->getScheme(); // -> "http"

$uri = new Uri();
$uri->getScheme(); // -> ""
```

#### `withScheme(string $scheme, int $flags = 0): UriInterface`

Method return a new Uri instance with specified scheme set.

```php
$uri = new Uri('http://example.com');
$clone = $uri->withScheme('https');
$clone->getScheme(); // -> "https"
echo "{$clone} \n"; // -> "https://example.com"
```

### Host

Host is the domain part, as DNS name or IP-number.

```php
$uri = new Uri('domain.tld');
echo "{$uri} \n"; // -> "domain.tld"

$uri = new Uri('127.0.0.1');
echo "{$uri} \n"; // -> "127.0.0.1"
```

#### `getHost(int $flags = 0): string`

Method return host, or empty string if not set.

```php
$uri = new Uri('domain.tld');
$uri->getHost(); // -> "domain.tld"

$uri = new Uri();
$uri->getHost(); // -> ""
```

#### `withHost(string $host, int $flags = 0): UriInterface`

Method return a new Uri instance with specified host set.

```php
$uri = new Uri('domain.tld');
$clone = $uri->withHost('new-host.com');
$clone->getHost(); // -> "new-host.com"
echo "{$clone} \n"; // -> "//new-host.com/domain.tld"
```

### Port

Port componenet of URI. If default port is used, it is typically hidden.

```php
$uri = new Uri('http://domain.tld:80');
echo "{$uri} \n"; // -> "http://domain.tld"

$uri = new Uri('http://domain.tld:1234');
echo "{$uri} \n"; // -> "http://domain.tld:1234"
```

#### `getPort(int $flags = 0): int|null`

Method return port, or `null` if using default port or port is not set.

```php
$uri = new Uri('http://domain.tld:80');
$uri->getPort(); // -> null

$uri = new Uri('http://domain.tld:1234');
$uri->getPort(); // -> 1234
```

#### `withPort(int|null $port, int $flags = 0): UriInterface`

Method return a new Uri instance with specified port set.

```php
$uri = new Uri('http://domain.tld:80');
$clone = $uri->withPort(1234);
$clone->getPort(); // -> 1234
echo "{$clone} \n"; // -> "http://domain.tld:1234"
$clone = $uri->withPort(null);
$clone->getPort(); // -> null
echo "{$clone} \n"; // -> "http://domain.tld"
```

### Path

Path component of Uri.

```php
$uri = new Uri('http://domain.tld/path/to/file');
echo "{$uri} \n"; // -> "http://domain.tld/path/to/file"

$uri = new Uri('path/to/file');
echo "{$uri} \n"; // -> "path/to/file"
```

#### `getPath(int $flags = 0): string`

Method return path, or empty string if not set.

```php
$uri = new Uri('http://domain.tld/path/to/file');
$uri->getPath(); // -> "path/to/file"

$uri = new Uri('path/to/file');
$uri->getPath(); // -> "path/to/file"
```

#### `withPath(string $path, int $flags = 0): UriInterface`

Method return a new Uri instance with specified path set.

```php
$uri = new Uri('http://domain.tld/path/to/file');
$clone = $uri->withPath('some/other/path/');
$clone->getPath(); // -> "some/other/path/"
echo "{$clone} \n"; // -> "http://domain.tld/some/other/path/"
```

### Query

Query component of Uri.

```php
$uri = new Uri('http://domain.tld?a=1&b=2');
echo "{$uri} \n"; // -> "http://domain.tld?a=1&b=2"
```

#### `getQuery(int $flags = 0): string`

Method return query, or empty string if not set.

```php
$uri = new Uri('http://domain.tld?a=1&b=2');
$uri->getQuery(); // -> "a=1&b=2"
```

#### `withQuery(string $query, int $flags = 0): UriInterface`

Method return a new Uri instance with specified query set.

```php
$uri = new Uri('http://domain.tld?a=1&b=2');
$clone = $uri->withQuery('c=3&d=4');
$clone->getQuery(); // -> "c=3&d=4"
echo "{$clone} \n"; // -> "http://domain.tld/?c=3&d=4"
```

### Fragment

Fragment component of Uri.

```php
$uri = new Uri('http://domain.tld#my+fragment');
echo "{$uri} \n"; // -> "http://domain.tld#my+fragment"
```

#### `getFragment(int $flags = 0): string`

Method return fragment, or empty string if not set.

```php
$uri = new Uri('http://domain.tld#my+fragment');
$uri->getFragment(); // -> "my+fragment"
```

#### `withFragment(string $fragment, int $flags = 0): UriInterface`

Method return a new Uri instance with specified fragment set.

```php
$uri = new Uri('http://domain.tld#my+fragment');
$clone = $uri->withFragment('new-fragment');
$clone->getFragment(); // -> "new-fragment"
echo "{$clone} \n"; // -> "http://domain.tld#new-fragment"
```

### UserInfo

The user info part of URI may consist of username and password.

```php
$uri = new Uri('https://user:pwd@domain.tld');
echo "{$uri} \n"; // -> "https://user:pwd@domain.tld"

$uri = new Uri('https://:pwd@domain.tld');
echo "{$uri} \n"; // -> "https://user:pwd@domain.tld"
```

#### `getUserInfo(int $flags = 0): string`

Method return user info, or empty string if not set.

```php
$uri = new Uri('https://user:pwd@domain.tld');
echo "{$uri->getUserInfo()} \n"; // -> "user:pwd"
```

#### `withUserInfo(string $user, string|null $password = null, int $flags = 0): UriInterface`

Method return a new Uri instance with specified user info set.

```php
$uri = new Uri('http://example.com');
$clone = $uri->withUserInfo('username');
echo "{$clone->getUserInfo()} \n"; // -> "username"
echo "{$clone} \n"; // -> "http://username@example.com"
$clone = $uri->withUserInfo('username', 'password');
echo "{$clone->getUserInfo()} \n"; // -> "username:password"
echo "{$clone} \n"; // -> "http://username:password@example.com"
```

### Authority

The authority part of URI may consist of host, port, username and password.

#### `getAuthority(int $flags = 0): string`

Method return authority part, or empty string if not set.

```php
$uri = new Uri('http://example.com');
echo "{$uri->getAuthority()} \n"; // -> "example.com"

$uri = new Uri();
echo "{$uri->getAuthority()} \n"; // -> ""

$uri = new Uri('https://user:pwd@domain.tld:1234/path/to/file.html?query=1#fragment');
echo "{$uri->getAuthority()} \n"; // -> "user:pwd@domain.tld:1234"
```

## Method flags

All `get`, `with` and the `toString()` methods accept option flags.

### Host options

#### The `IDN_ENCODE` option

Using `IDN_ENCODE` option will IDN-encode host using non-ASCII characters.
Only available with [Intl extension](https://www.php.net/manual/en/intl.installation.php).

```php
$uri = new Uri('https://ηßöø必Дあ.com');

$uri->getHost(); // -> "ηßöø必Дあ.com"
$uri->toString(); // -> "https://ηßöø必Дあ.com"

$uri->getHost(Uri::IDN_ENCODE); // -> "xn--zca0cg32z7rau82strvd.com"
$uri->toString(Uri::IDN_ENCODE); // -> "https://xn--zca0cg32z7rau82strvd.com"

$clone = $uri->withHost('œüç∂', Uri::IDN_ENCODE);
$clone->getHost(); // -> "xn--7ca5b9p776i"
echo "{$clone} \n"; // -> "https://xn--7ca5b9p776i"
```

#### The `IDN_DECODE` option

Using `IDN_DECODE` option will IDN-decode host previously encoded to ASCII-only characters.
Only available with [Intl extension](https://www.php.net/manual/en/intl.installation.php).

```php
$uri = new Uri('https://xn--zca0cg32z7rau82strvd.com');

$uri->getHost(); // -> "xn--zca0cg32z7rau82strvd.com"
$uri->toString(); // -> "https://xn--zca0cg32z7rau82strvd.com"

$uri->getHost(Uri::IDN_DECODE); // -> "ηßöø必Дあ.com"
$uri->toString(Uri::IDN_DECODE); // -> "https://xηßöø必Дあ.com"

$clone = $uri->withHost('xn--7ca5b9p776i', Uri::IDN_DECODE);
$clone->getHost(); // -> "œüç∂"
echo "{$clone} \n"; // -> "https://œüç∂"
```

### Port options

#### The `REQUIRE_PORT` option

By PSR-7 standard, if port is default for scheme it will be hidden.
This options will attempt to always show the port.
If not set, it will use default port if resolvable.

Using this option will require port when it normally would be hidden.

```php
$uri = new Uri('http://domain.tld:80');
$uri->getPort(); // -> null
$uri->toString(); // -> "http://domain.tld"
$uri->getPort(Uri::REQUIRE_PORT); // -> 80
$uri->toString(Uri::REQUIRE_PORT); // -> "http://domain.tld:80"

$uri = new Uri('http://domain.tld');
$uri->getPort(Uri::REQUIRE_PORT); // -> 80
$uri->toString(Uri::REQUIRE_PORT); // -> "http://domain.tld:80"
```

### Path options

#### The `ABSOLUTE_PATH` option

Will cause path to use absolute form, i.e. starting with `/`.

```php
$uri = new Uri('path/to/file');
$uri->getPath(); // -> "path/to/file"
$uri->toString(); // -> "path/to/file"
$uri->getPath(Uri::ABSOLUTE_PATH); // -> "/path/to/file"
$uri->toString(Uri::ABSOLUTE_PATH); // -> "/path/to/file"

$clone = $uri->withPath('some/other/path/', Uri::ABSOLUTE_PATH);
$clone->getPath(); // -> "/some/other/path"
$clone->toString(); // -> "/some/other/path"
```

#### The `NORMALIZE_PATH` option

Will attempt to normalize path, e.g. `./a/./path/../to//something` will transform to `a/to/something`.

```php
$uri = new Uri('a/./path/../to//something');
$uri->getPath(); // -> "a/./path/../to//something"
$uri->toString(); // -> "a/./path/../to//something"
$uri->getPath(Uri::NORMALIZE_PATH); // -> "a/to/something"
$uri->toString(Uri::NORMALIZE_PATH); // -> "a/to/something"

$clone = $uri->withPath('path/./somewhere/else/..', Uri::NORMALIZE_PATH);
$clone->getPath(); // -> "path/somewhere/"
$clone->toString(); // -> "path/somewhere/"
```

## Extension methods

These methods are now part of the PSR-7 UriInterface.

### Representation methods

#### `__toString(): string`

The class implements the `Stringable` interface, so it can always be printed as a string.

```php
$uri = new Uri('http://example.com');
echo $uri; // -> "http://example.com"
echo $uri->__toString(); // -> "http://example.com"
```

#### `toString(int $flags = 0): string`

Unlike the regular `__toString()` method, this method accept option flags.

```php
$uri = new Uri('https://ηßöø必Дあ.com/a/./path/../to//something');
echo $uri->__toString(); // -> "https://ηßöø必дあ.com/a/./path/../to//something
echo $uri->toString(Uri::IDN_ENCODE | Uri::REQUIRE_PORT | Uri::NORMALIZE_PATH); // -> "https://xn--zca0cg32z7rau82strvd.com:443/a/to/something"
```

#### `jsonSerialize(): string`

The class implements the `JsonSerializable` interface, so it will output properly when JSON encoded.

```php
$uri = new Uri('http://example.com');
echo $uri->jsonSerialize(); // -> "http://example.com"
echo json_encode($uri); // -> '"http:\/\/example.com"'
```


### Query helper methods

#### `getQueryItems(int $flags = 0): array`

Method will return query items (if existing) as an assoicative array.

```php
$uri = new Uri('http://example.com?a[a1]=1&a[a2]=2&b=3');
$uri->getQueryItems(); // -> ["a" => ["a1" => "1", "a2" => "2"], "b" => "3"]
$uri = new Uri('http://example.com?no-items-here');
$uri->getQueryItems(); // -> ["no-items-here" => ""]
$uri = new Uri('http://example.com');
$uri->getQueryItems(); // -> []
```

#### `getQueryItem(string $name, int $flags = 0): array|string|null`

Method will return named query value, or null if not existing

```php
$uri = new Uri('http://example.com?a[a1]=1&a[a2]=2&b=3');
$uri->getQueryItem('a'); // -> ["a1" => "1", "a2" => "2"]
$uri->getQueryItem('b'); // -> "3"
$uri->getQueryItem('c'); // -> null
```

#### `withQueryItems(array $items, int $flags = 0): UriInterface`

Method return a new Uri instance with specified query items.
The associative array of query items to add will be merged on existing items.
Providing value `null` on an item will remove it.

```php
$uri = new Uri('http://example.com?a[a1]=1&a[a2]=2&b=3&c=4');
$clone = $uri->withQueryItems(['a' => ['a2' => '2+', 'a3' => '3+'], 'b' => '3+', 'c' => null]);
$clone->getQueryItems(); // -> ["a" => ["a1" => "1", "a2" => "2+", "a3" => "3+"], "b" => "3+"]
echo $clone; // -> "http://example.com?a%5Ba1%5D=1&a%5Ba2%5D=2%2B&a%5Ba3%5D=3%2B&b=3%2B"
```

#### `withQueryItem(string $name, array|string|null $value, int $flags = 0): UriInterface`

Method return a new Uri instance with specified query name/value.
The added query item will be merged on existing items.
Providing value `null` on an item will remove it.

```php
$uri = new Uri('http://example.com?a[a1]=1&a[a2]=2&b=3&c=4');
$clone = $uri->withQueryItem('b', '3+');
$clone->getQueryItems(); // -> ["a" => ["a1" => "1", "a2" => "2"], "b" => "3+", "c" => "4"]
echo $clone; // -> "http://example.com?a%5Ba1%5D=1&a%5Ba2%5D=2&b=3%2B&c=4"
```

### Additional methods

#### `getComponents(int $flags = 0): array`

Method will return associative array of URI components.
It corresponds to [parse_url](https://www.php.net/manual/en/function.parse-url) array return.

```php
$uri = new Uri('https://domain.tld:1234/path/to/file.html?query=1');
$uri->getComponents(); // -> ["scheme" => "https", "host" => "domain.tld", "port" => 1234, "path" => "/path/to/file.html", "query" => "query=1"]
```

#### `withComponents(array $components, int $flags = 0): UriInterface`

Method return a new Uri instance with specified components.

```php
$uri = new Uri('http://domain.tld');
$clone = $uri->withComponents['scheme' => 'https', 'path' => 'path/to/file.html', 'query' => 'query=1']);
echo $clone; // -> "https://domain.tld/path/to/file.html?query=1"
```

## Good to konw

### Scheme and default port

If port is not explicitly set, it will use the default port of the new scheme.

```php
$uri = new Uri('http://example.com');
$clone = $uri->withScheme('https');
$clone->getPort(); // -> null
$clone->getPort(Uri::REQUIRE_PORT); // -> 443
```

If port is explicitly set, it will transfer the port regardless of scheme.

```php
$uri = new Uri('http://example.com:80');
$clone = $uri->withScheme('https');
$clone->getPort(); // -> 80
$clone->getPort(Uri::REQUIRE_PORT); // -> 80
```
