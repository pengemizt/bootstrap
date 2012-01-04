<?php
/**
 * BFormInputElement class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

Yii::import('ext.bootstrap.helpers.BHtml');
class BFormInputElement extends CFormInputElement
{
	/**
	 * @var array Core input types (alias=>BHtml method name)
	 */
	public static $coreTypes=array(
		'text'=>'activeTextField',
		'hidden'=>'activeHiddenField',
		'password'=>'activePasswordField',
		'textarea'=>'activeTextArea',
		'file'=>'activeFileField',
		'radio'=>'activeRadioButton',
		'checkbox'=>'activeCheckBox',
		'listbox'=>'activeListBox',
		'dropdownlist'=>'activeDropDownList',
		'checkboxlist'=>'activeCheckBoxList',
		'radiolist'=>'activeRadioButtonList',
	);
	/**
	 * @var string the layout used to render label, input, hint and error. They correspond to the placeholders
	 * "{label}", "{input}", "{hint}" and "{error}".
	 */
	public $layout = "{label}<div class=\"input\">{input}\n{error}\n{hint}</div>";

	/**
	 * Renders everything for this input.
	 * The default implementation simply returns the result of {@link renderLabel}, {@link renderInput},
	 * {@link renderHint}. When {@link CForm::showErrorSummary} is false, {@link renderError} is also called
	 * to show error messages after individual input fields.
	 * @return string the complete rendering result for this input, including label, input field, hint, and error.
	 */
	public function render()
	{
		if($this->type==='hidden')
			return $this->renderInput();
		$output=array(
			'{label}'=>$this->renderLabel(),
			'{input}'=>$this->renderInput(),
			'{hint}'=>$this->renderHint(),
			'{error}'=>$this->getParent()->showErrorSummary ? '' : $this->renderError(),
		);
		return strtr($this->layout,$output);
	}

	/**
	 * Renders the input field.
	 * The default implementation returns the result of the appropriate CHtml method or the widget.
	 * @return string the rendering result
	 */
	public function renderInput()
	{
		if(isset(self::$coreTypes[$this->type]))
		{
			$method=self::$coreTypes[$this->type];
			if(strpos($method,'List')!==false) {
				return BHtml::$method($this->getParent()->getModel(), $this->name, $this->items, $this->attributes);
				
			}
			else
				return BHtml::$method($this->getParent()->getModel(), $this->name, $this->attributes);
		}
		else
		{
			$attributes=$this->attributes;
			$attributes['model']=$this->getParent()->getModel();
			$attributes['attribute']=$this->name;
			ob_start();
			$this->getParent()->getOwner()->widget($this->type, $attributes);
			return ob_get_clean();
		}
	}

	/**
	 * Renders the hint text for this input.
	 * The default implementation returns the {@link hint} property enclosed in a paragraph HTML tag.
	 * @return string the rendering result.
	 */
	public function renderHint()
	{
		return $this->hint===null ? '' : '<span class="help-block">'.$this->hint.'</span>';
	}
}
