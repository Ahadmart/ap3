<?php

namespace Phrity\Net;

/**
 * Phrity\Net\SocketStream class.
 */
class SocketStream extends Stream
{
    // ---------- Configuration ---------------------------------------------------------------------------------------

    /**
     * If stream is connected.
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->stream && ($this->readable || $this->writable);
    }

    /**
     * Get name of remote socket, or null if not connected.
     * @return string|null
     */
    public function getRemoteName(): string|null
    {
        return stream_socket_get_name($this->stream, true);
    }

    /**
     * Get name of local socket, or null if not connected.
     * @return string|null
     */
    public function getLocalName(): string|null
    {
        return stream_socket_get_name($this->stream, false);
    }

    /**
     * Get type of stream resoucre.
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->stream ? get_resource_type($this->stream) : '';
    }

    /**
     * If stream is in blocking mode.
     * @return bool|null
     */
    public function isBlocking(): bool|null
    {
        return $this->getMetadata('blocked');
    }

    /**
     * Toggle blocking/non-blocking mode.
     * @param bool $enable Blocking mode to set.
     * @return bool If operation was succesful.
     * @throws \StreamException if stream is closed.
     */
    public function setBlocking(bool $enable): bool
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        return stream_set_blocking($this->stream, $enable);
    }

    /**
     * Set timeout period on a stream.
     * @param int $seconds Seconds to be set.
     * @param int $microseconds Microseconds to be set.
     * @return bool If operation was succesful.
     * @throws \StreamException if stream is closed.
     */
    public function setTimeout(int $seconds, int $microseconds = 0): bool
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        return stream_set_timeout($this->stream, $seconds, $microseconds);
    }


    // ---------- Operations ------------------------------------------------------------------------------------------

    /**
     * Read line from the stream.
     * @param int $length Read up to $length bytes from the object and return them.
     * @return string|null Returns the data read from the stream, or null of eof.
     * @throws \StreamException if an error occurs.
     */
    public function readLine(int $length): string|null
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        if (!$this->readable) {
            throw new StreamException(StreamException::NOT_READABLE);
        }
        return $this->handler->with(function () use ($length) {
            $result = fgets($this->stream, $length);
            return $result === false ? null : $result;
        }, new StreamException(StreamException::FAIL_GETS));
    }

    /**
     * Closes the stream for further reading.
     * @return void
     */
    public function closeRead(): void
    {
        if ($this->readable && $this->writable) {
            stream_socket_shutdown($this->stream, STREAM_SHUT_RD);
            $this->evalStream();
        } elseif (!$this->writable) {
            $this->close();
        }
        $this->readable = false;
    }
    /**
     * Closes the stream for further writing.
     * @return void
     */
    public function closeWrite(): void
    {
        if ($this->readable && $this->writable) {
            $x = stream_socket_shutdown($this->stream, STREAM_SHUT_WR);
            $this->evalStream();
        } elseif (!$this->readable) {
            $this->close();
        }
        $this->writable = false;
    }
}
