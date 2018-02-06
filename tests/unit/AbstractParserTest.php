<?php

namespace Tests\ObjectivePHP\Http\BodyParser\Parser;

use GuzzleHttp\Psr7\ServerRequest;
use ObjectivePHP\Http\BodyParser\AbstractParser;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class AbstractParserTest extends TestCase
{
    public function testDefaultContentType()
    {
        $parser = new class extends AbstractParser {
            protected $contentTypes = ['test', 'test'];

            public function parse(StreamInterface $stream)
            {
            }
        };

        $this->assertEquals(['test', 'test'], $parser->getContentTypes());
    }

    public function testContentTypeAccessor()
    {
        $parser = new class extends AbstractParser {
            public function parse(StreamInterface $stream)
            {
            }
        };

        $this->assertEmpty($parser->getContentTypes());

        $parser->setContentTypes(['test']);

        $this->assertEquals(['test'], $parser->getContentTypes());
        $this->assertAttributeEquals(['test'], 'contentTypes', $parser);

        $parser->addContentType('test');

        $this->assertEquals(['test', 'test'], $parser->getContentTypes());
    }

    public function testParserHandleContentType()
    {
        $parser = new class extends AbstractParser {
            public function parse(StreamInterface $stream)
            {
            }
        };

        $this->assertEmpty($parser->getContentTypes());

        $parser->addContentType('test');

        $this->assertTrue($parser->doesHandle(new ServerRequest('POST', 'test', ['Content-Type' => 'test'])));
    }

    public function testParserNotHandleContentType()
    {
        $parser = new class extends AbstractParser {
            public function parse(StreamInterface $stream)
            {
            }
        };

        $this->assertEmpty($parser->getContentTypes());

        $parser->addContentType('test');

        $this->assertFalse($parser->doesHandle(new ServerRequest('POST', 'test', ['Content-Type' => 'no work'])));
    }

    public function testParserHandleContentTypeWithRegExp()
    {
        $parser = new class extends AbstractParser {
            public function parse(StreamInterface $stream)
            {
            }
        };

        $this->assertEmpty($parser->getContentTypes());

        $parser->addContentType('application/xml', 'text/xml', 'application\/((.*)?\+)?xml');

        $this->assertTrue(
            $parser->doesHandle(new ServerRequest('POST', 'test', ['Content-Type' => 'application/xml']))
        );
        $this->assertTrue(
            $parser->doesHandle(new ServerRequest('POST', 'test', ['Content-Type' => 'text/xml']))
        );
        $this->assertTrue(
            $parser->doesHandle(new ServerRequest('POST', 'test', ['Content-Type' => 'application/rss+xml']))
        );
    }
}
