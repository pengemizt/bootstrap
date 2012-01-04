<?php
/**
 * BCaptcha class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

class BCaptcha extends CCaptcha
{
	public $model;
	public $attribute;

	/**
	 * Renders the widget.
	 */
	public function run()
	{
	    if(self::checkRequirements())
	    {
			echo '<div class="captcha">';
			$this->renderImage();
			echo '</div>';
			if(is_object($this->model)) {
				echo CHtml::activeTextField($this->model, $this->attribute);
			}
			$this->registerClientScript();
	    }
		else
			throw new CException(Yii::t('bootstrap','GD and FreeType PHP extensions are required.'));
	}
}
