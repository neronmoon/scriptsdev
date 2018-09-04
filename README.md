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
        "post-update-cmd": "php ./someCoolCommand.php"
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
