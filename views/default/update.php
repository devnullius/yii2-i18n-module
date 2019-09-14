<?php
/**
 * @var View $this
 * @var SourceMessage $model
 */

use devnullius\yii\modules\i18n\models\SourceMessage;
use devnullius\yii\modules\i18n\Module;
use yii\helpers\Html;
use yii\web\View;
use Zelenin\yii\SemanticUI\Elements;
use Zelenin\yii\SemanticUI\widgets\ActiveForm;

$this->title = Module::t('Update') . ': ' . $model->message;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-update">
    <div class="message-form">
        <div class="box box-default">
            <div class="box-header">
                <?= Elements::header(Module::t('Source message'), ['class' => 'top attached']) ?>
                <?= Elements::segment(Html::encode($model->message), ['class' => 'bottom attached']) ?>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body">
                <?php foreach ($model->messages as $language => $message) : ?>
                    <div class="col-md-12 margin-bottom">
                        <div class="col-md-1">
                            <?= $language ?>
                        </div>
                        <div class="col-md-10">
                            <?= $form->field($model->messages[$language], '[' . $language . ']translation')->textarea(['class' => 'col-md-8' ])->label(false) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Module::t('Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php $form::end(); ?>

        </div>
    </div>
</div>
