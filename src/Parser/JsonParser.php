<?php

namespace ObjectivePHP\Http\BodyParser\Parser;

use ObjectivePHP\Http\BodyParser\AbstractParser;
use ObjectivePHP\Http\BodyParser\Exception\ParseException;
use Psr\Http\Message\StreamInterface;

/**
 * Class JsonParser
 *
 * @package ObjectivePHP\Http\BodyParser\Parser
 */
class JsonParser extends AbstractParser
{
    /**
     * @var string[]
     */
    protected $contentTypes = ['application/json', 'application\/((.*)?\+)?json'];

    /**
     * {@inheritdoc}
     */
    public function parse(StreamInterface $stream)
    {
        $decoded = json_decode($stream->getContents(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ParseException('JSON parser error: ' . json_last_error_msg(), json_last_error());
        }

        return $decoded;
    }
}
