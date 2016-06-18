<?php
/**
 * Author: Pavel Naumenko
 */

namespace app\modules\user\controllers;

use app\modules\user\forms\FullFillProfileForm;
use app\modules\user\forms\LoginForm;
use app\modules\user\forms\PasswordResetRequestForm;
use app\modules\user\forms\ProfileForm;
use app\modules\user\forms\ResetPasswordForm;
use app\modules\user\forms\SignupForm;
use common\models\SocialAuth;
use common\models\User;
use frontend\components\SocialAuthHelper;
use frontend\components\UnusedParamsFilter;
use frontend\controllers\FrontController;
use skeeks\widget\simpleajaxuploader\backend\FileUpload;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

/**
 * Class UserController
 *
 * @package frontend\modules\user\controllers
 */
class UserController extends FrontController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'full-fill-profile'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'full-fill-profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'paramsFilter' => [
                'class' => UnusedParamsFilter::className(),
                'actions' => [
                    //action => ['param', 'param2']
                    'upload' => ['avatar'],
                    'reset-password' => ['token'],
                    'auth' => ['authclient', 'code']
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'profile-update' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['upload', 'upload-progress'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        $socialEmail = SocialAuthHelper::getEmail($attributes);
        $socialName = SocialAuthHelper::getName($attributes);
        $socialSurname = SocialAuthHelper::getSurname($attributes);
        $avatarId = SocialAuthHelper::getAvatarImageId($attributes);

        /* @var $auth \common\models\SocialAuth */
        $auth = SocialAuth::find()->where([
            'source' => $client->getId(),
            'source_id' => (string)$attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else { // signup
                if ($socialEmail) {
                    $userExist = User::find()->where(['email' => $socialEmail])->one();

                    if (!$userExist) {
                        $this->createSocialUser(
                            [
                                'name' => $socialName,
                                'surname' => $socialSurname,
                                'email' => $socialEmail,
                                'avatar_file_id' => $avatarId
                            ],
                            $client->id,
                            $attributes['id']
                        );
                    } else {
                        $auth = new SocialAuth([
                            'user_id' => $userExist->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            Yii::$app->user->login($userExist);
                        } else {
                            print_r($auth->getErrors());
                        }
                    }

                } else {
                    $this->createSocialUser(
                        [
                            'name' => $socialName,
                            'surname' => $socialSurname,
                            'avatar_file_id' => $avatarId
                        ],
                        $client->id,
                        $attributes['id']
                    );
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new SocialAuth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                $auth->save();
            }
        }

        return $this->goBack();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        $this->layout = '//simple';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render(
            'signup',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $label = Yii::t('resetPasswordForm', 'We sent your an email with password reset link');
                $subMessage = Yii::t('resetPasswordForm', 'Check your email for further instructions.');
            } else {
                $label = Yii::t('resetPasswordForm', 'Sorry, we are unable to reset password for email provided.');
                $subMessage = null;
            }

            return $this->render('requestPasswordDone', ['label' => $label, 'subMessage' => $subMessage]);
        }

        return $this->render(
            'requestPasswordResetToken',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param $token
     *
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $this->redirect('reset-password-done');
        }

        return $this->render(
            'resetPassword',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @return string
     */
    public function actionResetPasswordDone()
    {
        return $this->render(
            'requestPasswordDone',
            [
                'label' => Yii::t('newPasswordForm', 'New password was saved.'),
                'subMessage' => Yii::t('newPasswordForm', 'Now you can try to login with new credentials'),
            ]
        );
    }

    /**
     * @param FileUpload $file
     *
     * @return int
     */
    protected function saveFile(FileUpload $file)
    {
        $ext = $file->getExtension();
        $baseName = $file->getFileName();
        $user = Yii::$app->user->identity;

        $model = new \metalguardian\fileProcessor\models\File();
        $model->extension = $ext;
        $model->base_name = $baseName;
        $model->save(false);
        //Set user avatar id
        $user->avatar_file_id = $model->id;
        $user->save(false);

        $directory = \metalguardian\fileProcessor\helpers\FPM::getOriginalDirectory($model->id);

        \yii\helpers\FileHelper::createDirectory($directory, 0777, true);

        $newFileName =
            $directory
            . DIRECTORY_SEPARATOR
            . \metalguardian\fileProcessor\helpers\FPM::getOriginalFileName(
                $model->id,
                $baseName,
                $ext
            );

        rename($file->getSavedFile(), $newFileName);

        return $model->id;
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $this->layout = '//simple';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render(
                'login',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('profile', ['user' => Yii::$app->user->identity]);
    }

    /**
     * @return string
     */
    public function actionProfileUpdate()
    {
        $data = null;

        if (Yii::$app->request->isAjax) {
            $model = new ProfileForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    $data = [
                        'content' => [
                            [
                                'what' => '#popup',
                                'data' => $this->renderPartial(
                                    '_thank_popup',
                                    [
                                        'label' => Yii::t('profile', 'update_success_label'),
                                        'message' => Yii::t('profile', 'update_success_message')
                                    ]
                                )
                            ]
                        ],
                        'replaces' => [
                            [
                                'what' => '.user-descr',
                                'data' => $this->renderPartial(
                                    '_profile_user_info',
                                    [
                                        'user' => $model,
                                    ]
                                )
                            ]
                        ],
                        'js' => Html::script('showPopup();')
                    ];
                }
            }

            $data = $data
                ? $data
                : [
                    'content' => [
                        [
                            'what' => '#popup',
                            'data' => $this->renderAjax('profile_form', compact('model'))
                        ]
                    ],
                    'js' => Html::script('showPopup();')
                ];

            echo Json::encode($data);
        }
    }

    public function actionUpload()
    {
        $uploadDir = Yii::$app->getBasePath() . '/web/uploads/profile_avatar/';
        $allowedExt = ['gif', 'png', 'jpeg', 'jpg'];

        $upload = new FileUpload('avatar');
        $result = $upload->handleUpload($uploadDir, $allowedExt);

        if (!$result) {
            $data = [
                'error' => $upload->getErrorMsg()
            ];
        } else {
            $data = [
                'success' => true,
                'replaces' => [

                    [
                        'what' => '#user-foto',
                        'data' => $this->renderAjax('_user_avatar', ['src' => $this->saveFile($upload)])
                    ]
                ]
            ];
        }

        echo Json::encode($data);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return \yii\web\Response
     */
    public function actionFullFillProfile()
    {
        $this->layout = '//simple';

        /**
         * @var $user \common\models\User
         */
        $user = Yii::$app->user->getIdentity();

        if ($user->is_profile_filled) {
            return $this->redirect(['profile']);
        }

        $model = new FullFillProfileForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render(
            'full_fill_profile',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param array $userData
     * @param $clientId
     * @param $clientName
     * @throws \yii\db\Exception
     */
    protected function createSocialUser(array $userData, $clientId, $clientName)
    {
        $password = Yii::$app->security->generateRandomString(6);

        $user = new User($userData);
        $user->password = $password;
        $user->is_profile_filled = 0;
        $user->generateAuthKey();
        $user->generatePasswordResetToken();

        $transaction = $user->getDb()->beginTransaction();
        if ($user->save(false)) {
            $auth = new SocialAuth([
                'user_id' => $user->id,
                'source' => $clientId,
                'source_id' => (string)$clientName,
            ]);
            if ($auth->save()) {
                $transaction->commit();
                Yii::$app->user->login($user);
            } else {
                print_r($auth->getErrors());
            }
        } else {
            print_r($user->getErrors());
        }
    }
}
