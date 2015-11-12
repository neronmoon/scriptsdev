# scriptsdev for [Composer](https://github.com/composer/composer)
It's like require-dev, but for scripts
## Installation 
Add require string to composer.json

```json
...
"require-dev": {
	"neronmoon/scriptsdev": "dev-master",
}
...
```
And run update
```shell
$ composer update neronmoon/scriptsdev
```
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
