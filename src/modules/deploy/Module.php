<?php

	namespace vps\tools\modules\deploy;

	/**
	 * @author    Evgenii Kuteiko <kuteiko@mail.ru>
	 * @copyright Copyright (c) 2017
	 * @date      2017-10-31
	 */
	use vps\tools\helpers\ConfigurationHelper;
	use Yii;
	use yii\base\BootstrapInterface;

	/**
	 * Class Module
	 *```php
	 * 'deploy'   => [
	 * 'class'         => 'vps\tools\modules\deploy\Module'
	 * ],
	 * ```
	 * @package vps\tools\modules\deploy
	 */
	class Module extends \yii\base\Module implements BootstrapInterface
	{
		/**
		 * @var string the namespace that controller classes are in
		 */
		public $controllerNamespace = 'vps\tools\modules\deploy\controllers';

		public $img = 'http://img-fotki.yandex.ru/get/5314/31245118.40/0_66e02_1bb503e2_XL';

		/**
		 * @inheritdoc
		 */
		public function bootstrap ($app)
		{
			$app->setAliases([ '@deployViews' => __DIR__ . '/views' ]);
			$app->setAliases([ '@vpsViews' => __DIR__ . '/../../views' ]);
			$app->getUrlManager()->addRules([
				[ 'class'   => 'vps\tools\web\UrlRule',
				  'pattern' => 'env',
				  'route'   => $this->id . '/env/deploy'
				]
			], false);
			ConfigurationHelper::addTranslation('deploy', [ 'deploy' => 'deploy.php' ], __DIR__ . '/messages');

		}
	}