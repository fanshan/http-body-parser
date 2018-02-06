<?php

namespace ObjectivePHP\Http\BodyParser;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractParser
 *
 * @package ObjectivePHP\Http\BodyParser
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * @var string[] Content-types handle by the parser
     */
    protected $contentTypes = [];

    /**
     * AbstractParser constructor.
     *
     * @param string[] ...$contentTypes
     */
    public function __construct(string ...$contentTypes)
    {
        $this->setContentTypes($contentTypes);
    }

    /**
     * Get ContentTypes
     *
     * @return string[]
     */
    public function getContentTypes(): array
    {
        return $this->contentTypes;
    }

    /**
     * Set ContentTypes
     *
     * @param string[] $contentTypes
     *
     * @return $this
     */
    public function setContentTypes(array $contentTypes)
    {
        return $this->addContentType(...$contentTypes);
    }

    /**
     * Add a content type handle by the parser
     *
     * @param string[] ...$contentTypes
     *
     * @return $this
     */
    public function addContentType(string ...$contentTypes)
    {
        $this->contentTypes = array_merge($this->getContentTypes(), $contentTypes);

        return $this;
    }

    /**
     * Returns if the parser handle a content-type
     *
     * @param string $contentType
     *
     * @return bool
     */
    public function hasContentType(string $contentType)
    {
        $result = in_array($contentType, $this->getContentTypes(), true);

        if ($result) {
            return $result;
        }

        foreach ($this->getContentTypes() as $pattern) {
            try {
                if (preg_match(sprintf('/%s/', $pattern), $contentType)) {
                    return true;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function doesHandle(ServerRequestInterface $request): bool
    {
        foreach ($request->getHeader('Content-Type') as $contentType) {
            if ($this->hasContentType($contentType)) {
                return true;
            }
        }

        return false;
    }
}
