<?php
/**
 * Created by PhpStorm.
 * User: Илья
 * Date: 28.04.2017
 * Time: 16:04
 */

namespace zrk4939\modules\files\widgets\plupload;

class Plupload extends \boundstate\plupload\Plupload
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $variableName = 'uploader' . $this->browseOptions['id'];
        // fix click browse btn when his not visible (in bootstrap tabs)
        $this->view->registerJs("$('a[data-toggle=\"tab\"]').on('shown.bs.tab', function (e) { {$variableName}.refresh(); })");
    }
}