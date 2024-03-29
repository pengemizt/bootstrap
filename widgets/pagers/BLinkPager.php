<?php
/**
 * BLinkPager class file
 * 
 * @author Niko Wicaksono <pengemizt@gmail.com>
 * @copyright Copyright (c) 2011 Niko Wicaksono
 * @license http://niko.wicaksono.info/bsd-license
 */

class BLinkPager extends CLinkPager
{
	/**
	 * Initializes the pager by setting some default property values.
	 */
	public function init()
	{
		// @fixme not rendered properly
		$this->header = '';

		if ($this->nextPageLabel === null)
			$this->nextPageLabel=Yii::t('bootstrap','Next');
		if ($this->prevPageLabel === null)
			$this->prevPageLabel=Yii::t('bootstrap','Previous');
		if ($this->lastPageLabel === null)
			$this->lastPageLabel=Yii::t('bootstrap','Last &rarr;');
		if ($this->firstPageLabel === null)
			$this->firstPageLabel=Yii::t('bootstrap','&larr; First');

		if ($this->cssFile === null)
			$this->cssFile = false; // Bootstrap has its own css

		if (!isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = '';

		parent::init();
	}

	/**
	 * Creates the page buttons.
	 * @return array a list of page buttons (in HTML code).
	 */
	protected function createPageButtons()
	{
		if (($pageCount = $this->getPageCount()) <= 1)
			return array();

		list ($beginPage, $endPage) = $this->getPageRange();
		$currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()

		$buttons = array();

		// first page
		$buttons[] = $this->createPageButton($this->firstPageLabel, 0, self::CSS_PREVIOUS_PAGE, $currentPage <= 0, false);

		// prev page
		if (($page = $currentPage - 1) < 0)
			$page = 0;
		$buttons[] = $this->createPageButton($this->prevPageLabel, $page, null, $currentPage <= 0, false);

		// internal pages
		for ($i = $beginPage; $i <= $endPage; ++$i)
			$buttons[]=$this->createPageButton($i + 1, $i, '', false, $i == $currentPage);

		// next page
		if (($page = $currentPage+1)>=$pageCount-1)
			$page = $pageCount-1;
		$buttons[] = $this->createPageButton($this->nextPageLabel, $page, null, $currentPage >= ($pageCount - 1), false);

		// last page
		$buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, self::CSS_NEXT_PAGE, $currentPage >= ($pageCount - 1), false);

		return $buttons;
	}

	/**
	 * Creates a page button.
	 * You may override this method to customize the page buttons.
	 * @param string $label the text label for the button
	 * @param integer $page the page number
	 * @param string $class the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
	 * @param boolean $hidden whether this page button is visible
	 * @param boolean $selected whether this page button is selected
	 * @return string the generated button
	 */
	protected function createPageButton($label, $page, $class, $hidden, $selected)
	{
		if ($hidden || $selected)
			$class .= ' '.($hidden ? 'disabled' : 'active');

		return CHtml::tag('li', array('class'=>$class), CHtml::link($label, $this->createPageUrl($page)));
	}
}
