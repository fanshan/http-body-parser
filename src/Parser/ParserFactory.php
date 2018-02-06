<?php

namespace ObjectivePHP\Http\BodyParser\Parser;

use ObjectivePHP\Http\BodyParser\AbstractParser;
use ObjectivePHP\Http\BodyParser\Exception\ParseException;

/**
 * Class ParserFactory
 * @package ObjectivePHP\Http\BodyParser\Parser
 */
class ParserFactory
{
    /**
     * @param string $class
     * @param string[] ...$contentTypes
     * @return AbstractParser|ParserDecorator
     * @throws ParseException
     */
    public static function create(string $class, string ...$contentTypes)
    {
        /** @var AbstractParser $parser */
        $parser = new $class();
        if (!$parser instanceof AbstractParser) {
            throw new ParseException("Parser $class isnt define");
        }
        $parser->addContentType(...$contentTypes);
        $parser = new ParserDecorator($parser);

        return $parser;

    }
}