<?php
/**
 * BDataColumn class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

Yii::import('zii.widgets.grid.CDataColumn');
class BDataColumn extends CDataColumn
{
	/**
	 * Initializes the column.
	 */
	public function init()
	{
		if (isset($this->headerHtmlOptions['class']))
			$this->headerHtmlOptions['class'] .= ' header';
		else
			$this->headerHtmlOptions['class'] = 'header';

		parent::init();
	}

	/**
	 * Renders the header cell.
	 */
	public function renderHeaderCell()
	{
		if ($this->grid->enableSorting && $this->sortable && $this->name !== null)
		{
			$sortDir = $this->grid->dataProvider->getSort()->getDirection($this->name);

			if ($sortDir !== null)
			{
				$sortCssClass = $sortDir ? 'headerSortDown' : 'headerSortUp';
				$this->headerHtmlOptions['class'] .= ' '.$sortCssClass;
			}
		}

		parent::renderHeaderCell();
	}
}
