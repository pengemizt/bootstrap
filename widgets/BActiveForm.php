<?php
/**
 * BActiveForm class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

Yii::import('ext.bootstrap.helpers.BHtml');
class BActiveForm extends CActiveForm
{
	/**
	 * Initializes the widget.
	 * This renders the form open tag.
	 */
	public function init()
	{
		if($this->errorMessageCssClass == 'block'
		|| $this->errorMessageCssClass == 'help-block')
			$this->errorMessageCssClass = 'help-block';
		else
			$this->errorMessageCssClass = 'help-inline';
		
		parent::init();
	}
	
	/**
	 * Runs the widget.
	 * This registers the necessary javascript code and renders the form close tag.
	 */
	public function run()
	{
		if(is_array($this->focus))
			$this->focus="#".CHtml::activeId($this->focus[0],$this->focus[1]);

		echo CHtml::endForm();
		$cs=Yii::app()->clientScript;
		if(!$this->enableAjaxValidation && !$this->enableClientValidation || empty($this->attributes))
		{
			if($this->focus!==null)
			{
				$cs->registerCoreScript('jquery');
				$cs->registerScript('CActiveForm#focus',"
					if(!window.location.hash)
						$('".$this->focus."').focus();
				");
			}
			return;
		}

		$options = $this->clientOptions;
		
		if(!isset($options['inputContainer'])) {
			$options['inputContainer'] = '.clearfix';
		}
		
		if(isset($this->clientOptions['validationUrl']) && is_array($this->clientOptions['validationUrl']))
			$options['validationUrl']=CHtml::normalizeUrl($this->clientOptions['validationUrl']);

		$options['attributes']=array_values($this->attributes);

		if($this->summaryID!==null)
			$options['summaryID']=$this->summaryID;

		if($this->focus!==null)
			$options['focus']=$this->focus;

		$options=CJavaScript::encode($options);
		$cs->registerCoreScript('yiiactiveform');
		$id=$this->id;
		$cs->registerScript(__CLASS__.'#'.$id,"\$('#$id').yiiactiveform($options);");
	}

	/**
	 * Displays the first validation error for a model attribute.
	 * This is similar to {@link CHtml::error} except that it registers the model attribute
	 * so that if its value is changed by users, an AJAX validation may be triggered.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute name
	 * @param array $htmlOptions additional HTML attributes to be rendered in the container span tag.
	 * Besides all those options available in {@link CHtml::error}, the following options are recognized in addition:
	 * <ul>
	 * <li>validationDelay</li>
	 * <li>validateOnChange</li>
	 * <li>validateOnType</li>
	 * <li>hideErrorMessage</li>
	 * <li>inputContainer</li>
	 * <li>errorCssClass</li>
	 * <li>successCssClass</li>
	 * <li>validatingCssClass</li>
	 * <li>beforeValidateAttribute</li>
	 * <li>afterValidateAttribute</li>
	 * </ul>
	 * These options override the corresponding options as declared in {@link options} for this
	 * particular model attribute. For more details about these options, please refer to {@link clientOptions}.
	 * Note that these options are only used when {@link enableAjaxValidation} or {@link enableClientValidation}
	 * is set true.
	 *
	 * When client-side validation is enabled, an option named "clientValidation" is also recognized.
	 * This option should take a piece of JavaScript code to perform client-side validation. In the code,
	 * the variables are predefined:
	 * <ul>
	 * <li>value: the current input value associated with this attribute.</li>
	 * <li>messages: an array that may be appended with new error messages for the attribute.</li>
	 * <li>attribute: a data structure keeping all client-side options for the attribute</li>
	 * </ul>
	 * @param boolean $enableAjaxValidation whether to enable AJAX validation for the specified attribute.
	 * Note that in order to enable AJAX validation, both {@link enableAjaxValidation} and this parameter
	 * must be true.
	 * @param boolean $enableClientValidation whether to enable client-side validation for the specified attribute.
	 * Note that in order to enable client-side validation, both {@link enableClientValidation} and this parameter
	 * must be true. This parameter has been available since version 1.1.7.
	 * @return string the validation result (error display or success message).
	 * @see CHtml::error
	 */
	public function error($model,$attribute,$htmlOptions=array(),$enableAjaxValidation=true,$enableClientValidation=true)
	{
		if(!$this->enableAjaxValidation)
			$enableAjaxValidation=false;
		if(!$this->enableClientValidation)
			$enableClientValidation=false;

		if(!isset($htmlOptions['class']))
			$htmlOptions['class']=$this->errorMessageCssClass;

		if(!$enableAjaxValidation && !$enableClientValidation)
			return BHtml::error($model,$attribute,$htmlOptions);

		$id=BHtml::activeId($model,$attribute);
		$inputID=isset($htmlOptions['inputID']) ? $htmlOptions['inputID'] : $id;
		unset($htmlOptions['inputID']);
		if(!isset($htmlOptions['id']))
			$htmlOptions['id']=$inputID.'_em_';

		$option=array(
			'id'=>$id,
			'inputID'=>$inputID,
			'errorID'=>$htmlOptions['id'],
			'model'=>get_class($model),
			'name'=>BHtml::resolveName($model, $attribute),
			'enableAjaxValidation'=>$enableAjaxValidation,
		);

		$optionNames=array(
			'validationDelay',
			'validateOnChange',
			'validateOnType',
			'hideErrorMessage',
			'inputContainer',
			'errorCssClass',
			'successCssClass',
			'validatingCssClass',
			'beforeValidateAttribute',
			'afterValidateAttribute',
		);
		foreach($optionNames as $name)
		{
			if(isset($htmlOptions[$name]))
			{
				$option[$name]=$htmlOptions[$name];
				unset($htmlOptions[$name]);
			}
		}
		if($model instanceof CActiveRecord && !$model->isNewRecord)
			$option['status']=1;

		if($enableClientValidation)
		{
			$validators=isset($htmlOptions['clientValidation']) ? array($htmlOptions['clientValidation']) : array();
			foreach($model->getValidators($attribute) as $validator)
			{
				if($enableClientValidation && $validator->enableClientValidation)
				{
					if(($js=$validator->clientValidateAttribute($model,$attribute))!='')
						$validators[]=$js;
				}
			}
			if($validators!==array())
				$option['clientValidation']="js:function(value, messages, attribute) {\n".implode("\n",$validators)."\n}";
		}

		$html=BHtml::error($model,$attribute,$htmlOptions);
		if($html==='')
		{
			if(isset($htmlOptions['style']))
				$htmlOptions['style']=rtrim($htmlOptions['style'],';').';display:none';
			else
				$htmlOptions['style']='display:none';
			$html=BHtml::tag('span',$htmlOptions,'');
		}

		$this->attributes[$inputID]=$option;
		return $html;
	}

	/**
	 * Renders a text field for a model attribute.
	 * This method is a wrapper of {@link CHtml::activeTextField}.
	 * Please check {@link CHtml::activeTextField} for detailed information
	 * about the parameters for this method.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes.
	 * @return string the generated input field
	 */
	public function textField($model,$attribute,$htmlOptions=array())
	{
		return BHtml::activeTextField($model,$attribute,$htmlOptions);
	}

	/**
	 * Renders a checkbox list for a model attribute.
	 * This method is a wrapper of {@link CHtml::activeCheckBoxList}.
	 * Please check {@link CHtml::activeCheckBoxList} for detailed information
	 * about the parameters for this method.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the check box list.
	 * @param array $htmlOptions addtional HTML options.
	 * @return string the generated check box list
	 */
	public function checkBoxList($model,$attribute,$data,$htmlOptions=array())
	{
		return BHtml::activeCheckBoxList($model,$attribute,$data,$htmlOptions);
	}
}
