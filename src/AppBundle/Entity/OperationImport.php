<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * OperationImport
 *
 * @ApiResource(collectionOperations={ }, itemOperations={
 *     "post"={
 *         "route_name"="operation_import_post"
 *     }
 * })
 */
class OperationImport
{

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $content;

    /**
     * Set format
     *
     * @param string $format
     *
     * @return OperationImport
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return OperationImport
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}

