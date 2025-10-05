<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\player;

use ChernegaSergiy\AdvancedInventory\player\data\SavableData;

class PlayerData {

    /** @var SavableData[] */
    private array $dataMap = [];

    public function __construct() {
        // Initialize with empty data objects if needed, or load them dynamically.
    }

    public function getData(string $key): ?SavableData {
        return $this->dataMap[$key] ?? null;
    }

    public function setData(string $key, SavableData $data): void {
        $this->dataMap[$key] = $data;
    }

    public function getAllData(): array {
        return $this->dataMap;
    }
}
