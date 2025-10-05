<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory;

use ChernegaSergiy\AdvancedInventory\player\data\ArmorInventoryData;
use ChernegaSergiy\AdvancedInventory\player\data\EffectsData;
use ChernegaSergiy\AdvancedInventory\player\data\OffHandInventoryData;
use ChernegaSergiy\AdvancedInventory\player\data\PlayerInventoryData;
use ChernegaSergiy\AdvancedInventory\player\PlayerManager;
use ChernegaSergiy\AdvancedInventory\utils\SoundFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerTeleportEvent;
use pocketmine\player\Player;

class PlayerListener implements Listener {

    private Main $plugin;
    private PlayerManager $playerManager;

    public function __construct(Main $plugin, PlayerManager $playerManager) {
        $this->plugin = $plugin;
        $this->playerManager = $playerManager;
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $this->playerManager->loadPlayer($event->getPlayer());
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $this->playerManager->unloadPlayer($event->getPlayer());
    }

    public function onPlayerGameModeChange(PlayerGameModeChangeEvent $event): void {
        $player = $event->getPlayer();
        $worldName = $player->getWorld()->getFolderName();
        $settings = $this->plugin->getInventorySettings();

        $enabledWorlds = $settings->get("enabled-worlds", []);
        $useGamemodeSeparation = $settings->get("use-gamemode-separation-in-worlds", true);

        // Only trigger swap if we are in a world that uses gamemode separation
        if (in_array($worldName, $enabledWorlds, true) && $useGamemodeSeparation) {
            $fromGamemode = strtolower($event->getOldGameMode()->getEnglishName());
            $toGamemode = strtolower($event->getNewGameMode()->getEnglishName());
            $this->swapAllData($player, $worldName, $fromGamemode, $worldName, $toGamemode);
        }
    }

    public function onPlayerTeleport(PlayerTeleportEvent $event): void {
        $player = $event->getPlayer();
        $fromWorld = $event->getOrigin()->getWorld()->getFolderName();
        $toWorld = $event->getTarget()->getWorld()->getFolderName();

        if ($fromWorld === $toWorld) {
            return;
        }

        $effectiveFromWorld = $this->playerManager->getEffectiveWorldName($fromWorld);
        $effectiveToWorld = $this->playerManager->getEffectiveWorldName($toWorld);

        // Only swap if the effective world is changing
        if ($effectiveFromWorld !== $effectiveToWorld) {
            $fromGamemode = $this->playerManager->getGamemodeStringFor($player, $fromWorld);
            $toGamemode = $this->playerManager->getGamemodeStringFor($player, $toWorld);
            $this->swapAllData($player, $effectiveFromWorld, $fromGamemode, $effectiveToWorld, $toGamemode);
        }
    }

    private function swapAllData(Player $player, string $fromWorld, string $fromGamemode, string $toWorld, string $toGamemode): void {
        $session = $this->playerManager->getSession($player);
        if ($session === null) {
            return;
        }

        $settings = $this->plugin->getInventorySettings();

        $dataMappings = [
            "inventory" => ["class" => PlayerInventoryData::class, "clear" => fn(Player $p) => $p->getInventory()->clearAll()],
            "armor" => ["class" => ArmorInventoryData::class, "clear" => fn(Player $p) => $p->getArmorInventory()->clearAll()],
            "offhand" => ["class" => OffHandInventoryData::class, "clear" => fn(Player $p) => $p->getOffHandInventory()->clearAll()],
            "effects" => ["class" => EffectsData::class, "clear" => fn(Player $p) => $p->getEffects()->clear()],
        ];

        foreach ($dataMappings as $type => $map) {
            // Check if separation for this specific data type is enabled globally
            if (!$settings->get("separate-" . $type, true)) {
                continue;
            }

            $fromKey = "{$fromWorld}_{$fromGamemode}_{$type}";
            $toKey = "{$toWorld}_{$toGamemode}_{$type}";

            // Save current data
            /** @var \ChernegaSergiy\AdvancedInventory\player\data\SavableData $currentData */
            $currentData = new $map['class']();
            $currentData->load($player);
            $session->setData($fromKey, $currentData);

            // Clear this specific part of the player's data
            $map['clear']($player);

            // Load new data
            $newData = $session->getData($toKey);
            if ($newData !== null) {
                $newData->applyTo($player);
            }
        }

        $this->playerManager->savePlayer($player);

        $soundName = (string)$settings->get("sound-on-change", "pop");
        if ($soundName !== "") {
            $sound = SoundFactory::fromString($soundName);
            if ($sound !== null) {
                $player->broadcastSound($sound, [$player]);
            }
        }
    }
}
