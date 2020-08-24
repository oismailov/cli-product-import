<?php

namespace App\Service\Statistics\ResultCounter;

class Service implements ResultCounter
{
    /**
     * @var int
     */
    private $rowsCreated;

    /**
     * @var int
     */
    private $rowsUpdated;

    /**
     * @var int
     */
    private $rowsSkipped;

    public function __construct()
    {
        $this->rowsCreated = 0;
        $this->rowsUpdated = 0;
        $this->rowsSkipped = 0;
    }

    public function getRowsCreated(): int
    {
        return $this->rowsCreated;
    }

    public function incrementRowsCreated(): void
    {
        $this->rowsCreated++;
    }

    public function getRowsUpdated(): int
    {
        return $this->rowsUpdated;
    }

    public function incrementRowsUpdated(): void
    {
        $this->rowsUpdated++;
    }

    public function getRowsSkipped(): int
    {
        return $this->rowsSkipped;
    }

    public function incrementRowsSkipped(): void
    {
        $this->rowsSkipped++;
    }
}