<?php

namespace Tests\ObjectivePHP\Http\BodyParser\Parser;

use GuzzleHttp\Psr7\BufferStream;
use GuzzleHttp\Psr7\ServerRequest;
use ObjectivePHP\Http\BodyParser\Exception\ParseException;
use ObjectivePHP\Http\BodyParser\Parser\XmlParser;
use PHPUnit\Framework\TestCase;

/**
 * Class XmlParser
 *
 * @package Tests\ObjectivePHP\Http\BodyParser\Parsers
 */
class XmlParserTest extends TestCase
{
    public function testParserHandleRequest()
    {
        $parser = new XmlParser();

        $request = new ServerRequest('POST', 'test', ['Content-Type' => 'application/rss+xml']);

        $this->assertTrue($parser->doesHandle($request));
    }

    public function testParser()
    {
        $stream = new BufferStream();
        $stream->write('<xml>test</xml>');

        $parser = new XmlParser();

        $this->assertEquals("<?xml version=\"1.0\"?>\n<xml>test</xml>\n", $parser->parse($stream)->saveXML());
    }

    public function testParserThatFail()
    {
        $stream = new BufferStream();
        $stream->write('not xml');

        $parser = new XmlParser();

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage(
            <<<MSG
XML parser error: DOMDocument::loadXML(): Start tag expected, '<' not found in Entity, line: 1
MSG
        );

        $parser->parse($stream);
    }

    public function testThatEmptyStreamReturnNull()
    {
        $stream = new BufferStream();
        $stream->write('');

        $parser = new XmlParser();

        $this->assertNull($parser->parse($stream));
    }
}
