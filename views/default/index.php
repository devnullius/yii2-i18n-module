<?php
/**
 * @var View $this
 * @var SourceMessageSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use devnullius\yii\modules\i18n\models\search\SourceMessageSearch;
use devnullius\yii\modules\i18n\models\SourceMessage;
use devnullius\yii\modules\i18n\Module;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

$this->title = Module::t('Translations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'value' => static function ($model, $index, $dataColumn) {
                            return $model->id;
                        },
                    ],
                    [
                        'attribute' => 'message',
                        'format' => 'raw',
                        'value' => static function ($model, $index, $widget) {
                            return Html::a($model->message, ['update', 'id' => $model->id], ['data' => ['pjax' => 0]]);
                        },
                    ],
                   'translation',
                    [
                        'attribute' => 'category',
                        'value' => static function ($model, $index, $dataColumn) {
                            return $model->category;
                        },
                        'filter' => ArrayHelper::map($searchModel::getCategories(), 'category', 'category'),
                    ],
                    [
                        'attribute' => 'status',
                        'value' => static function ($model, $index, $widget) {
                            /** @var SourceMessage $model */
                            return $model->isTranslated() ? 'Translated' : 'Not translated';
                        },
                        'filter' => $searchModel::getStatus(),
                    ],
                ],
            ]);
            ?>
        </div>
    </div>

</div>
