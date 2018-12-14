# scriptsdev for [Composer](https://github.com/composer/composer) [![Build Status](https://travis-ci.org/neronmoon/scriptsdev.svg?branch=master)](https://travis-ci.org/neronmoon/scriptsdev)
It's like require-dev, but for scripts
## Installation
Just run `composer require neronmoon/scriptsdev --dev`

## Usage
After installing you able to add extra.scripts-dev directive in your `composer.json`
```json
...
"extra": {
    "scripts-dev": {
        "post-install-cmd": [
            "npm install --dev"
        ],
        "post-update-cmd": "php ./someCoolCommand.php",
        "test": "phpunit"
    },
}
...
```

## Deprecated Usage
```json
...
"scripts-dev": {
    "post-install-cmd": [
        "npm install --dev"
    ],
    "post-update-cmd": "php ./someCoolCommand.php"
}
...
```

## Known issues

- Usage of this plugin will cause minor warning of validation process.
`composer validate` command will display something like this.
```
./composer.json is valid, but with a few warnings
See https://getcomposer.org/doc/04-schema.md for details on the schema
Description for non-existent script "test" found in "scripts-descriptions"
```
