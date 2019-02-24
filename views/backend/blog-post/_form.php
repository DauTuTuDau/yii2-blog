<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

use diazoxide\yii2blog\models\BlogCategory;
use diazoxide\yii2blog\Module;
use kartik\markdown\MarkdownEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model diazoxide\yii2blog\models\BlogPost */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-post-form">


    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-10\">{input}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-12 text-right">
            <?= Html::submitButton($model->isNewRecord ? Module::t('blog', 'Create') : Module::t('blog', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-warning']) ?>
        </div>
    </div>


    <div class="row top-buffer-20">
        <div class="col-md-8">

            <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => 128, 'class' => 'form-control input-sm', 'readonly' => true, 'onclick' => "this.removeAttribute('readonly')"]) ?>


            <?= $form->field($model, 'brief')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::class, [
                'moduleId' => $model->module->redactorModule,
                'clientOptions' => [
                    'plugins' => ['clips', 'fontcolor', 'imagemanager']
                ]
            ]); ?>
        </div>

        <div class="col-md-4">


            <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(BlogCategory::get(0, BlogCategory::find()->all()), 'id', 'str_label')) ?>

            <?= $form->field($model, 'is_slide')->dropDownList([0 => 'No', 1 => 'Yes']) ?>

            <?= $form->field($model, 'tags')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'banner')->fileInput() ?>

            <?= $form->field($model, 'click')->textInput() ?>

            <?= $form->field($model, 'status')->dropDownList(\diazoxide\yii2blog\models\BlogPost::getStatusList()) ?>

        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
