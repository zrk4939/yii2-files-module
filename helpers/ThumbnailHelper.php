<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 27.02.2017
 * Time: 15:16
 */

namespace zrk4939\modules\files\helpers;


use yii\base\Object;

class ThumbnailHelper extends Object
{
    /**
     * @param string $imagesDir
     * @param string $item
     * @param string $thumb_key
     * @param array $sizes
     */
    public static function createImageThumbnail(string $imagesDir, string $item, string $thumb_key, array $sizes)
    {
        $thumbFileName = $thumb_key . '_' . $item;

        if (!file_exists($imagesDir . $thumbFileName)) {
            self::generateImageThumbnail(
                $imagesDir . $item,
                $imagesDir . $thumbFileName,
                $sizes['width'],
                isset($sizes['height']) ? $sizes['height'] : null,
                isset($sizes['cropAndCenter']) ? $sizes['cropAndCenter'] : null
            );
        }
    }

    /**
     * @param $file string
     * @return mixed
     */
    public static function isImage($file)
    {
        $result = false;
        try {
            if (function_exists('exif_imagetype')) {
                $result = exif_imagetype($file);
            } else {
                $result = getimagesize($file);
            }
        } catch (\Exception $e) {
        }

        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * @param string $source_image_path
     * @param string $thumbnail_image_path
     * @param string $width
     * @param string|null $height
     * @param bool $cropAndCenter
     * @return bool
     */
    protected static function generateImageThumbnail($source_image_path, $thumbnail_image_path, $width, $height = null, $cropAndCenter = true)
    {
        if (!static::isImage($source_image_path)) {
            return false;
        }
        // read image
        list($source_image_width, $source_image_height, $source_gd_image, $source_image_type) = self::readImage($source_image_path);
        if (empty($source_gd_image)) {
            return false;
        }

        /* ----- */

        if (empty($height)) {
            $koe = $source_image_width / $width; // вычисляем коэффициент $width это ширина которая должна быть
            $height = ceil($source_image_height / $koe); // с помощью коэффициента вычисляем высоту
        }

        if ($cropAndCenter) {
            $ratio_orig = $source_image_width / $source_image_height;

            if ($width / $height > $ratio_orig) {
                $new_height = $width / $ratio_orig;
                $new_width = $width;
            } else {
                $new_width = $height * $ratio_orig;
                $new_height = $height;
            }

            $x_mid = $new_width / 2;  //horizontal middle
            $y_mid = $new_height / 2; //vertical middle

            $process = imagecreatetruecolor(round($new_width), round($new_height));

            imagecopyresampled($process, $source_gd_image, 0, 0, 0, 0, $new_width, $new_height, $source_image_width, $source_image_height);
            $thumbnail_gd_image = imagecreatetruecolor($width, $height);
            imagecopyresampled($thumbnail_gd_image, $process, 0, 0, ($x_mid - ($width / 2)), ($y_mid - ($height / 2)), $width, $height, $width, $height);
        } else {
            $source_aspect_ratio = $source_image_width / $source_image_height;
            $thumbnail_aspect_ratio = $width / $height;

            if ($source_image_width <= $width && $source_image_height <= $height) {
                $thumbnail_image_width = $source_image_width;
                $thumbnail_image_height = $source_image_height;
            } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
                $thumbnail_image_width = (int)($height * $source_aspect_ratio);
                $thumbnail_image_height = $height;
            } else {
                $thumbnail_image_width = $width;
                $thumbnail_image_height = (int)($width / $source_aspect_ratio);
            }

            $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
            imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
        }

        $result = imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 85);
        if ($result) {
            chmod($thumbnail_image_path, 0775);
        }
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        return true;
    }

    /**
     * @param string $source_image_path
     * @return array
     */
    protected static function readImage($source_image_path)
    {
        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
        $source_gd_image = null;

        switch ($source_image_type) {
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }

        return [$source_image_width, $source_image_height, $source_gd_image, $source_image_type];
    }
}
