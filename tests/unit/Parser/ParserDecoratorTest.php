<?php

namespace Tests\ObjectivePHP\Http\BodyParser\Parser;

use GuzzleHttp\Psr7\BufferStream;
use GuzzleHttp\Psr7\ServerRequest;
use ObjectivePHP\Http\BodyParser\Exception\ParseException;
use ObjectivePHP\Http\BodyParser\Parser\JsonParser;
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
        $parser = ParserFactory::create(JsonParser::class);

        $request = new ServerRequest('POST', 'test', ['Content-Type' => 'application/json']);

        $this->assertTrue($parser->doesHandle($request));
    }

    public function testParserJson()
    {
        $stream = new BufferStream();
        $stream->write(json_encode(['test' => 'test']));

        $parser = ParserFactory::create(JsonParser::class);

        $this->assertEquals(['test' => 'test'], $parser->parse($stream));
    }

    public function testParserThatFailJson()
    {
        $stream = new BufferStream();
        $stream->write('not json');

        $parser = ParserFactory::create(JsonParser::class);

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('JSON parser error: Syntax error');

        $parser->parse($stream);
    }

    public function testThatEmptyStreamReturnNullJson()
    {
        $stream = new BufferStream();
        $stream->write('');

        $parser = ParserFactory::create(JsonParser::class);

        $this->assertNull($parser->parse($stream));
    }


    public function testParseXml()
    {
        $parser = ParserFactory::create(XmlParser::class);

        $request = new ServerRequest('POST', 'test', ['Content-Type' => 'application/rss+xml']);

        $this->assertTrue($parser->doesHandle($request));
    }


    public function testParserXml()
    {
        $stream = new BufferStream();
        $stream->write('<xml>test</xml>');

        $parser = ParserFactory::create(XmlParser::class);

        $this->assertEquals("<?xml version=\"1.0\"?>\n<xml>test</xml>\n", $parser->parse($stream)->saveXML());
    }

    public function testParserThatFailXml()
    {
        $stream = new BufferStream();
        $stream->write('not xml');

        $parser = ParserFactory::create(XmlParser::class);

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

        $parser = ParserFactory::create(XmlParser::class);

        $this->assertNull($parser->parse($stream));
    }

    public function testNotParser()
    {
        $this->expectException(ParseException::class);
        ParserFactory::create(BrbTest::class);
    }

}

class BrbTest
{

}