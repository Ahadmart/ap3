<?php

/**
 * File for Net\Uri class.
 * @package Phrity > Net > Uri
 * @see https://www.rfc-editor.org/rfc/rfc3986
 * @see https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface
 */

namespace Phrity\Net;

use InvalidArgumentException;
use JsonSerializable;
use Psr\Http\Message\UriInterface;
use Stringable;
use TypeError;

/**
 * Net\Uri class.
 */
class Uri implements JsonSerializable, Stringable, UriInterface
{
    public const REQUIRE_PORT = 1; // Always include port, explicit or default
    public const ABSOLUTE_PATH = 2; // Enforce absolute path
    public const NORMALIZE_PATH = 4; // Normalize path
    public const IDNA = 8; // @deprecated, replaced by IDN_ENCODE
    public const IDN_ENCODE = 16; // IDN-encode host
    public const IDN_DECODE = 32; // IDN-decode host

    private const RE_MAIN = '!^(?P<schemec>(?P<scheme>[^:/?#]+):)?(?P<authorityc>//(?P<authority>[^/?#]*))?'
                          . '(?P<path>[^?#]*)(?P<queryc>\?(?P<query>[^#]*))?(?P<fragmentc>#(?P<fragment>.*))?$!';
    private const RE_AUTH = '!^(?P<userinfoc>(?P<user>[^:/?#]+)(?P<passc>:(?P<pass>[^:/?#]+))?@)?'
                          . '(?P<host>[^:/?#]*|\[[^/?#]*\])(?P<portc>:(?P<port>[0-9]*))?$!';

    private static array $port_defaults = [
        'acap' => 674,
        'afp' => 548,
        'dict' => 2628,
        'dns' => 53,
        'ftp' => 21,
        'git' => 9418,
        'gopher' => 70,
        'http' => 80,
        'https' => 443,
        'imap' => 143,
        'ipp' => 631,
        'ipps' => 631,
        'irc' => 194,
        'ircs' => 6697,
        'ldap' => 389,
        'ldaps' => 636,
        'mms' => 1755,
        'msrp' => 2855,
        'mtqp' => 1038,
        'nfs' => 111,
        'nntp' => 119,
        'nntps' => 563,
        'pop' => 110,
        'prospero' => 1525,
        'redis' => 6379,
        'rsync' => 873,
        'rtsp' => 554,
        'rtsps' => 322,
        'rtspu' => 5005,
        'sftp' => 22,
        'smb' => 445,
        'snmp' => 161,
        'ssh' => 22,
        'svn' => 3690,
        'telnet' => 23,
        'ventrilo' => 3784,
        'vnc' => 5900,
        'wais' => 210,
        'ws' => 80,
        'wss' => 443,
    ];

    private string $scheme = '';
    private bool $authority = false;
    private string $host = '';
    private int|null $port = null;
    private string $user = '';
    private string|null $pass = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    /**
     * Create new URI instance using a string
     * @param string $uri_string URI as string
     * @throws \InvalidArgumentException If the given URI cannot be parsed
     */
    public function __construct(string $uri_string = '')
    {
        $this->parse($uri_string);
    }


    // ---------- PSR-7 getters ---------------------------------------------------------------------------------------

    /**
     * Retrieve the scheme component of the URI.
     * @param int $flags Optional modifier flags
     * @return string The URI scheme
     */
    public function getScheme(int $flags = 0): string
    {
        return $this->scheme;
    }

    /**
     * Retrieve the authority component of the URI.
     * @param int $flags Optional modifier flags
     * @return string The URI authority, in "[user-info@]host[:port]" format
     */
    public function getAuthority(int $flags = 0): string
    {
        $host = $this->formatComponent($this->getHost($flags));
        if ($host === '') {
            return '';
        }
        $userinfo = $this->formatComponent($this->getUserInfo(), '', '@');
        $port = $this->formatComponent($this->getPort($flags), ':');
        return "{$userinfo}{$host}{$port}";
    }

    /**
     * Retrieve the user information component of the URI.
     * @param int $flags Optional modifier flags
     * @return string The URI user information, in "username[:password]" format
     */
    public function getUserInfo(int $flags = 0): string
    {
        $user = $this->formatComponent($this->user);
        $pass = $this->formatComponent($this->pass, ':');
        return $user === '' ? '' : "{$user}{$pass}";
    }

    /**
     * Retrieve the host component of the URI.
     * @param int $flags Optional modifier flags
     * @return string The URI host
     */
    public function getHost(int $flags = 0): string
    {
        if ($flags & self::IDNA) {
            trigger_error("Flag IDNA is deprecated; use IDN_ENCODE instead", E_USER_DEPRECATED);
            return $this->idnEncode($this->host);
        }
        if ($flags & self::IDN_ENCODE) {
            return $this->idnEncode($this->host);
        }
        if ($flags & self::IDN_DECODE) {
            return $this->idnDecode($this->host);
        }
        return $this->host;
    }

