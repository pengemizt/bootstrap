<?php
/**
 * BForm class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

Yii::import('ext.bootstrap.helpers.BFormInputElement');
Yii::import('ext.bootstrap.widgets.BActiveForm');
class BForm extends CForm
{
	/**
	 * @var string the name of the class for representing a form input element. Defaults to 'CFormInputElement'.
	 */
	public $inputElementClass='BFormInputElement';
	/**
	 * @var array the configuration used to create the active form widget.
	 * The widget will be used to render the form tag and the error messages.
	 * The 'class' option is required, which specifies the class of the widget.
	 * The rest of the options will be passed to {@link CBaseController::beginWidget()} call.
	 * Defaults to array('class'=>'CActiveForm').
	 * @since 1.1.1
	 */
	public $activeForm=array('class'=>'BActiveForm');

	/**
	 * Initializes this form.
	 * This method is invoked at the end of the constructor.
	 * You may override this method to provide customized initialization (such as
	 * configuring the form object).
	 */
	public function init()
	{
		if(!isset($this->activeForm['class']))
			$this->activeForm['class'] = 'BActiveForm';
	}
	
	/**
	 * Renders the {@link buttons} in this form.
	 * @return string the rendering result
	 */
	public function renderButtons()
	{
		$output='';
		foreach($this->getButtons() as $button)
			$output.=$this->renderElement($button);
		return $output!=='' ? "<div class=\"actions\">".$output."</div>\n" : '';
	}
	
	/**
	 * Renders the {@link elements} in this form.
	 * @return string the rendering result
	 */
	public function renderElement($element)
	{
		if(is_string($element))
		{
			if(($e=$this[$element])===null && ($e=$this->getButtons()->itemAt($element))===null)
				return $element;
			else
				$element=$e;
		}
		if($element->getVisible())
		{
			if($element instanceof BFormInputElement)
			{
				if($element->type==='hidden')
					return "<div style=\"visibility:hidden\">\n".$element->render()."</div>\n";
				else
					return "<div class=\"clearfix field_{$element->name}\">\n".$element->render()."</div>\n";
			}
			else if($element instanceof CFormButtonElement)
				return $element->render()."\n";
			else
				return $element->render();
		}
		return '';
	}
}
