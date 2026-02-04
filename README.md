# AdvancedInventory

[![Poggit CI](https://poggit.pmmp.io/ci.shield/newlandpe/AdvancedInventory/AdvancedInventory)](https://poggit.pmmp.io/ci/newlandpe/AdvancedInventory/AdvancedInventory)

AdvancedInventory is a plugin designed to handle inventory splitting mechanics in Minecraft: Bedrock Edition servers. It allows for seamless transition between creative/spectator mode and survival/adventure mode while preserving inventory contents.

## Features

- **Per-world inventory separation:** Activate inventory separation in specific worlds.
- **Per-gamemode inventory separation:** Further separate inventories based on gamemode (e.g., creative vs. survival) within enabled worlds.
- **Configurable default world:** Designate a world to hold the 'global' or 'default' inventory.
- **Granular separation:** Independently separate the main inventory, armor, off-hand slot, and player effects.
- **Customizable sounds:** Play a configurable sound upon inventory change.

## Configuration

AdvancedInventory is highly configurable. You can find the configuration file at `plugin_data/AdvancedInventory/config.yml`.

Here is a breakdown of the configuration options:

- `enabled-worlds`: A list of worlds where inventory separation is active. In any world not in this list, the player will have their "default" inventory.
  - **Example:** `["creative-world", "plots"]`

- `use-gamemode-separation-in-worlds`: If `true`, inventories in the `enabled-worlds` will ALSO be separated by gamemode (survival/creative). This enables a "hybrid" mode. If `false`, players in an enabled world will have one inventory for all gamemodes.
  - **Default:** `true`

- `default-world-name`: The name of the world that holds the "default" or "global" inventory. This inventory will be used in all worlds that are not listed in `enabled-worlds`.
  - **Default:** `"world"`

- `separate-inventory`: Whether to enable separated inventories. If `true`, players will have a different inventory based on the world/gamemode rules.
  - **Default:** `true`

- `separate-armor`: Whether to enable separated armor inventories.
  - **Default:** `true`

- `separate-offhand`: Whether to enable separated off-hand inventories.
  - **Default:** `true`

- `separate-effects`: Whether to enable separated player effects (like speed, jump boost, etc.).
  - **Default:** `true`

- `sound-on-change`: The sound to play when the inventory is switched. Leave empty to disable.
  - **Available sounds:** `pop`, `anvil_break`, `anvil_use`, `chest_open`, `chest_close`, `ender_chest_open`, `ender_chest_close`, `explode`, `ghast_shoot`, `note`, `xp_collect`, `xp_levelup`
  - **Default:** `"pop"`

## How it Works

For players, the plugin works automatically. When you move between worlds or change your gamemode, the plugin will save your current inventory and load the correct one for your new context.

For example, if you are in the `survival-world` and then teleport to the `creative-world` (which is in the `enabled-worlds` list), your survival inventory, armor, and effects will be saved, and you will be given a new, empty inventory for creative mode. When you return to the `survival-world`, your original inventory will be restored.

## Installation

1. Download the latest release from the [releases page](https://github.com/newlandpe/AdvancedInventory/releases).
2. Place the downloaded `.phar` file into your server's `plugins` folder.
3. Restart the server. The plugin will create its data folder and a default `config.yml` at `plugin_data/AdvancedInventory/config.yml`.
4. Modify the `config.yml` to suit your needs.
5. Restart the server again to apply the changes.

## Contributing

Contributions are welcome and appreciated! Here's how you can contribute:

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please make sure to update tests as appropriate and adhere to the existing coding style.

### Support

If you encounter any issues or have questions about the AdvancedInventory plugin, please open an issue on the [Issues](https://github.com/newlandpe/AdvancedInventory/issues) page of this repository.

## License

This project is licensed under the CSSM Unlimited License v2.0 (CSSM-ULv2). Please note that this is a custom license. See the [LICENSE](LICENSE) file for details.
