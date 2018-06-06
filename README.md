# yii2-files-module
# DOES NOT WORK

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
    'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt', 'zip'],
    'uploadPath' => '@webroot/uploads',
    'rootPath' => '@webroot',
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

### Widget

```php
<?php
echo $form->field($model, 'images_arr')->widget(\zrk4939\modules\files\widget\FilesWidget::className(), [
    'multiple' => true,
    'files' => $model->images,
    'types' => [
        'image/*'
    ]
]);
?>
```

### CKEditor options
```php
$imageTypes = Json::encode([
    'image/*'
]);

'editorOptions' => [
    'filebrowserBrowseUrl' => Url::to(['/files/manage/index', 'frame' => 1]),
    'filebrowserImageBrowseUrl' => Url::to(['/files/manage/index', 'frame' => 1, 'types' => $imageTypes]),
],
```
