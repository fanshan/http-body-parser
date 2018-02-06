<?php

namespace ObjectivePHP\Http\BodyParser\Parser;

/**
 * Class ParserFactory
 * @package ObjectivePHP\Http\BodyParser\Parser
 */
class ParserFactory
{
    /**
     * @var array
     */
    protected static $authorized = [
        'json', 'xml'
    ];

    /**
     * @param $type
     * @return string
     * @throws \Exception
     */
    protected static function getClass($type)
    {
        if (in_array(strtolower($type), self::$authorized)) {
            return ucfirst("ObjectivePHP\Http\BodyParser\Parser\\".$type.'Parser');
        }

        throw new \Exception('Parser type doesnt exists');
    }

    /**
     * @param $type
     * @return ParserDecorator
     */
    public static function create($type)
    {
        $class  = self::getClass($type);
        $parser = new $class();
        $parser = new ParserDecorator($parser);

        return $parser;

    }
}