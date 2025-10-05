<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\player\data;

use ChernegaSergiy\AdvancedInventory\utils\InventorySerializer;
use pocketmine\player\Player;

class ArmorInventoryData implements SavableData {

    private array $inventoryContents = [];
    private static ?InventorySerializer $serializer = null;

    public function __construct() {
        if (self::$serializer === null) {
            self::$serializer = new InventorySerializer();
        }
    }

    public function load(Player $player): void {
        $this->inventoryContents = $player->getArmorInventory()->getContents(true);
    }

    public function applyTo(Player $player): void {
        $player->getArmorInventory()->setContents($this->inventoryContents);
    }

    public function serialize(): array {
        $serialized = self::$serializer->write($this->inventoryContents);
        return $serialized ? [$serialized] : [];
    }

    public function deserialize(array $data): void {
        if (empty($data)) {
            $this->inventoryContents = [];
            return;
        }
        $this->inventoryContents = self::$serializer->read($data[0]);
    }
}
