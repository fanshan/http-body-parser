<?php

namespace ObjectivePHP\Http\BodyParser;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Interface ParserInterface
 *
 * @package ObjectivePHP\Http\BodyParser
 */
interface ParserInterface
{
    /**
     * Respond if the parser handle the request
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function doesHandle(ServerRequestInterface $request): bool;

    /**
     * This method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only. A null value indicates
     * the absence of body content.
     *
     * @param StreamInterface $stream A stream that represent the HTTP request body
     *
     * @return null|array|object The deserialized body parameters, if any. These will typically be an array or object.
     */
    public function parse(StreamInterface $stream);
}
