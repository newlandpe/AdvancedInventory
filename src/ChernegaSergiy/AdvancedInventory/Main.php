<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory;

use ChernegaSergiy\AdvancedInventory\player\PlayerManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    private Config $inventorySettings;
    private PlayerManager $playerManager;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->inventorySettings = $this->getConfig();
        $this->playerManager = new PlayerManager($this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener($this, $this->playerManager), $this);
    }

    public function getInventorySettings(): Config {
        return $this->inventorySettings;
    }

    public function getPlayerManager(): PlayerManager {
        return $this->playerManager;
    }
}
