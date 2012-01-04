<?php
/**
 * BBreadcrumbs class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

Yii::import('zii.widgets.CBreadcrumbs');
class BBreadcrumbs extends CBreadcrumbs
{
	/**
	 * @var array the HTML attributes for the breadcrumbs container tag.
	 */
	public $htmlOptions = array('class'=>'breadcrumb');
	/**
	 * @var string the separator between links in the breadcrumbs. Defaults to ' &raquo; '.
	 */
	public $separator = '/';

	public function run()
	{
		if(empty($this->links))
			return;

		$items=array();
		if($this->homeLink===null) {
			$item = CHtml::link(Yii::t('zii','Home'), Yii::app()->homeUrl);
			$items[] = $this->renderItem($item);
		}
		else if($this->homeLink!==false) {
			$item = $this->homeLink;
			$items[] = $this->renderItem($item);
		}
		foreach($this->links as $label=>$url)
		{
			if(is_string($label) || is_array($url)) {
				$item = CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
				$items[] = $this->renderItem($item);
			}
			else {
				$item = ($this->encodeLabel ? CHtml::encode($url) : $url);
				$items[] = $this->renderItem($item, true);
			}
		}

		echo CHtml::openTag('ul', $this->htmlOptions)."\n";
		echo implode($items);
		echo CHtml::closeTag('ul');
	}

	/**
	 * Renders a single breadcrumb item.
	 * @param string $content the content.
	 * @param boolean $active whether the item is active.
	 * @return string the markup.
	 */
	protected function renderItem($item, $active = false)
	{
		$separator = !$active ? '<span class="divider">'.$this->separator.'</span>' : '';

		ob_start();
		echo CHtml::openTag('li', $active ? array('class'=>'active') : array());
		echo $item . $separator;
		echo CHtml::closeTag('li');
		return ob_get_clean();
	}
}
