<?php

namespace devnullius\yii\modules\i18n\controllers;

use devnullius\yii\modules\i18n\models\search\SourceMessageSearch;
use devnullius\yii\modules\i18n\models\SourceMessage;
use devnullius\yii\modules\i18n\Module;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DefaultController extends Controller
{
    public function actionIndex(): string
    {
        $searchModel = new SourceMessageSearch;
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        /** @var SourceMessage $model */
        $model = $this->findModel($id);
        $model->initMessages();
        
        if (Model::loadMultiple($model->messages, Yii::$app->getRequest()->post()) && Model::validateMultiple($model->messages)) {
            $model->saveMessages();
            Yii::$app->getSession()->setFlash('success', Module::t('Updated'));
            
            return $this->redirect(['update', 'id' => $model->id]);
        }
        
        return $this->render('update', ['model' => $model]);
    }
    
    /**
     * @param array|integer $id
     *
     * @return SourceMessage|SourceMessage[]
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $query = SourceMessage::find()->where('id = :id', [':id' => $id]);
        $models = is_array($id)
            ? $query->all()
            : $query->one();
        if (!empty($models)) {
            return $models;
        }
        throw new NotFoundHttpException(Module::t('The requested page does not exist'));
    }
}
