# AdvancedInventory Plugin

## Project Development Progress: Inventory Separation Module

🧱 Development progress on the "Fyennyi Gamemode Survival" project (formerly "NewLand Games") continues unabated. While we occasionally discuss what the project will look like in the future, it's important to report on the progress made and the future prospects of the inventory separation system (AdvancedInventory module).

### Overview

This plugin introduces a crucial mechanic for fair gameplay survival, as we've incorporated the following features:

- When switching game modes to Creative/Spectator, the survival/adventure inventory contents will be saved to the database and only then cleared by setting the content from the previously saved inventory.
- When switching game modes to Survival/Adventure, the contents of the creative inventory (or spectator) will be saved to the database and only then cleared by setting the content from the already saved survival inventory.

### Features Implemented:

1. **Configuration File:** A configuration file has been developed, allowing you to customize the following properties:
   - Full module disablement, if necessary.
   - Separation of main inventory content.
   - Separation of armor inventory area.
   - Separation of offhand.
   - Separation of applied player effects.
   - 🆕 Forced clearing of each inventory part, if necessary.

### Licensing

This project is licensed under the [MIT License](LICENSE), which means you are free to use, modify, and distribute the code as long as you include the original copyright notice and license terms.

### Installation

To install the AdvancedInventory plugin, simply download the latest release from the [Releases](https://github.com/newlandpe/AdvancedInventory/releases) page and place the JAR file into your server's plugins folder.

### Usage

Once installed, configure the plugin according to your preferences using the provided configuration file. Then, start or reload your server to apply the changes.

### Contribution

Contributions to this project are welcome! Feel free to fork this repository, make changes, and submit a pull request.

### Support

If you encounter any issues or have questions about the AdvancedInventory plugin, please open an issue on the [Issues](https://github.com/newlandpe/AdvancedInventory/issues) page of this repository.

---

© 2024 Fyennyi Gamemode Survival Team. All rights reserved.
