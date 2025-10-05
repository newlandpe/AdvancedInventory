<?php

declare(strict_types=1);

namespace ChernegaSergiy\AdvancedInventory\task;

use pocketmine\scheduler\AsyncTask;

class SavePlayerTask extends AsyncTask {

    private string $playerName;
    private string $filePath;
    private string $data;

    public function __construct(string $playerName, string $dataFolder, string $data) {
        $this->playerName = $playerName;
        $this->filePath = $dataFolder . "players/" . strtolower($this->playerName) . ".json";
        $this->data = $data;
    }

    public function onRun(): void {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($this->filePath, $this->data);
    }
}
