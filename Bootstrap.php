<?php
/**
 * Bootstrap class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

class Bootstrap extends CApplicationComponent
{
	/**
	 * @var string assets url
	 */
	protected $_assetsUrl;
	
	/**
	 * Register Twitter Bootstrap.
	 */
	public function register()
	{
		$cs = Yii::app()->clientScript;
		$cs->registerCssFile($this->getAssetsUrl().'/bootstrap.min.css');
	}

	/**
	* Returns the url to assets publishing the folder if necessary.
	* @return string the assets url
	*/
	protected function getAssetsUrl()
	{
		if ($this->_assetsUrl !== null)
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('ext.bootstrap.assets');
			$assetsManager = Yii::app()->assetManager;
			if (YII_DEBUG)
				$assetsUrl = $assetsManager->publish($assetsPath, false, -1, true);
			else
				$assetsUrl = $assetsManager->publish($assetsPath);

			return $this->_assetsUrl = $assetsUrl;
		}
	}
}
