<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\store\widgets\imagesUpload;

use common\models\EntityToFile;
use kartik\file\FileInput;
use metalguardian\fileProcessor\helpers\FPM;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class ImageUpload
 * @package backend\modules\store\widgets\imagesUpload
 */
class ImageUpload extends Widget
{
    /**
     * @var \backend\modules\store\models\StoreProduct $model
     */
    public $model;

    /**
     * @var string $attribute
     */
    public $attribute;

    public function run()
    {
        if (!$this->model || !$this->attribute) {
            return null;
        }

        $extraData = $this->model->isNewRecord
            ? ['sign' => $this->model->sign]
            : ['id' => $this->model->id];

        $previewImages = [];
        $previewImagesConfig = [];

        $existModelImages = EntityToFile::find()->where('entity_model_name = :emn', [':emn' => $this->model->formName()]);
        $existModelImages = $this->model->isNewRecord
            ? $existModelImages->andWhere('temp_sign = :ts', [':ts' => $this->model->sign])
            : $existModelImages->andWhere('entity_model_id = :id', [':id' => $this->model->id]);
        $existModelImages = $existModelImages->orderBy('position DESC')->all();

        /**
         * @var \common\models\EntityToFile $file
         */
        foreach ($existModelImages as $image) {
            $fileName = $image->file->base_name.'.'.$image->file->extension;
            $previewImages[] = Html::img(FPM::originalSrc($image->file_id), [
                    'class' => 'file-preview-image',
                    'id' => 'preview-image-'.$image->file_id
                ]);
            $previewImagesConfig[] = [
                'caption' => $fileName,
                'width' => '120px',
                'url' => Url::to(['/store/store-product/delete-image', 'id' => $image->id]),
                'key' => $image->id,
            ];
        }

        $output = Html::hiddenInput('urlForSorting', Url::to(['/store/store-product/sort-images']), ['id' => 'urlForSorting']);

        $output .= FileInput::widget(
            [
                'model' => $this->model,
                'attribute' => $this->attribute,
                'options' => [
                    'multiple' => true,
                    'accept' => 'image/*'
                ],
                'pluginOptions' => [
                    'dropZoneEnabled' => false,
                    'browseClass' => 'btn btn-success',
                    'browseIcon' => '<i class="glyphicon glyphicon-picture"></i> ',
                    'removeClass' => "btn btn-danger",
                    'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> ',
                    'uploadClass' => "btn btn-info",
                    'uploadIcon' => '<i class="glyphicon glyphicon-upload"></i> ',
                    'uploadUrl' => Url::to('/store/store-product/upload-image'),
                    'allowedFileTypes' => ['image'],
                    'allowedPreviewTypes' => ['image'],
                    'uploadExtraData' => $extraData,
                    'initialPreview' => $previewImages,
                    'initialPreviewConfig' => $previewImagesConfig,
                    'overwriteInitial' => false,
                    'showRemove' => false,
                    'otherActionButtons' => $this->render('_crop_button'),
                    'fileActionSettings' => [
                        'indicatorSuccess' => $this->render('_success_buttons_template')
                    ],
                ],
                'pluginEvents' => [
                    'fileuploaded' => 'function(event, data, previewId, index) {
                       var elem = $("#"+previewId).find(".file-actions .file-upload-indicator .kv-file-remove");
                       var cropElem = $("#"+previewId).find(".file-actions .crop-link");
                       var img = $("#"+previewId).find("img");
					   //id for cropped image replace
                       img.attr("id", "preview-image-"+data.response.imgId);

                       elem.attr("data-url", data.response.deleteUrl);
                       elem.attr("data-key", data.response.id);
                       cropElem.attr("href", data.response.cropUrl);

                       //Resort images
                       saveStoreProductSort();

                       //Fix crop url for old images
                       fixMultiUploadImageCropUrl();
                    }',
                    'fileloaded' => 'function(file, previewId, index, reader) {
                        //Fix url for old images
                        fixMultiUploadImageCropUrl();
                    }'
                ]
            ]
        );

        $output .= $this->render('_modal');

        return $output;
    }
}
