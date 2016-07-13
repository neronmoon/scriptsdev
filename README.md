# scriptsdev for [Composer](https://github.com/composer/composer)
It's like require-dev, but for scripts
## Installation 
Just run `composer require neronmoon/scriptsdev --dev`

## Usage
After installing you able to add scripts-dev directive in your ```composer.json```
```json
...
"scripts-dev": {
	"post-install-cmd": [
		"npm install --dev"
	],
	"post-update-cmd": "php ./someCoolCommand.php"
},
...
```
