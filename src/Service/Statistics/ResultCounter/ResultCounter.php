<?php

namespace App\Service\Statistics\ResultCounter;

interface ResultCounter
{
    public function getRowsCreated(): int;

    public function incrementRowsCreated(): void;

    public function getRowsUpdated(): int;

    public function incrementRowsUpdated(): void;

    public function getRowsSkipped(): int;

    public function incrementRowsSkipped(): void;
}