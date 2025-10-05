<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\utils;

use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNbtSerializer;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\TreeRoot;

class InventorySerializer {

    /** @var BigEndianNbtSerializer $nbtSerializer */
    private BigEndianNbtSerializer $nbtSerializer;

    public function __construct() {
        $this->nbtSerializer = new BigEndianNbtSerializer;
    }

    /**
     * @param string $data
     * @return array
     */
    public function read(string $data): array {
        $contents = [];
        $inventoryTags = $this->nbtSerializer->read(json_decode($data))->mustGetCompoundTag()->getListTag('Inventory');
        /** @var CompoundTag $tag */
        foreach ($inventoryTags as $tag) {
            $contents[$tag->getByte('Slot')] = Item::nbtDeserialize($tag);
        }
        return $contents;
    }

    /**
     * @param array $items
     * @return string|bool
     */
    public function write(array $items): string|bool {
        $contents = [];
        /** @var Item[] $items */
        foreach ($items as $slot => $item) {
            if (!$item->isNull()) {
                $contents[] = $item->nbtSerialize($slot);
            }
        }
        return json_encode($this->nbtSerializer->write(new TreeRoot(CompoundTag::create()
            ->setTag('Inventory', new ListTag($contents, NBT::TAG_Compound))
        )));
    }
}
