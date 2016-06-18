<?php

namespace backend\modules\blog\models;

use backend\modules\store\models\StoreProduct;
use common\models\Language;
use kartik\file\FileInput;
use kartik\select2\Select2;
use metalguardian\fileProcessor\behaviors\UploadBehavior;
use metalguardian\fileProcessor\helpers\FPM;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;
use omgdef\multilingual\MultilingualBehavior;
use vova07\imperavi\Widget;
use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\validators\ImageValidator;
use yii\web\JsExpression;

/**
 * This is the model class for table "{{%blog_article}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $alias
 * @property string $description
 * @property string $show_on_main
 * @property integer $blog_rubric_id
 * @property integer $file_id
 * @property integer $visible
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property BlogRubric $blogRubric
 * @property BlogArticleComponent[] $blogArticleComponents
 * @property BlogArticleLang[] $blogArticleLangs
 */
class BlogArticle extends \backend\components\BackModel
{

    public $content = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'blog_rubric_id', 'alias'], 'required'],
            [['description'], 'string'],
            [['blog_rubric_id', 'show_on_main', 'visible', 'position'], 'integer'],
            [['created', 'content', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 255],
            [
                [
                    'id',
                    'label',
                    'description',
                    'blog_rubric_id',
                    'file_id',
                    'visible',
                    'position',
                    'created',
                    'modified'
                ],
                'safe',
                'on' => 'search'
            ]
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $event = parent::afterSave($insert, $changedAttributes);
        $this->saveTemplate();

        return $event;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->loadTemplate();
    }

    protected function saveTemplate()
    {
        $this->deleteTemplate();

        $mainBlogContenModelId = null;
        $defaultLangCode = Language::getDefaultLang()->code;

        if (!empty ($this->content)) {
            foreach ($this->content as $i => $content) {
                foreach ($content as $typeId => $contentArr) {
                    foreach ($contentArr as $key => $langList) {
                        foreach ($langList as $lang => $value) {
                            $value = trim($value);

                            if (!empty($value) || $typeId == \common\models\BlogArticle::CONTENT_TYPE_IMAGE) {
                                switch ($typeId) {
                                    case \common\models\BlogArticle::CONTENT_TYPE_TEXT:
                                    case \common\models\BlogArticle::CONTENT_TYPE_VIDEO_BLOCK:
                                        if ($lang == $defaultLangCode) {
                                            $model = new BlogArticleComponent();
                                            $model->blog_article_id = $this->id;
                                            $model->type = $typeId;
                                            $model->content = $value;
                                            $model->save(false);

                                            $mainBlogContenModelId = $model->id;
                                        }

                                        $modelLang = new BlogArticleComponentLang();
                                        $modelLang->model_id = $mainBlogContenModelId;
                                        $modelLang->lang_id = $lang;
                                        $modelLang->content = $value;
                                        $modelLang->save(false);

                                        break;
                                    case \common\models\BlogArticle::CONTENT_TYPE_IMAGE:
                                        $file = \yii\web\UploadedFile::getInstance(
                                            $this,
                                            'content[' . $i . '][' . $typeId . '][' . $key . '][' . $lang . ']'
                                        );
                                        $validator = new ImageValidator();

                                        if ($file && $validator->validate($file)) {
                                            $fileId = \metalguardian\fileProcessor\helpers\FPM::transfer(
                                            )->saveUploadedFile($file);
                                            if ($fileId) {
                                                $model = new BlogArticleComponent();
                                                $model->blog_article_id = $this->id;
                                                $model->type = $typeId;
                                                $model->content = $fileId;
                                                $model->save(false);
                                            }
                                        } elseif (!$file && !empty($value)) {
                                            $model = new BlogArticleComponent();
                                            $model->blog_article_id = $this->id;
                                            $model->type = $typeId;
                                            $model->content = $value;
                                            $model->save(false);
                                        }
                                        break;
                                    case \common\models\BlogArticle::CONTENT_TYPE_PRODUCT_BLOCK:
                                        $model = new BlogArticleComponent();
                                        $model->blog_article_id = $this->id;
                                        $model->type = $typeId;
                                        $model->content = $value;
                                        $model->save(false);
                                        break;
                                    case \common\models\BlogArticle::CONTENT_TYPE_IMAGE_BLOCK:
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function deleteTemplate()
    {
        BlogArticleComponent::deleteAll(['blog_article_id' => $this->id]);
    }

    protected function loadTemplate()
    {
        $components = BlogArticleComponent::find()->where(['blog_article_id' => $this->id])->all();

        $i = 0;
        $defaultLangCode = Language::getDefaultLang()->code;
        foreach ($components as $component) {
            switch ($component->type) {
                case \common\models\BlogArticle::CONTENT_TYPE_TEXT:
                case \common\models\BlogArticle::CONTENT_TYPE_VIDEO_BLOCK:
                    $langModels = BlogArticleComponentLang::find()->where(['model_id' => $component->id])->all();

                    foreach ($langModels as $lModel) {
                        $this->content[$i][$component->type][$component->id][$lModel->lang_id] = $lModel->content;
                    }

                    break;
                case \common\models\BlogArticle::CONTENT_TYPE_IMAGE:
                    $this->content[$i][$component->type][$component->id][$defaultLangCode] = $component->content;
                    break;
                case \common\models\BlogArticle::CONTENT_TYPE_PRODUCT_BLOCK:
                    $products = ArrayHelper::map(
                        (new Query())
                            ->select(['id', 'label'])
                            ->from(StoreProduct::tableName())
                            ->where(['id' => explode(',', $component->content)])
                            ->all(),
                        'id',
                        'label'
                    );

                    $this->content[$i][$component->type][$component->id][$defaultLangCode] = empty($products)
                        ? ''
                        : Json::encode($products);

                    break;
                case \common\models\BlogArticle::CONTENT_TYPE_IMAGE_BLOCK:
                    break;
            }

            $i++;
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
            'label' => 'Заголовок',
            'description' => 'Краткое описание',
            'blog_rubric_id' => 'Рубрика',
            'file_id' => 'Изображение',
            'visible' => 'Отображать',
            'position' => 'Позиция',
            'show_on_main' => 'Отображать на главной странице',
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
                    'tableName' => BlogArticleLang::className(),
                    'attributes' => $this->getLocalizedAttributes()
                ],
                'image' => [
                    'class' => UploadBehavior::className(),
                    'attribute' => 'file_id',
                    'image' => true
                ],
                'seo' => [
                    'class' => MetaTagBehavior::className(),
                    'defaultLanguage' => Language::getDefaultLang()->locale
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getLocalizedAttributes()
    {
        return [
            'label',
            'description'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            FPM::deleteFile($this->file_id);

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogRubric()
    {
        return $this->hasOne(BlogRubric::className(), ['id' => 'blog_rubric_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogArticleComponents()
    {
        return $this->hasMany(BlogArticleComponent::className(), ['blog_article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogArticleLangs()
    {
        return $this->hasMany(BlogArticleLang::className(), ['model_id' => 'id']);
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC]
                ]
            ]
        );

        if (!empty($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'label', $this->label]);
        $query->andFilterWhere(['description' => $this->description]);
        $query->andFilterWhere(['blog_rubric_id' => $this->blog_rubric_id]);
        $query->andFilterWhere(['file_id' => $this->file_id]);
        $query->andFilterWhere(['visible' => $this->visible]);
        $query->andFilterWhere(['position' => $this->position]);
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
                'label',
                'description',
                'blog_rubric_id',
                'file_id',
                'visible:boolean',
                'position',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'label',
                [
                    'attribute' => 'blog_rubric_id',
                    'filter' => static::getRubricList(),
                    'value' => function(self $data) {
                        return $data::getRubric($data->blog_rubric_id);
                    }
                ],
                [
                    'attribute' => 'Изображение',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->file_id
                            ? Html::img(
                                FPM::src(
                                    $data->file_id,
                                    'blog',
                                    'adminPreview'
                                )
                            )
                            : null;
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::className()
                ]
            ];
    }

    /**
     * @return array
     */
    public function getFormRows()
    {
        return [
            'label' => [
                'type' => Form::INPUT_TEXT,
                'options' => [
                    'class' => 's_name'
                ]
            ],
            'alias' => [
                'type' => Form::INPUT_TEXT,
                'options' => [
                    'class' => 's_alias'
                ]
            ],
            'blog_rubric_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => static::getRubricList()
            ],
            'description' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],
            'imagePreview' => [
                'type' => Form::INPUT_RAW,
                'value' => function ($this) {
                    return $this->isNewRecord
                        ? null
                        : Html::img(
                            FPM::src(
                                $this->file_id,
                                'blog',
                                'adminPreview'
                            )
                        );
                },
            ],
            'file_id' => [
                'type' => Form::INPUT_FILE,
            ],
            'visible' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'show_on_main' => [
                'type' => Form::INPUT_CHECKBOX,
            ],
            'content' => [
                'type' => Form::INPUT_RAW,
                'value' => $this->getArticleContent(),
                'options' => [
                    'class' => 'test'
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function getArticleContent()
    {
        $output = '';

        $output .= Html::beginTag('div', ['class' => 'form-group template-builder']);
        $output .= Html::beginTag('div', ['class' =>  'template-list']);
        //Hidden field for properly clear content variable when no component installed
        $output .= Html::hiddenInput(Html::getInputName($this, 'content'), '');

        if (!empty ($this->content)) {
            foreach ($this->content as $i => $content) {
                foreach ($content as $typeId => $contentArr) {
                    foreach ($contentArr as $key => $langList) {
                        $output .= $this->getContentByType($typeId, $key, $i);
                    }
                }
            }
        }
        $output .= Html::endTag('div');

        $output .= Html::label('Добавить блок', 'component');
        $output .= Html::tag('br');
        $output .= Html::dropDownList(
            'component',
            [],
            \common\models\BlogArticle::getContentList(),
            ['class' => 'form-control width-50']
        );
        $output .= Html::button(
            '<i class="glyphicon glyphicon-plus"></i>',
            [
                'class' => 'btn btn-success btn-template-builder',
                'data-url' => static::getContentByTypeUrl()
            ]
        );


        $output .= Html::endTag('div');

        return $output;
    }

    /**
     * @param $typeId
     * @param null $key
     *
     * @return string
     */
    public function getContentByType($typeId, $key = null, $i = null)
    {
        $content = Html::beginTag('div', ['class' => 'form-group content-append']);
        $content .= Html::button(
            '<i class="glyphicon glyphicon-move"></i>',
            [
                'class' => 'btn btn-info btn-template-mover',
            ]
        );
        $content .= Html::button(
            '<i class="glyphicon glyphicon-remove"></i>',
            [
                'class' => 'btn btn-danger btn-template-delete',
            ]
        );
        $defaultLangCode = Language::getDefaultLang()->code;
        $uniqueI = Yii::$app->security->generateRandomString(10);
        $uniqueKey = Yii::$app->security->generateRandomString(5);
        foreach (Language::getLangList() as $code => $label) {
            $attributes[$code] = $key
                ? 'content[' . $i . '][' . $typeId . '][' . $key . '][' . $code . ']'
                : 'content[' . $uniqueI . '][' . $typeId . '][' . $uniqueKey . '][' . $code . ']';
        }

        switch ($typeId) {
            case \common\models\BlogArticle::CONTENT_TYPE_TEXT:
                foreach (Language::getLangList() as $code => $label) {
                    if ($code != $defaultLangCode) {
                        $content .= Html::label('[' . $code . ']', $attributes[$code]);
                    }
                    $content .= Widget::widget(
                        [
                            'model' => $this,
                            'attribute' => $attributes[$code],
                            'settings' => [
                                'buttons' => [
                                    'formatting',
                                    'bold',
                                    'link'
                                ],
                                'lang' => 'ru',
                                'minHeight' => 250,
                                'pastePlainText' => true,
                                'buttonSource' => true,
                                'replaceDivs' => true,
                                'paragraphize' => true,
                            ],
                        ]
                    );
                }
                break;
            case \common\models\BlogArticle::CONTENT_TYPE_IMAGE:
                if (!empty($attributes[$defaultLangCode])) {
                    $content .= FPM::image(
                        Html::getAttributeValue($this, $attributes[$defaultLangCode]),
                        'blog',
                        'adminPreview',
                        ['class' => 'content-builder-image']
                    );
                }
                //In hidden input we will store id of exist file to save it, if no new file was uploaded
                $content .= Html::activeHiddenInput($this, $attributes[$defaultLangCode]);
                $content .= Html::fileInput(
                    Html::getInputName($this, $attributes[$defaultLangCode])
                );
                break;
            case \common\models\BlogArticle::CONTENT_TYPE_IMAGE_BLOCK:
                $content .= FileInput::widget(
                    [
                        'model' => $this,
                        'attribute' => $attributes[$defaultLangCode],
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
//                            'uploadExtraData' => $extraData,
//                            'initialPreview' => $previewImages,
//                            'initialPreviewConfig' => $previewImagesConfig,
                            'overwriteInitial' => false,
                            'showRemove' => false,
//                            'otherActionButtons' => $this->render('_crop_button'),
//                            'fileActionSettings' => [
//                                'indicatorSuccess' => $this->render('_success_buttons_template')
//                            ],
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
                break;
            case \common\models\BlogArticle::CONTENT_TYPE_PRODUCT_BLOCK:
                $content .= Select2::widget(
                    [
                        'model' => $this,
                        'attribute' => $attributes[$defaultLangCode],
                        'pluginOptions' => [
                            'tags' => true,
                            'allowClear' => true,
                            'ajax' => [
                                'url' => StoreProduct::getSimilarProductUrl(),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                            ],
                            'initSelection' => new JsExpression(
                                '
                        function (element, callback) {
                            var data = [];
                            var elemData = JSON.parse(element.val());

                            if (elemData != "") {
                                 $.each(elemData, function(key, value){
                                     data.push({
                                                id: key,
                                                text: value
                                            });
                                 });

                                callback(data);
                            }
                        }
                    '
                            )
                        ],
                        'options' => [
                            'multiple' => true,
                            'placeholder' => 'Выберите похожие товары'
                        ]
                    ]
                );
                break;
            case \common\models\BlogArticle::CONTENT_TYPE_VIDEO_BLOCK:
                foreach (Language::getLangList() as $code => $label) {
                    if ($code != $defaultLangCode) {
                        $content .= Html::label('[' . $code . ']', $attributes[$code]);
                    }
                    $content .= Widget::widget(
                        [
                            'model' => $this,
                            'attribute' => $attributes[$code],
                            'settings' => [
                                'convertVideoLinks' => true,
                                'plugins' => [
                                    'video',
                                ],
                                'buttons' => [
                                    'video',
                                ],
                                'lang' => 'ru',
                                'minHeight' => 250,
                            ],
                        ]
                    );
                }
                break;
        }

        $content .= Html::endTag('div');

        return $content;
    }

    /**
     * @return string
     */
    public static function getContentByTypeUrl()
    {
        return static::createUrl('/blog/blog-article/get-content-by-type', []);
    }

    /**
     * @return array
     */
    public static function getRubricList()
    {
        return ArrayHelper::map(
            BlogRubric::find()->asArray()->orderBy(['position' => SORT_DESC])->all(),
            'id',
            'label'
        );
    }

    /**
     * @param $rubricId
     *
     * @return null
     */
    public static function getRubric($rubricId)
    {
        $rubrics = static::getRubricList();

        return isset($rubrics[$rubricId]) ? $rubrics[$rubricId] : null;
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
        return 'Записи в блоге';
    }
}
