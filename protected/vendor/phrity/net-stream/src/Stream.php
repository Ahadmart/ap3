<?php

namespace Phrity\Net;

use InvalidArgumentException;
use Phrity\Util\ErrorHandler;
use Psr\Http\Message\StreamInterface;
use Stringable;
use Throwable;

/**
 * Phrity\Net\Stream class.
 * @see https://www.php-fig.org/psr/psr-7/#34-psrhttpmessagestreaminterface
*/
class Stream implements StreamInterface, Stringable
{
    private static $readmodes = ['r', 'r+', 'w+', 'a+', 'x+', 'c+'];
    private static $writemodes = ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+'];

    protected $stream;
    protected $handler;
    protected $readable = false;
    protected $writable = false;
    protected $seekable = false;

    /**
     * Create new stream wrapper instance
     * @param resource $stream A stream resource to wrap
     * @throws \InvalidArgumentException If not a valid stream resource
     */
    public function __construct($stream)
    {
        $type = gettype($stream);
        if ($type !== 'resource') {
             throw new InvalidArgumentException("Invalid stream provided; got type '{$type}'.");
        }
        $rtype = get_resource_type($stream);
        if (!in_array($rtype, ['stream', 'persistent stream'])) {
             throw new InvalidArgumentException("Invalid stream provided; got resource type '{$rtype}'.");
        }
        $this->stream = $stream;
        $this->handler = new ErrorHandler();
        $this->evalStream();
    }


    // ---------- PSR-7 methods ---------------------------------------------------------------------------------------

    /**
     * Closes the stream and any underlying resources.
     * @return void
     */
    public function close(): void
    {
        if (isset($this->stream)) {
            fclose($this->stream);
        }
        $this->stream = null;
        $this->evalStream();
    }

    /**
     * Separates any underlying resources from the stream.
     * After the stream has been detached, the stream is in an unusable state.
     * @return resource|null Underlying stream, if any
     */
    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }
        $stream = $this->stream;
        $this->stream = null;
        $this->evalStream();
        return $stream;
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata(string|null $key = null): mixed
    {
        if (!isset($this->stream)) {
            return null;
        }
        $meta = stream_get_meta_data($this->stream);
        if (isset($key)) {
            return array_key_exists($key, $meta) ? $meta[$key] : null;
        }
        return $meta;
    }

    /**
     * Returns the current position of the file read/write pointer
     * @return int Position of the file pointer
     * @throws \StreamException on error.
     */
    public function tell(): int
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        return $this->handler->with(function () {
            return ftell($this->stream);
        }, new StreamException(StreamException::FAIL_TELL));
    }

    /**
     * Returns true if the stream is at the end of the stream.
     * @return bool
     */
    public function eof(): bool
    {
        return empty($this->stream) || feof($this->stream);
    }

    /**
     * Read data from the stream.
     * @param int $length Read up to $length bytes from the object and return them.
     * @return string Returns the data read from the stream, or an empty string.
     * @throws \StreamException if an error occurs.
     */
    public function read(int $length): string
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        if (!$this->readable) {
            throw new StreamException(StreamException::NOT_READABLE);
        }
        return $this->handler->with(function () use ($length) {
            return (string)fread($this->stream, $length);
        }, new StreamException(StreamException::FAIL_READ));
    }

    /**
     * Write data to the stream.
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \StreamException on failure.
     */
    public function write(string $string): int
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        if (!$this->writable) {
            throw new StreamException(StreamException::NOT_WRITABLE);
        }
        return $this->handler->with(function () use ($string) {
            return fwrite($this->stream, $string);
        }, new StreamException(StreamException::FAIL_WRITE));
    }

    /**
     * Get the size of the stream if known.
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize(): int|null
    {
        if (!isset($this->stream)) {
            return null;
        }
        $stats = fstat($this->stream);
        return $stats && array_key_exists('size', $stats) ? $stats['size'] : null;
    }

    /**
     * Returns whether or not the stream is seekable.
     * @return bool
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * Seek to a position in the stream.
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated based on the seek offset.
     * @throws \StreamException on failure.
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        if (!$this->seekable) {
            throw new StreamException(StreamException::NOT_SEEKABLE);
        }
        $result = fseek($this->stream, $offset, $whence);
        if ($result !== 0) {
            throw new StreamException(StreamException::FAIL_SEEK);
        }
    }

    /**
     * Seek to the beginning of the stream.
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Returns whether or not the stream is writable.
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Returns whether or not the stream is readable.
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * Returns the remaining contents in a string
     * @return string
     * @throws \StreamException if unable to read.
     * @throws \StreamException if error occurs while reading.
     */
    public function getContents(): string
    {
        if (!isset($this->stream)) {
            throw new StreamException(StreamException::STREAM_DETACHED);
        }
        if (!$this->readable) {
            throw new StreamException(StreamException::NOT_READABLE);
        }
        return $this->handler->with(function () {
            return stream_get_contents($this->stream);
        }, new StreamException(StreamException::FAIL_CONTENTS));
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     * @return string
     */
    public function __toString(): string
    {
        try {
            if ($this->isSeekable()) {
                $this->rewind();
            }
            return $this->getContents();
        } catch (Throwable $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            return '';
        }
    }


    // ---------- Extended methods ------------------------------------------------------------------------------------

    /**
     * Return underlying resource.
     * @return resource|null.
     */
    public function getResource()
    {
        return $this->stream;
    }


    // ---------- Protected helper methods ----------------------------------------------------------------------------

    /**
     * Evaluate stream state.
     */
    protected function evalStream(): void
    {
        if ($this->stream && $meta = $this->getMetadata()) {
            $mode = substr($meta['mode'], 0, 2);
            $this->readable = in_array($mode, self::$readmodes);
            $this->writable = in_array($mode, self::$writemodes);
            $this->seekable = $meta['seekable'];
            return;
        }
        $this->readable = $this->writable = $this->seekable = false;
    }
}
