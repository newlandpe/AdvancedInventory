<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\player\data;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\player\Player;

class EffectsData implements SavableData {

    /** @var EffectInstance[] */
    private array $effects = [];

    public function load(Player $player): void {
        $this->effects = $player->getEffects()->all();
    }

    public function applyTo(Player $player): void {
        $player->getEffects()->clear();
        foreach ($this->effects as $effect) {
            $player->getEffects()->add(clone $effect);
        }
    }

    public function serialize(): array {
        $serializedEffects = [];
        foreach ($this->effects as $effect) {
            $serializedEffects[] = [
                "id" => EffectIdMap::getInstance()->toId($effect->getType()),
                "duration" => $effect->getDuration(),
                "amplifier" => $effect->getAmplifier(),
                "visible" => $effect->isVisible(),
                "ambient" => $effect->isAmbient(),
                "color" => $effect->getColor()->toARGB()
            ];
        }
        return $serializedEffects;
    }

    public function deserialize(array $data): void {
        $this->effects = [];
        foreach ($data as $serializedEffect) {
            $effectType = EffectIdMap::getInstance()->fromId($serializedEffect['id']);
            if ($effectType === null) continue;

            $effectInstance = (new EffectInstance($effectType))
                ->setDuration($serializedEffect['duration'])
                ->setAmplifier($serializedEffect['amplifier'])
                ->setVisible($serializedEffect['visible'])
                ->setAmbient($serializedEffect['ambient']);

            if (isset($serializedEffect['color'])) {
                $effectInstance->setColor($effectType->getColor()->fromARGB($serializedEffect['color']));
            }
            $this->effects[] = $effectInstance;
        }
    }
}
