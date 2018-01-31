<?php
/**
 * Created by PhpStorm.
 * User: zrk4939
 * Date: 31.01.2018
 * Time: 10:42
 */

namespace zrk4939\modules\files\behaviors;


use yii\behaviors\SluggableBehavior;
use yii\helpers\Inflector;

class FileNameBehavior extends SluggableBehavior
{
    public $slugAttribute = 'filename';
    public $ensureUnique = true;

    /**
     * @inheritdoc
     */
    protected function generateSlug($slugParts)
    {
        return Inflector::transliterate(implode('-', $slugParts));
    }

    /**
     * @inheritdoc
     */
    protected function generateUniqueSlug($baseSlug, $iteration)
    {
        if (is_callable($this->uniqueSlugGenerator)) {
            return call_user_func($this->uniqueSlugGenerator, $baseSlug, $iteration, $this->owner);
        }

        $filename = preg_replace('/^(.*?)\.\w+$/msiu', '$1', $baseSlug);
        $ext = preg_replace('/^.*?\.(\w+)$/msiu', '$1', $baseSlug);

        return $filename . '-' . ($iteration + 1) . '.' . $ext;
    }
}
