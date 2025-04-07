<?php

namespace Phrity\Net;

use Phrity\Util\ErrorHandler;
use Psr\Http\Message\UriInterface;

/**
 * Phrity\Net\SocketClient class.
 */
class SocketClient
{
    protected $uri;
    protected $handler;
    protected $persistent = false;
    protected $timeout = null;
    protected $context = null;

    /**
     * Create new socker server instance
     * @param \Psr\Http\Message\UriInterface $uri The URI to open socket on.
     */
    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
        $this->handler = new ErrorHandler();
    }


    // ---------- Configuration ---------------------------------------------------------------------------------------

    /**
     * Set stream context.
     * @param array|null $options
     * @param array|null $params
     * @return \Phrity\Net\SocketClient
     */
    public function setContext(array|null $options = null, array|null $params = null): self
    {
        $this->context = stream_context_create($options, $params);
        return $this;
    }

    /**
     * Set connection persistency.
     * @param bool $persistent
     * @return \Phrity\Net\SocketClient
     */
    public function setPersistent(bool $persistent): self
    {
        $this->persistent = $persistent;
        return $this;
    }

    /**
     * Set timeout in seconds.
     * @param int|null $timeout
     * @return \Phrity\Net\SocketClient
     */
    public function setTimeout(int|null $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }


    // ---------- Operations ------------------------------------------------------------------------------------------

    /**
     * Create a connection on remote socket.
     * @return \Phrity\Net\SocketStream The stream for opened conenction.
     * @throws StreamException if connection could not be created
     */
    public function connect(): SocketStream
    {
        $stream = $this->handler->with(function () {
            $error_code = $error_message = '';
            return stream_socket_client(
                $this->uri->__toString(),
                $error_code,
                $error_message,
                $this->timeout,
                $this->persistent ? STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT : STREAM_CLIENT_CONNECT,
                $this->context
            );
        }, new StreamException(StreamException::CLIENT_CONNECT_ERR, ['uri' => $this->uri]));
        return new SocketStream($stream);
    }
}
