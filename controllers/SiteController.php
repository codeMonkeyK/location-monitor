<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;

/** 
 * Controller is the post fix, so routes to views in the
 * /views/site folder 
 * Actions are the objects that end users can directly refer to for 
 * execution. Actions are grouped by controllers. The execution result
 * of an action is the response that an end user will receive.
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * action that looks for a URL parameter called target
     * to echo to the screen. Action methods have the prefix
     * action to them to identify them as actionable methods.
     * Renders views/ControllerID/ViewName.php --> /views/site/say.php
     */
    public function actionSay($target = 'World')
    {
        return $this->render('say', ['target' => $target]);
    }

    /**
     * Form action. This is the URL (entry). We want to render the view
     * from entry or entry-confirm, but the URL does not change!
     */
    public function actionEntry()
    {
        $model = new EntryForm();

        // check to see if yii\web\Request::post() was made, then validate data
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // valid data received in $model

            // do something meaningful here about $model ...

            // if successful, render entry-confirm
            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            // either the page is initially displayed or there is some validation error
            //Consider Refresh or Redirect to avoid resubmission
            return $this->render('entry', ['model' => $model]);
        }
    }
}
