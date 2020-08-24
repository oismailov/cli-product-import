<?php

namespace App\Service\Statistics;

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
    private $message;

    public function __construct(int $rowId, string $property, string $message)
    {
        $this->rowId = $rowId;
        $this->property = $property;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getRowId(): int
    {
        return $this->rowId;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}