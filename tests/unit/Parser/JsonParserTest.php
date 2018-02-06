<?php

namespace Tests\ObjectivePHP\Http\BodyParser\Parser;

use GuzzleHttp\Psr7\BufferStream;
use GuzzleHttp\Psr7\ServerRequest;
use ObjectivePHP\Http\BodyParser\Exception\ParseException;
use ObjectivePHP\Http\BodyParser\Parser\JsonParser;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonParserTest
 *
 * @package Tests\ObjectivePHP\Http\BodyParser
 */
class JsonParserTest extends TestCase
{
    public function testParserHandleRequest()
    {
        $parser = new JsonParser();

        $request = new ServerRequest('POST', 'test', ['Content-Type' => 'application/json']);

        $this->assertTrue($parser->doesHandle($request));
    }

    public function testParser()
    {
        $stream = new BufferStream();
        $stream->write(json_encode(['test' => 'test']));

        $parser = new JsonParser();

        $this->assertEquals(['test' => 'test'], $parser->parse($stream));
    }

    public function testParserThatFail()
    {
        $stream = new BufferStream();
        $stream->write('not json');

        $parser = new JsonParser();

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('JSON parser error: Syntax error');

        $parser->parse($stream);
    }

    public function testThatEmptyStreamReturnNull()
    {
        $stream = new BufferStream();
        $stream->write('');

        $parser = new JsonParser();

        $this->assertNull($parser->parse($stream));
    }
}
