<?php

namespace ObjectivePHP\Http\BodyParser\Parser;

use ObjectivePHP\Http\BodyParser\AbstractParser;
use ObjectivePHP\Http\BodyParser\Exception\ParseException;
use Psr\Http\Message\StreamInterface;

/**
 * Class XmlParser
 *
 * @package ObjectivePHP\Http\BodyParser\Parser
 */
class XmlParser extends AbstractParser
{
    /**
     * @var string[]
     */
    protected $contentTypes = ['application/xml', 'text/xml', 'application\/((.*)?\+)?xml'];

    /**
     * {@inheritdoc}
     *
     * @return null|\DOMDocument
     */
    public function parse(StreamInterface $stream)
    {
        $doc = new \DOMDocument();

        try {
            $doc->loadXML($stream->getContents());
        } catch (\Throwable $e) {
            throw new ParseException('XML parser error: ' . $e->getMessage(), $e->getCode(), $e);
        }

        return $doc;
    }
}
