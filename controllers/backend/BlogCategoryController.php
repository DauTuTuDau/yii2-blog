<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

namespace diazoxide\yii2blog\controllers\backend;

use diazoxide\yii2blog\models\BlogCategory;
use diazoxide\yii2blog\models\BlogCategorySearch;
use diazoxide\yii2blog\traits\IActiveStatus;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BlogCategoryController implements the CRUD actions for BlogCategory model.
 */
class BlogCategoryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete', 'create', 'update', 'view'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_CATEGORIES']
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_CATEGORY']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['BLOG_DELETE_CATEGORY']
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['BLOG_CREATE_CATEGORY']
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['BLOG_UPDATE_CATEGORY']
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all BlogCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogCategorySearch();
        $dataProvider = BlogCategory::get(0, BlogCategory::find()->all());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlogCategory model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the BlogCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new BlogCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BlogCategory();

        if (Yii::$app->request->get('parent_id') > 0) {
            $model->parent_id = Yii::$app->request->get('parent_id');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlogCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BlogCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        $model->status = IActiveStatus::STATUS_ARCHIVE;
        $model->save();

        return $this->redirect(['index']);
    }
}
