<?php

namespace App\Service\Statistics;

/**
 * Class Errors
 *
 * @package App\Service\Statistics
 */
class Errors
{
    /**
     * @var int
     */
    private $rowId;

    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $message;

    /**
     * Errors constructor.
     *
     * @param int $rowId
     * @param string $sku
     * @param string $property
     * @param string $message
     */
    public function __construct(int $rowId, string $sku, string $property, string $message)
    {
        $this->rowId = $rowId;
        $this->sku = $sku;
        $this->property = $property;
        $this->message = $message;
    }

    /**
     * Get row id.
     *
     * @return int
     */
    public function getRowId(): int
    {
        return $this->rowId;
    }

    /**
     * Get sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Get property name.
     *
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}