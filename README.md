# yii2-files-module

Files manager module for the [Yii2](http://www.yiiframework.ru/) framework

## Installation

add
```
"zrk4939/files-module": "@dev",
```
to the require section of your `composer.json` file.

and
```
{
    "type": "vcs",
    "url": "https://github.com/zrk4939/files-module.git"
}
```
to the repositories array of your `composer.json` file.

## Usage

### main.php

```php
 'files' => [
    'class' => 'zrk4939\modules\files\FilesModule',
    'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt', 'zip']
 ],
```

### i18n

```php
'files*' => [
    'class' => 'yii\i18n\PhpMessageSource',
    'sourceLanguage' => 'en-US',
    'basePath' => "@zrk4939/modules/files/messages",
    'fileMap' => [
        'files' => 'translation.php',
    ]
],
```