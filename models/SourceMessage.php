<?php
declare(strict_types=1);

namespace devnullius\yii\modules\i18n\models;

use devnullius\yii\modules\i18n\models\query\SourceMessageQuery;
use devnullius\yii\modules\i18n\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class SourceMessage
 *
 * @package devnullius\yii\modules\i18n\models
 */
class SourceMessage extends ActiveRecord
{
    public $translation;
    
    /**
     * @inheritdoc
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
        if (!isset($i18n->sourceMessageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        
        return $i18n->sourceMessageTable;
    }
    
    /**
     * @return array|SourceMessage[]
     */
    public static function getCategories(): array
    {
        return self::find()->select('category')->distinct('category')->asArray()->all();
    }
    
    public static function find()
    {
        return new SourceMessageQuery(static::class);
    }
    
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['message', 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Module::t('ID'),
            'category' => Module::t('Category'),
            'message' => Module::t('Message'),
            'status' => Module::t('Translation status'),
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
    
    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['id' => 'id'])->indexBy('language');
    }
    
    public function initMessages(): void
    {
        $messages = [];
        foreach (Yii::$app->getI18n()->languages as $language) {
            if (!isset($this->messages[$language])) {
                $message = new Message;
                $message->language = $language;
                $messages[$language] = $message;
            } else {
                $messages[$language] = $this->messages[$language];
            }
        }
        $this->populateRelation('messages', $messages);
    }
    
    public function saveMessages(): void
    {
        /** @var Message $message */
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
            $message->save();
        }
    }
    
    public function isTranslated(): bool
    {
        foreach ($this->messages as $message) {
            if (!$message->translation) {
                return false;
            }
        }
        
        return true;
    }
}
