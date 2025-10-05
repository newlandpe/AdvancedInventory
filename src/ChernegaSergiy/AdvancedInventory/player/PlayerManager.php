<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\player;

use ChernegaSergiy\AdvancedInventory\Main;
use ChernegaSergiy\AdvancedInventory\player\data\ArmorInventoryData;
use ChernegaSergiy\AdvancedInventory\player\data\EffectsData;
use ChernegaSergiy\AdvancedInventory\player\data\OffHandInventoryData;
use ChernegaSergiy\AdvancedInventory\player\data\PlayerInventoryData;
use ChernegaSergiy\AdvancedInventory\player\data\SavableData;
use ChernegaSergiy\AdvancedInventory\task\SavePlayerTask;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class PlayerManager {

    private Main $plugin;
    private Config $inventorySettings;

    /** @var PlayerData[] */
    private array $sessions = [];

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->inventorySettings = $plugin->getInventorySettings();
    }

    public function loadPlayer(Player $player): void {
        $playerSession = new PlayerData();
        $filePath = $this->getPlayerDataPath($player);

        $data = [];
        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            if ($contents) {
                $data = json_decode($contents, true);
            }
        }

        foreach ($data as $key => $serializedData) {
            $savableData = $this->createSavableDataByKey($key);
            if ($savableData !== null) {
                $savableData->deserialize($serializedData);
                $playerSession->setData($key, $savableData);
            }
        }

        $this->sessions[strtolower($player->getName())] = $playerSession;
    }

    public function unloadPlayer(Player $player): void {
        $this->savePlayer($player);
        unset($this->sessions[strtolower($player->getName())]);
    }

    public function getSession(Player $player): ?PlayerData {
        return $this->sessions[strtolower($player->getName())] ?? null;
    }

    public function savePlayer(Player $player, bool $async = true): void {
        $session = $this->getSession($player);
        if ($session === null) {
            return;
        }

        $serializedData = [];
        foreach ($session->getAllData() as $key => $savableData) {
            $serializedData[$key] = $savableData->serialize();
        }

        $data = json_encode($serializedData, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_SUBSTITUTE);

        if ($async) {
            $this->plugin->getServer()->getAsyncPool()->submitTask(new SavePlayerTask($player->getName(), $this->plugin->getDataFolder(), $data));
        } else {
            file_put_contents($this->getPlayerDataPath($player), $data);
        }
    }

    public function getEffectiveWorldName(string $currentWorldName): string {
        $enabledWorlds = $this->inventorySettings->get("enabled-worlds", []);
        if (in_array($currentWorldName, $enabledWorlds, true)) {
            return $currentWorldName;
        }
        return $this->inventorySettings->get("default-world-name", "world");
    }

    public function getGamemodeStringFor(Player $player, string $worldName): string {
        $effectiveWorldName = $this->getEffectiveWorldName($worldName);
        $enabledWorlds = $this->inventorySettings->get("enabled-worlds", []);

        // If we are not in a special world OR if gamemode separation is off for special worlds, use a default key.
        if (!in_array($effectiveWorldName, $enabledWorlds, true) || !$this->inventorySettings->get("use-gamemode-separation-in-worlds", true)) {
            return "default";
        }

        return strtolower($player->getGamemode()->getEnglishName());
    }

    private function getPlayerDataPath(Player $player): string {
        return $this->plugin->getDataFolder() . "players/" . strtolower($player->getName()) . ".json";
    }

    private function createSavableDataByKey(string $key): ?SavableData {
        $parts = explode("_", $key);
        $type = end($parts);

        return match ($type) {
            'inventory' => new PlayerInventoryData(),
            'armor' => new ArmorInventoryData(),
            'offhand' => new OffHandInventoryData(),
            'effects' => new EffectsData(),
            default => null,
        };
    }
}
