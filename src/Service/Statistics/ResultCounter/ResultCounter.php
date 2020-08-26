<?php

namespace App\Service\Statistics\ResultCounter;

/**
 * Interface ResultCounter
 * Gather statistics during product import.
 *
 * @package App\Service\Statistics\ResultCounter
 */
interface ResultCounter
{
    /**
     * Get rows created.
     *
     * @return int
     */
    public function getRowsCreated(): int;

    /**
     * Increments number of created rows.
     *
     * @return void
     */
    public function incrementRowsCreated(): void;

    /**
     * Get rows updated.
     *
     * @return int
     */
    public function getRowsUpdated(): int;

    /**
     * Increments number of updated rows.
     *
     * @return void
     */
    public function incrementRowsUpdated(): void;

    /**
     * Get rows skipped.
     *
     * @return int
     */
    public function getRowsSkipped(): int;

    /**
     * Increments number of skipped rows.
     *
     * @return void
     */
    public function incrementRowsSkipped(): void;
}