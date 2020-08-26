<?php

namespace App\Service\Statistics\ResultCounter;

/**
 * Class Service
 * Gather statistics during product import.
 *
 * @package App\Service\Statistics\ResultCounter
 */
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

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->rowsCreated = 0;
        $this->rowsUpdated = 0;
        $this->rowsSkipped = 0;
    }

    /**
     * Get rows created.
     *
     * @return int
     */
    public function getRowsCreated(): int
    {
        return $this->rowsCreated;
    }

    /**
     * Increments number of created rows.
     *
     * @return void
     */
    public function incrementRowsCreated(): void
    {
        $this->rowsCreated++;
    }

    /**
     * Get rows updated.
     *
     * @return int
     */
    public function getRowsUpdated(): int
    {
        return $this->rowsUpdated;
    }

    /**
     * Increments number of updated rows.
     *
     * @return void
     */
    public function incrementRowsUpdated(): void
    {
        $this->rowsUpdated++;
    }

    /**
     * Get rows skipped.
     *
     * @return int
     */
    public function getRowsSkipped(): int
    {
        return $this->rowsSkipped;
    }

    /**
     * Increments number of skipped rows.
     *
     * @return void
     */
    public function incrementRowsSkipped(): void
    {
        $this->rowsSkipped++;
    }
}