    /**
     * Retrieve the port component of the URI.
     * @param int $flags Optional modifier flags
     * @return null|int The URI port
     */
    public function getPort(int $flags = 0): int|null
    {
        $default = self::$port_defaults[$this->scheme] ?? null;
        if ($flags & self::REQUIRE_PORT) {
            return $this->port !== null ? $this->port : $default;
        }
        return $this->port === $default ? null : $this->port;
    }

    /**
     * Retrieve the path component of the URI.
     * @param int $flags Optional modifier flags
     * @return string The URI path
     */
    public function getPath(int $flags = 0): string
    {
        $path = $this->path;
        if ($flags & self::NORMALIZE_PATH) {
            $path = $this->normalizePath($path);
        }
        if ($flags & self::ABSOLUTE_PATH && substr($path, 0, 1) !== '/') {
            $path = "/{$path}";
        }
        return $path;
    }

    /**
     * Retrieve the query string of the URI.
     * @param int $flags Optional modifier flags
     * @return string The URI query string
     */
    public function getQuery(int $flags = 0): string
    {
        return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     * @param int $flags Optional modifier flags
     * @return string The URI fragment
     */
    public function getFragment(int $flags = 0): string
    {
        return $this->fragment;
    }


    // ---------- PSR-7 setters ---------------------------------------------------------------------------------------

    /**
     * Return an instance with the specified scheme.
     * @param string $scheme The scheme to use with the new instance
     * @param int $flags Optional modifier flags
     * @return static A new instance with the specified scheme
     * @throws \InvalidArgumentException for invalid schemes
     * @throws \InvalidArgumentException for unsupported schemes
     */
    public function withScheme(string $scheme, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setScheme($scheme, $flags);
        return $clone;
    }

    /**
     * Return an instance with the specified user information.
     * @param string $user The user name to use for authority
     * @param null|string $password The password associated with $user
     * @param int $flags Optional modifier flags
     * @return static A new instance with the specified user information
     */
    public function withUserInfo(string $user, string|null $password = null, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setUserInfo($user, $password);
        return $clone;
    }

    /**
     * Return an instance with the specified host.
     * @param string $host The hostname to use with the new instance
     * @param int $flags Optional modifier flags
     * @return static A new instance with the specified host
     * @throws \InvalidArgumentException for invalid hostnames
     */
    public function withHost(string $host, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setHost($host, $flags);
        return $clone;
    }

    /**
     * Return an instance with the specified port.
     * @param null|int $port The port to use with the new instance
     * @param int $flags Optional modifier flags
     * @return static A new instance with the specified port
     * @throws \InvalidArgumentException for invalid ports
     */
    public function withPort(int|null $port, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setPort($port, $flags);
        return $clone;
    }

    /**
     * Return an instance with the specified path.
     * @param string $path The path to use with the new instance
     * @param int $flags Optional modifier flags
     * @return static A new instance with the specified path
     * @throws \InvalidArgumentException for invalid paths
     */
    public function withPath(string $path, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setPath($path, $flags);
        return $clone;
    }

    /**
     * Return an instance with the specified query string.
     * @param string $query The query string to use with the new instance
     * @param int $flags Optional modifier flags
     * @return static A new instance with the specified query string
     * @throws \InvalidArgumentException for invalid query strings
     */
    public function withQuery(string $query, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setQuery($query, $flags);
        return $clone;
    }

    /**
     * Return an instance with the specified URI fragment.
     * @param string $fragment The fragment to use with the new instance
     * @param int $flags Optional modifier flags
     * @return static A new instance with the specified fragment
     */
    public function withFragment(string $fragment, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setFragment($fragment, $flags);
        return $clone;
    }


    // ---------- PSR-7 string & Stringable ---------------------------------------------------------------------------

    /**
     * Return the string representation as a URI reference.
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }


    // ---------- JsonSerializable ------------------------------------------------------------------------------------

    /**
     * Return JSON encode value as URI reference.
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->toString();
    }


    // ---------- Extensions ------------------------------------------------------------------------------------------

    /**
     * Return the string representation as a URI reference.
     * @param int $flags Optional modifier flags
     * @param tring $format Optional format specification
     * @return string
     */
    public function toString(int $flags = 0, string $format = '{scheme}{authority}{path}{query}{fragment}'): string
    {
        $path_flags = ($this->authority && $this->path ? self::ABSOLUTE_PATH : 0) | $flags;
        return str_replace([
            '{scheme}',
            '{authority}',
            '{path}',
            '{query}',
            '{fragment}',
        ], [
            $this->formatComponent($this->getScheme($flags), '', ':'),
            $this->authority ? "//{$this->formatComponent($this->getAuthority($flags))}" : '',
            $this->formatComponent($this->getPath($path_flags)),
            $this->formatComponent($this->getQuery(), '?'),
            $this->formatComponent($this->getFragment(), '#'),
        ], $format);
    }

    /**
     * Get compontsns as array; as parse_url() method
     * @param int $flags Optional modifier flags
     * @return array
     */
    public function getComponents(int $flags = 0): array
    {
        return array_filter([
            'scheme' => $this->getScheme($flags),
            'host' => $this->getHost($flags),
            'port' => $this->getPort($flags | self::REQUIRE_PORT),
            'user' => $this->user,
            'pass' => $this->pass,
            'path' => $this->getPath($flags),
            'query' => $this->getQuery($flags),
            'fragment' => $this->getFragment($flags),
        ]);
    }

    /**
     * Return an instance with the specified compontents set.
     * @return static A new instance with the specified components
     */
    public function withComponents(array $components, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        foreach ($components as $component => $value) {
            switch ($component) {
                case 'port':
                    $clone->setPort($value, $flags);
                    break;
                case 'scheme':
                    $clone->setScheme($value, $flags);
                    break;
                case 'host':
                    $clone->setHost($value, $flags);
                    break;
                case 'path':
                    $clone->setPath($value, $flags);
                    break;
                case 'query':
                    $clone->setQuery($value, $flags);
                    break;
                case 'fragment':
                    $clone->setFragment($value, $flags);
                    break;
                case 'userInfo':
                    $clone->setUserInfo(...$value);
                    break;
                default:
                    throw new InvalidArgumentException("Invalid URI component: '{$component}'");
            }
        }
        return $clone;
    }

    /**
     * Return all query items (if any) as associative array.
     * @param int $flags Optional modifier flags
     * @return array Query items
     */
    public function getQueryItems(int $flags = 0): array
    {
        parse_str($this->getQuery(), $result);
        return $result;
    }

    /**
     * Return query item value for named query item, or null if not present.
     * @param string $name Name of query item to retrieve
     * @param int $flags Optional modifier flags
     * @return array|string|null Query item value
     */
    public function getQueryItem(string $name, int $flags = 0): array|string|null
    {
        parse_str($this->getQuery(), $result);
        return $result[$name] ?? null;
    }

    /**
     * Add query items as associative array that will be merged qith current items.
     * @param array $items Array of query items to add
     * @param int $flags Optional modifier flags
     * @return static A new instance with the added query items
     */
    public function withQueryItems(array $items, int $flags = 0): UriInterface
    {
        $clone = $this->clone($flags);
        $clone->setQuery(http_build_query(
            $this->queryMerge($this->getQueryItems($flags), $items),
            '',
            null,
            PHP_QUERY_RFC3986
        ), $flags);
        return $clone;
    }

    /**
     * Add query item value for named query item
     * @param string $name Name of query item to add
     * @param array|string|null $value Value of query item to add
     * @param int $flags Optional modifier flags
     * @return static A new instance with the added query items
     */
    public function withQueryItem(string $name, array|string|null $value, int $flags = 0): UriInterface
    {
        return $this->withQueryItems([$name => $value], $flags);
    }


    // ---------- Protected helper methods ----------------------------------------------------------------------------

    protected function setPort(int|null $port, int $flags = 0): void
    {
        if ($port !== null && ($port < 0 || $port > 65535)) {
            throw new InvalidArgumentException("Invalid port '{$port}'");
        }
        $this->port = $port;
    }

    protected function setScheme(string $scheme, int $flags = 0): void
    {
        $pattern = '/^[a-z][a-z0-9-+.]*$/i';
        if ($scheme !== '' && preg_match($pattern, $scheme) == 0) {
            throw new InvalidArgumentException("Invalid scheme '{$scheme}': Should match {$pattern}");
        }
        $this->scheme = mb_strtolower($scheme);
    }

    protected function setHost(string $host, int $flags = 0): void
    {
        $this->authority = $this->authority || $host !== '';
        if ($flags & self::IDNA) {
            trigger_error("Flag IDNA is deprecated; use IDN_ENCODE instead", E_USER_DEPRECATED);
            $host = $this->idnEncode($host);
        }
        if ($flags & self::IDN_ENCODE) {
            $host = $this->idnEncode($host);
        }
        if ($flags & self::IDN_DECODE) {
            $host = $this->idnDecode($host);
        }
        $this->host = mb_strtolower($host);
    }

    protected function setPath(string $path, int $flags = 0): void
    {
        if ($flags & self::NORMALIZE_PATH) {
            $path = $this->normalizePath($path);
        }
        if ($flags & self::ABSOLUTE_PATH && substr($path, 0, 1) !== '/') {
            $path = "/{$path}";
        }
        $this->path = $this->uriEncode($path, $flags);
    }

    protected function setQuery(string $query, int $flags = 0): void
    {
        $this->query = $this->uriEncode($query, $flags, '?');
    }

    protected function setFragment(string $fragment, int $flags = 0): void
    {
        $this->fragment = $this->uriEncode($fragment, $flags, '?');
    }

    protected function setUser(string $user, int $flags = 0): void
    {
        $this->user = $this->uriEncode($user, $flags, '?');
    }

    protected function setPassword(string|null $pass, int $flags = 0): void
    {
        $this->pass = $pass === null ? null : $this->uriEncode($pass, $flags, '?');
    }

    protected function setUserInfo(string $user = '', string|null $pass = null, int $flags = 0): void
    {
        $this->setUser($user);
        $this->setPassword($pass);
    }


    // ---------- Private helper methods ------------------------------------------------------------------------------

    private function parse(string $uri_string = ''): void
    {
        if ($uri_string === '') {
            return;
        }
        preg_match(self::RE_MAIN, $uri_string, $main);
        $this->authority = !empty($main['authorityc']);
        $this->setScheme(isset($main['schemec']) ? $main['scheme'] : '');
        $this->setPath(isset($main['path']) ? $main['path'] : '');
        $this->setQuery(isset($main['queryc']) ? $main['query'] : '');
        $this->setFragment(isset($main['fragmentc']) ? $main['fragment'] : '');
        if ($this->authority) {
            preg_match(self::RE_AUTH, $main['authority'], $auth);
            if (empty($auth) && $main['authority'] !== '') {
                throw new InvalidArgumentException("Invalid 'authority'.");
            }
            if ($auth['host'] === '' && $auth['user'] !== '') {
                throw new InvalidArgumentException("Invalid 'authority'.");
            }
            $this->setUser(isset($auth['user']) ? $auth['user'] : '');
            $this->setPassword(isset($auth['passc']) ? $auth['pass'] : null);
            $this->setHost(isset($auth['host']) ? $auth['host'] : '');
            $this->setPort(isset($auth['portc']) ? (int)$auth['port'] : null);
        }
    }

    private function clone(int $flags = 0): self
    {
        $clone = clone $this;
        if ($flags & self::REQUIRE_PORT) {
            $clone->setPort($this->getPort(self::REQUIRE_PORT), $flags);
        }
        return $clone;
    }

    private function uriEncode(string $source, int $flags = 0, string $keep = ''): string
    {
        $exclude = "[^%\/:=&!\$'()*+,;@{$keep}]+";
        $exp = "/(%{$exclude})|({$exclude})/";
        return preg_replace_callback($exp, function ($matches) {
            if ($e = preg_match('/^(%[0-9a-fA-F]{2})/', $matches[0], $m)) {
                return substr($matches[0], 0, 3) . rawurlencode(substr($matches[0], 3));
            } else {
                return rawurlencode($matches[0]);
            }
        }, $source);
    }

    private function formatComponent(string|int|null $value, string $before = '', string $after = ''): string
    {
        $string = strval($value);
        return $string === '' ? '' : "{$before}{$string}{$after}";
    }

    private function normalizePath(string $path): string
    {
        $result = [];
        preg_match_all('!([^/]*/|[^/]*$)!', $path, $items);
        foreach ($items[0] as $item) {
            switch ($item) {
                case '':
                case './':
                case '.':
                    break; // just skip
                case '/':
                    if (empty($result)) {
                        array_push($result, $item); // add
                    }
                    break;
                case '..':
                case '../':
                    if (empty($result) || end($result) == '../') {
                        array_push($result, $item); // add
                    } else {
                        array_pop($result); // remove previous
                    }
                    break;
                default:
                    array_push($result, $item); // add
            }
        }
        return implode('', $result);
    }

    private function idnEncode(string $value): string
    {
        if ($value === '' || !is_callable('idn_to_ascii')) {
            return $value; // Can't convert, but don't cause exception
        }
        return idn_to_ascii($value, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
    }

    private function idnDecode(string $value): string
    {
        if ($value === '' || !is_callable('idn_to_utf8')) {
            return $value; // Can't convert, but don't cause exception
        }
        return idn_to_utf8($value, IDNA_NONTRANSITIONAL_TO_UNICODE, INTL_IDNA_VARIANT_UTS46);
    }

    private function queryMerge(array $a, array $b): array
    {
        foreach ($b as $key => $value) {
            if (is_int($key)) {
                $a[] = $value;
            } elseif (is_array($value)) {
                $a[$key] = $this->queryMerge($a[$key] ?? [], $b[$key] ?? []);
            } elseif (is_scalar($value)) {
                $a[$key] = rawurldecode($b[$key]);
            } else {
                unset($a[$key]);
            }
        }
        return $a;
    }
}
