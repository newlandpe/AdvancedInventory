<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\utils;

use pocketmine\world\sound\AnvilBreakSound;
use pocketmine\world\sound\AnvilUseSound;
use pocketmine\world\sound\ChestCloseSound;
use pocketmine\world\sound\ChestOpenSound;
use pocketmine\world\sound\EnderChestCloseSound;
use pocketmine\world\sound\EnderChestOpenSound;
use pocketmine\world\sound\ExplodeSound;
use pocketmine\world\sound\GhastShootSound;
use pocketmine\world\sound\NoteSound;
use pocketmine\world\sound\PopSound;
use pocketmine\world\sound\Sound;
use pocketmine\world\sound\XpCollectSound;
use pocketmine\world\sound\XpLevelUpSound;

final class SoundFactory {

    public static function fromString(string $name): ?Sound {
        return match (strtolower($name)) {
            "pop" => new PopSound(),
            "anvil_break" => new AnvilBreakSound(),
            "anvil_use" => new AnvilUseSound(),
            "chest_open" => new ChestOpenSound(),
            "chest_close" => new ChestCloseSound(),
            "ender_chest_open" => new EnderChestOpenSound(),
            "ender_chest_close" => new EnderChestCloseSound(),
            "explode" => new ExplodeSound(),
            "ghast_shoot" => new GhastShootSound(),
            "note" => new NoteSound(0, 1),
            "xp_collect" => new XpCollectSound(),
            "xp_levelup" => new XpLevelUpSound(1),
            default => null,
        };
    }
}
