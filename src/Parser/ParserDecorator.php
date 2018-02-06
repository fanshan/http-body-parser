<?php

namespace ObjectivePHP\Http\BodyParser\Parser;

use ObjectivePHP\Http\BodyParser\AbstractParser;
use Psr\Http\Message\StreamInterface;

/**
 * Class ParserDecorator
 * @package ObjectivePHP\Http\BodyParser\Parser
 */
class ParserDecorator extends AbstractParser
{
    /**
     * @var AbstractParser
     */
    private $_decorated;

    /**
     * ParserDecorator constructor.
     * @param AbstractParser $pDecorated
     */
    public function __construct(AbstractParser $pDecorated)
    {
        $this->_decorated = $pDecorated;
        parent::__construct(... $pDecorated->getContentTypes());
    }

    /**
     * @param StreamInterface $stream
     * @return null
     */
    public function parse(StreamInterface $stream)
    {
        if ($stream->getSize() == 0) {
            return null;
        }

        return $this->_decorated->parse($stream);
    }

}