<?php
declare(strict_types=1);

namespace devnullius\yii\modules\i18n\models;

use devnullius\yii\modules\i18n\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Message extends ActiveRecord
{
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get(Yii::$app->getI18n()->db);
    }
    
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function tableName(): string
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        
        return $i18n->messageTable;
    }
    
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['language', 'required'],
            ['language', 'string', 'max' => 16],
            ['translation', 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Module::t('ID'),
            'language' => Module::t('Language'),
            'translation' => Module::t('Translation'),
        ];
    }
    
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'defaultValue' => -1,
            ],
        ];
    }
    
    public function getSourceMessage(): ActiveQuery
    {
        return $this->hasOne(SourceMessage::class, ['id' => 'id']);
    }
}
