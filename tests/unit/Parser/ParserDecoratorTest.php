<?php

namespace Tests\ObjectivePHP\Http\BodyParser\Parser;

use GuzzleHttp\Psr7\BufferStream;
use GuzzleHttp\Psr7\ServerRequest;
use ObjectivePHP\Http\BodyParser\Exception\ParseException;
use ObjectivePHP\Http\BodyParser\Parser\ParserFactory;
use ObjectivePHP\Http\BodyParser\Parser\XmlParser;
use PHPUnit\Framework\TestCase;

/**
 * Class ParserDecoratorTest
 * @package Tests\ObjectivePHP\Http\BodyParser\Parser
 */
class ParserDecoratorTest extends TestCase
{
    public function testParseJson()
    {
        $parser = ParserFactory::create('json');

        $request = new ServerRequest('POST', 'test', ['Content-Type' => 'application/json']);

        $this->assertTrue($parser->doesHandle($request));
    }

    public function testParserJson()
    {
        $stream = new BufferStream();
        $stream->write(json_encode(['test' => 'test']));

        $parser = ParserFactory::create('json');

        $this->assertEquals(['test' => 'test'], $parser->parse($stream));
    }

    public function testParserThatFailJson()
    {
        $stream = new BufferStream();
        $stream->write('not json');

        $parser = ParserFactory::create('json');

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('JSON parser error: Syntax error');

        $parser->parse($stream);
    }

    public function testThatEmptyStreamReturnNullJson()
    {
        $stream = new BufferStream();
        $stream->write('');

        $parser = ParserFactory::create('json');

        $this->assertNull($parser->parse($stream));
    }


    public function testParserXml()
    {
        $stream = new BufferStream();
        $stream->write('<xml>test</xml>');

        $parser = ParserFactory::create('xml');

        $this->assertEquals("<?xml version=\"1.0\"?>\n<xml>test</xml>\n", $parser->parse($stream)->saveXML());
    }

    public function testParserThatFailXml()
    {
        $stream = new BufferStream();
        $stream->write('not xml');

        $parser = ParserFactory::create('xml');

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage(
            <<<MSG
XML parser error: DOMDocument::loadXML(): Start tag expected, '<' not found in Entity, line: 1
MSG
        );

        $parser->parse($stream);
    }

    public function testThatEmptyStreamReturnNullXml()
    {
        $stream = new BufferStream();
        $stream->write('');

        $parser = ParserFactory::create('xml');

        $this->assertNull($parser->parse($stream));
    }

}