<?php

use yii\base\InvalidConfigException;
use yii\db\Migration;

class m140609_093837_addI18nTables extends Migration
{
    /**
     * @return bool|void
     * @throws InvalidConfigException
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }

        $sourceMessageTable = $i18n->sourceMessageTable;
        $messageTable = $i18n->messageTable;

        $this->createTable($sourceMessageTable, [
            'id' => $this->bigPrimaryKey(),
            'created_by' => $this->bigInteger()->notNull()->defaultValue(0)->comment('Modifier id of create, if 0 created from db, if -1 not registered user.'),
            'updated_by' => $this->bigInteger()->notNull()->defaultValue(0)->comment('Modifier id of update, if 0 created from db, if -1 not registered user.'),
            'created_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of create date.'),
            'updated_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of update date.'),
            'modifier' => $this->string()->notNull()->defaultValue('user')->comment('Operation performer entity name.'),
            'deleted' => $this->boolean()->defaultValue(false)->comment('If true row is softly deleted, only marker.'),
            'category' => $this->string()->null(),
            'message' => $this->text()->null(),
        ], $tableOptions);

        $this->createTable($messageTable, [
            'id' => $this->bigInteger()->null(),
            'created_by' => $this->bigInteger()->notNull()->defaultValue(0)->comment('Modifier id of create, if 0 created from db, if -1 not registered user.'),
            'updated_by' => $this->bigInteger()->notNull()->defaultValue(0)->comment('Modifier id of update, if 0 created from db, if -1 not registered user.'),
            'created_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of create date.'),
            'updated_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of update date.'),
            'modifier' => $this->string()->notNull()->defaultValue('user')->comment('Operation performer entity name.'),
            'deleted' => $this->boolean()->defaultValue(false)->comment('If true row is softly deleted, only marker.'),
            'language' => $this->string()->null(),
            'translation' => $this->text()->null(),
        ], $tableOptions);
        $this->addPrimaryKey('id', $messageTable, ['id', 'language']);
        $this->addForeignKey('fk_source_message_message', $messageTable, 'id', $sourceMessageTable, 'id', 'cascade', 'restrict');
    }

    public function safeDown()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }

        $this->dropTable($i18n->sourceMessageTable);
        $this->dropTable($i18n->messageTable);
    }
}
