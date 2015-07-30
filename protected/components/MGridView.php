<?php

Yii::import('zii.widgets.grid.CGridView');

class MGridView extends CGridView {

	public $htmlOptions = array('class' => '');
	public $emptyText = 'Data tidak ditemukan';
	public $summaryText = '{start}-{end} dari {count}';
	public $itemsCssClass = 'striped responsive-table';
	public $pagerCssClass = 'pagination-centered';
	public $loadingCssClass = 'grid-loading';
	public $filterPosition = 'footer';
	public $pager = array(
		 'header' => '',
		 'firstPageCssClass' => 'arrow',
		 'firstPageLabel' => '&laquo;',
		 'prevPageLabel' => '&lsaquo;',
		 'nextPageLabel' => '&rsaquo;',
		 'htmlOptions' => array('class' => 'pagination'),
		 'hiddenPageCssClass' => 'unavailable',
		 'selectedPageCssClass' => 'current',
		 'lastPageCssClass' => 'arrow',
		 'lastPageLabel' => '&raquo;',
	);
}
