<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\player\data;

use pocketmine\player\Player;

interface SavableData {

    /**
     * Loads data from a player object.
     * @param Player $player
     */
    public function load(Player $player): void;

    /**
     * Applies the stored data to a player object.
     * @param Player $player
     */
    public function applyTo(Player $player): void;

    /**
     * Serializes the internal data to an array for saving.
     * @return array
     */
    public function serialize(): array;

    /**
     * Deserializes data from an array.
     * @param array $data
     */
    public function deserialize(array $data): void;
}
