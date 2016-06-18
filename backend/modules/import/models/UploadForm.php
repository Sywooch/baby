<?php
namespace backend\modules\import\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'],
                'file',
                'checkExtensionByMimeType' => false,
                'skipOnEmpty' => false,
                'extensions' => 'csv',
                'mimeTypes' => 'text/csv, text/plain, text/tsv'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Выберите файл в формате .csv'
        ];
    }
}
