<?php

namespace devnullius\yii\modules\i18n\models\search;

use devnullius\yii\modules\i18n\models\Message;
use devnullius\yii\modules\i18n\models\SourceMessage;
use devnullius\yii\modules\i18n\Module;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class SourceMessageSearch extends SourceMessage
{
    public const STATUS_TRANSLATED = 1;
    public const STATUS_NOT_TRANSLATED = 2;
    
    public $status;
    public $translation;
    
    public static function getStatus($id = null)
    {
        $statuses = [
            self::STATUS_TRANSLATED => Module::t('Translated'),
            self::STATUS_NOT_TRANSLATED => Module::t('Not translated'),
        ];
        if ($id !== null) {
            return ArrayHelper::getValue($statuses, $id, null);
        }
        
        return $statuses;
    }
    
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['category', 'safe'],
            ['message', 'safe'],
            ['status', 'safe'],
            ['translation', 'safe'],
        ];
    }
    
    /**
     * @param array|null $params
     *
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search($params): ActiveDataProvider
    {
        $query = SourceMessage::find()
            ->select([
                SourceMessage::tableName() . '.id',
                SourceMessage::tableName() . '.category as [[category]]',
                SourceMessage::tableName() . '.message as [[message]]',
                Message::tableName() . '.translation as [[translation]]',
            ])
            ->joinWith(['messages'])
            ->groupBy([SourceMessage::tableName() . '.id', Message::tableName() . '.translation'])
            ->orderBy([SourceMessage::tableName() . '.id' => SORT_DESC]);
        
        $query->andWhere([ Message::tableName() . '.language' => \Yii::$app->language]);
        
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        if ((int)$this->status === static::STATUS_TRANSLATED) {
            $query->translated();
        }
        if ((int)$this->status === static::STATUS_NOT_TRANSLATED) {
            $query->notTranslated();
        }
        
        $query
            ->andFilterWhere(['like', SourceMessage::tableName() . '.category', $this->category])
            ->andFilterWhere(['like', Message::tableName() . '.translation', $this->translation])
            ->andFilterWhere(['like', SourceMessage::tableName() . '.message', $this->message]);
        
        return $dataProvider;
    }
}
