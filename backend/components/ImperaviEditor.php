<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\components;

use vova07\imperavi\Widget;
use yii\helpers\Url;

/**
 * Class ImperaviEditor
 *
 * @package backend\components
 */
class ImperaviEditor extends Widget
{
    /**
     * @var array
     */
    public $customSettings = [];

    public function init()
    {
        $this->settings = array_merge(
            [
                'buttons' => [
                    'html',
                    'formatting',
                    'bold',
                    'italic',
                    'underline',
                    'unorderedlist',
                    'orderedlist',
                    'image',
                    'file',
                    'link',
                    'table',
                    'alignment',
                    'horizontalrule'
                ],
                'minHeight' => 250,
                'pastePlainText' => true,
                'buttonSource' => true,
                'replaceDivs' => false,
                'paragraphize' => false,
                'imageManagerJson' => Url::to(['/backend/images-get']),
                'imageUpload' => Url::to(['/backend/image-upload']),
                'fileUpload' => Url::to(['/backend/file-upload']),
                'plugins' => [
                    'imagemanager',
                    'filemanager',
                    'fullscreen',
                    'table',
                    'video',
                ]
            ],
            $this->customSettings
        );

        parent::init();
    }
}
