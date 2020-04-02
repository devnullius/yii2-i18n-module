<?php
/**
 * @var View          $this
 * @var SourceMessage $model
 */

use devnullius\yii\modules\i18n\models\SourceMessage;
use devnullius\yii\modules\i18n\Module;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

//use Zelenin\yii\SemanticUI\Elements;
//use Zelenin\yii\SemanticUI\widgets\ActiveForm;

$this->title = Module::t('Update') . ': ' . $model->message;
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Default box -->
<div class="translate-update">
    <div class="box box-default">
        <div class="box-body">
            <div class="row">
                <div class="col-6">
                    <?php $form = ActiveForm::begin(); ?>

                    <?php foreach ($model->messages as $language => $message) : ?>
                        <div class="col-md-12 margin-bottom">
                            <div class="col-md-1">
                                <?= $language ?>
                            </div>
                            <div class="col-md-10">
                                <?= $form->field($model->messages[$language], '[' . $language . ']translation')->textarea(['class' => 'col-md-8'])->label(false) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-6">
                    <div class="col-md-12 margin-bottom">
                        <div class="col-md-1">
                            <?= Html::submitButton(Module::t('Update'), ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
                <?php $form::end(); ?>
            </div>
        </div>
    </div>
</div>
