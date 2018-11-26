<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 15.08.2017
 * Time: 9:28
 */

namespace zrk4939\modules\files\helpers;


use yii\base\Object;
use zrk4939\modules\files\models\File;

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

    public static function getVideoCodecs($mime)
    {
        switch ($mime) {
            case 'video/mp4': {
                return "avc1.42E01E, mp4a.40.2";
            }
            case 'video/webm': {
                return "vp8, vorbis";
            }
            case 'video/ogv': {
                return "theora, vorbis";
            }
        }

        return null;
    }
}