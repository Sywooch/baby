<?php

namespace backend\modules\configuration\models;

use common\models\Language;
use metalguardian\fileProcessor\helpers\FPM;
use omgdef\multilingual\MultilingualBehavior;
use Yii;
use kartik\builder\Form;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%configuration}}".
 *
 * @property integer $id
 * @property string $config_key
 * @property string $value
 * @property string $description
 * @property integer $type
 * @property string $created
 * @property string $modified
 *
 * @property ConfigurationLang[] $configurationLangs
 */
class Configuration extends \backend\components\BackModel
{

    /**
     * File ids of exist configuration record
     * It needed for save it, after attributes mass assignment
     *
     * @var integer
     */
    protected $_oldFileIds = [];

    /**
     * Array of uploaded files for different languages
     *
     * @var array
     */
    protected $_uploadedFiles = [];

    public $attributeToShowUploadedFiles;


    public function afterFind()
    {
        parent::afterFind();

        if (in_array($this->type, [
                \common\models\Configuration::TYPE_FILE,
                \common\models\Configuration::TYPE_IMAGE
            ])) {
            //For all languages save olf uploaded file id
            foreach (Language::getLangList() as $key => $label) {
                if ($key != Language::getDefaultLang()->code) {
                    $this->_oldFileIds['value_'.$key] = $this->{'value_'.$key};
                } else {
                    $this->_oldFileIds['value'] = $this->value;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%configuration}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_key'], 'required'],
            ['value', 'safe'],
            [['type'], 'integer'],
            [['config_key', 'description'], 'string', 'max' => 255],
            [['config_key'], 'unique'],
            [['id', 'config_key', 'value', 'description', 'type', 'created', 'modified'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $attrs = ['value' => $this->value];
        foreach (Language::getLangList() as $key => $label) {
            if ($key != Language::getDefaultLang()->code) {
                $attrs['value_'.$key] = $this->{'value_'.$key};
            }
        }

        $model = new DynamicModel($attrs);

        switch ($this->type) {
            case \common\models\Configuration::TYPE_STRING;
            case \common\models\Configuration::TYPE_TEXT;
                $model->addRule(['value'], 'string')->addRule(['value'], 'required');
                break;
            case \common\models\Configuration::TYPE_INTEGER;
                $model->addRule(['value'], 'integer')->addRule(['value'], 'required');
                break;
            case \common\models\Configuration::TYPE_FILE;
                $this->addFileValidators($model);
                break;
            case \common\models\Configuration::TYPE_IMAGE;
                $this->addFileValidators($model, true);
                break;
        }

        if ($model->validate()) {

            if (in_array($this->type, [
                    \common\models\Configuration::TYPE_FILE,
                    \common\models\Configuration::TYPE_IMAGE
                ])) {
                $this->saveFile();
            }

            return true;
        } else {
            $this->addErrors($model->getErrors());
            return false;
        }
    }

    /**
     * @param DynamicModel $model
     * @param bool $isImage
     *
     * @return \yii\web\UploadedFile
     */
    protected function addFileValidators(DynamicModel &$model, $isImage = false)
    {
        $newFile = \yii\web\UploadedFile::getInstance($this, 'value');
        if ($newFile) {
            $model->setAttributes(['value' => $newFile], false);
        }
        $model->addRule(['value'], $isImage ? 'image' : 'file', ['skipOnEmpty' => !$this->isNewRecord]);

        $this->_uploadedFiles['value'] = $newFile;

        //Handle multilingual file fields
        foreach (Language::getLangList() as $key => $label) {
            if ($key != Language::getDefaultLang()->code) {
                $field = 'value_'.$key;

                $newFile = \yii\web\UploadedFile::getInstance($this, $field);
                if ($newFile) {
                    $model->setAttributes([$field => $newFile], false);
                }
                $model->addRule([$field], $isImage ? 'image' : 'file', ['skipOnEmpty' => true]);

                $this->_uploadedFiles[$field] = $newFile;
            }
        }
    }

    /**
     * Save all files, include multilingual via FPM
     */
    protected function saveFile()
    {
        //Handle multilingual file fields
        foreach ($this->_uploadedFiles as $label => $file) {
            if (!is_null($file)) {
                $fileId = \metalguardian\fileProcessor\helpers\FPM::transfer()->saveUploadedFile($file);

                if ($fileId) {
                    if (!empty($this->_oldFileIds[$label])) {
                        \metalguardian\fileProcessor\helpers\FPM::deleteFile($this->_oldFileIds[$label]);
                    }
                    $this->{$label} = $fileId;
                }
            } else {
                //Restore old fileId
                $this->{$label} = $this->_oldFileIds[$label];
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find()->with(['translations']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributes = [
            'id' => 'ID',
            'config_key' => 'Ключ',
            'value' => 'Значение',
            'description' => 'Описание',
            'type' => 'Тип',
            'created' => 'Создано',
            'modified' => 'Обновлено',
        ];

        return $this->prepareAttributeLabels($attributes);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                            return date("Y-m-d H:i:s");
                        }
                ],
                'ml' => [
                    'class' => MultilingualBehavior::className(),
                    'languages' => Language::getLangList(),
                    'languageField' => 'lang_id',
                    'defaultLanguage' => Language::getDefaultLang()->code,
                    'langForeignKey' => 'model_id',
                    'tableName' => ConfigurationLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getLocalizedAttributes()
    {
        return [
            'value'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfigurationLangs()
    {
        return $this->hasMany(ConfigurationLang::className(), ['model_id' => 'id']);
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!empty($params)) {
            $this->load($params);
        }

            $query->andFilterWhere(['id' => $this->id]);
            $query->andFilterWhere(['like', 'config_key', $this->config_key]);
            $query->andFilterWhere(['value' => $this->value]);
            $query->andFilterWhere(['like', 'description', $this->description]);
            $query->andFilterWhere(['type' => $this->type]);
            $query->andFilterWhere(['created' => $this->created]);
            $query->andFilterWhere(['modified' => $this->modified]);
    
        return $dataProvider;
    }

    /**
    * @param bool $viewAction
    *
    * @return array
    */
    public function getViewColumns($viewAction = false)
    {
        return $viewAction
            ? [
                'id',
                'config_key',
                'value',
                'description',
                'type',
                'created',
                'modified',
            ]
            : $this->getIndexColumns();
    }

    /**
     * @return array
     */
    public function getIndexColumns()
    {
        $rows = [
            [
                'attribute' => 'id',
                'headerOptions' => ['class' => 'col-sm-1']
            ],
            'config_key',
            'description'
        ];

        $rows[] = [
            'attribute' => 'value',
            'filter' => false,
            'format' => 'raw',
            'value' => function ($data) {
                    if (!empty ($data->value)) {
                        try {
                            switch ($data->type) {
                                case \common\models\Configuration::TYPE_FILE:
                                    $model = FPM::transfer()->getData($data->value);

                                    return Html::a(
                                        $model->base_name . '.' . $model->extension,
                                        FPM::originalSrc($data->value)
                                    );

                                    break;
                                case \common\models\Configuration::TYPE_IMAGE:
                                    $model = FPM::transfer()->getData($data->value);

                                    return Html::img(
                                        FPM::originalSrc($data->value),
                                        ['class' => 'config-img']
                                    );
                                    break;
                                default:
                                    return $data->value;
                            }
                        }
                        catch (Exception $e){
                            return $data->value;
                        }
                    }

                    return null;
                }
        ];

        $rows[] = [
            'class' => \yii\grid\ActionColumn::className()
        ];

        return $rows;
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return $this->getFormRowsDependOfType();
    }

    /**
    * @inheritdoc
    */
    public function getColCount()
    {
        return 1;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'Настройки';
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function getChangeTypeUrl($params = [])
    {
        return static::createUrl('/configuration/configuration/get-form', $params);
    }

    /**
     * @return array
     */
    public function getFormRowsDependOfType()
    {
        $rows = [
            'type' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => \common\models\Configuration::getTypes(),
                'options' => [
                    'class' => 'config-type',
                    'data-url' => static::getChangeTypeUrl()
                ],
            ],
            'config_key' => [
                'type' => Form::INPUT_TEXT,
                'hint' => 'Не изменяйте это поле при обновлении записи, если не уверены, что делаете'
            ],
            'description' => [
                'type' => Form::INPUT_TEXT,
            ],
        ];

        switch ($this->type) {
            case \common\models\Configuration::TYPE_STRING;
            case \common\models\Configuration::TYPE_INTEGER;
                $rows['value'] = [
                        'type' => Form::INPUT_TEXT
                    ];
                break;
            case \common\models\Configuration::TYPE_TEXT;
                $rows['value'] = [
                    'type' => Form::INPUT_TEXTAREA,
                    'options' => [
                        'rows' => 5
                    ]
                ];
                break;
            case \common\models\Configuration::TYPE_FILE;
            case \common\models\Configuration::TYPE_IMAGE;
                $rows['value'] = [
                    'type' => Form::INPUT_FILE
                ];
                $rows['attributeToShowUploadedFiles'] = [
                    'type' => Form::INPUT_RAW,
                    'value' => function () {
                            $output = Html::beginTag('div', ['class' => 'well']);
                            foreach (Language::getLangList() as $key => $label) {
                                if ($key != Language::getDefaultLang()->code) {
                                    $field = 'value_' . $key;
                                    $output .= $this->getLinkToFile($field);
                                } else {
                                    $output .= $this->getLinkToFile();
                                }
                            }
                            $output .= Html::endTag('div');
                            return $output;
                        },
                    'options' => ['class' => 'well']
                ];
                break;
        }

        return $rows;
    }


    /**
     * @param string $attr
     *
     * @return string
     */
    protected function getLinkToFile($attr = 'value')
    {
        $output = '';

        if (!empty ($this->{$attr})) {
            $model = FPM::transfer()->getData($this->{$attr});
            if ($model) {
                $output .= Html::beginTag('p');
                $output .= Html::tag('strong', 'Загруженное '.$this->getAttributeLabel($attr)).':';
                $output .= Html::tag('br');

                if ($this->type == \common\models\Configuration::TYPE_FILE) {
                    $output .= Html::a(
                        $model->base_name . '.' . $model->extension,
                        FPM::originalSrc($this->{$attr})
                    );
                } else {
                    $output .= Html::img(
                        FPM::originalSrc($this->{$attr}),
                        ['class' => 'config-img']
                    );
                }
            }
        }

        return $output;
    }
}
