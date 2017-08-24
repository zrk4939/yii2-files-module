<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 15.08.2017
 * Time: 9:28
 */

namespace domain\modules\files\helpers;


use domain\modules\files\models\File;
use yii\base\Object;

class FilesHelper extends Object
{
    const ICON_TYPES = [
        'pdf',
        'excel',
        'word',
        'archive',
        'zip',
        'image',
        'audio',
        'movie',
        'video',
        'text',
    ];

    /**
     * @param File $file
     * @return string
     */
    public static function getFontAwesomeFileIcon(File $file)
    {
        $icon = "fa fa-file-o";

        foreach (self::ICON_TYPES as $iconType) {
            if (preg_match("/{$iconType}/msiu", $file->mime)) {
                return "fa fa-file-{$iconType}-o";
            }
        }

        return $icon;
    }
}