<?php
/**
 * BGridView class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

Yii::import('zii.widgets.grid.CGridView');
Yii::import('ext.bootstrap.widgets.grid.BDataColumn');
class BGridView extends CGridView
{
	/**
	 * @property string the CSS class name for the container table.
	 * Defaults to 'zebra-striped'.
	 */
	public $itemsCssClass = 'bordered-table zebra-striped';
	/**
	 * @property string the CSS class name for the pager container.
	 * Defaults to 'pagination'.
	 */
	public $pagerCssClass = 'pagination';
	/**
	 * @property array the configuration for the pager.
	 * Defaults to <code>array('class'=>'ext.bootstrap.widgets.pagers.BLinkPager')</code>.
	 */
	public $pager = array('class'=>'ext.bootstrap.widgets.pagers.BLinkPager');

	/**
	 * Creates column objects and initializes them.
	 */
	protected function initColumns()
	{
		foreach ($this->columns as &$column) {
			if (!isset($column['class']))
				$column['class'] = 'BDataColumn';
		}
		parent::initColumns();
	}
}
