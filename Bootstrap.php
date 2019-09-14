<?php

namespace devnullius\yii\modules\i18n;

use devnullius\yii\modules\i18n\console\controllers\I18nController;
use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\data\Pagination;
use yii\web\Application as WebApplication;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        if ($app instanceof WebApplication && $i18nModule = Yii::$app->getModule('i18n')) {
            $moduleId = $i18nModule->id;
            $app->getUrlManager()->addRules([
                'translations/<id:\d+>' => $moduleId . '/default/update',
                'translations/page/<page:\d+>' => $moduleId . '/default/index',
                'translations' => $moduleId . '/default/index',
            ], false);
            
            Yii::$container->set(Pagination::class, [
                'pageSizeLimit' => [1, 100],
                'defaultPageSize' => $i18nModule->pageSize,
            ]);
        }
        if (($app instanceof ConsoleApplication) && !isset($app->controllerMap['i18n'])) {
            $app->controllerMap['i18n'] = I18nController::class;
        }
    }
}
