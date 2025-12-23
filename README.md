# KSF Amortization - FrontAccounting Module

This is the FrontAccounting module for the KSF Amortization system.

## Installation

```bash
# The module will be installed as a submodule in your FA modules directory
# When activated through FA, it will automatically:
# 1. Run composer install to fetch dependencies
# 2. Create necessary database tables
# 3. Register menu items
```

## Structure

- `hooks.php` - FA module hooks that handle installation and feature registration
- `INSTALL.md` - Detailed installation instructions
- `composer.json` - Dependency configuration (requires KSF Amortization Core)
- `FADataProvider.php` - FA-specific database interaction layer
- `src/` - Additional FA platform code

## Requirements

- FrontAccounting 2.4+
- PHP 7.3+
- Composer

## Development

Tests are located in the main repository and can be run with:
```bash
cd ../.. && composer test-fa
```
