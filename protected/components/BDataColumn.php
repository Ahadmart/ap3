<?php

Yii::import('zii.widgets.grid.CDataColumn');

class BDataColumn extends CDataColumn {

	public $accesskey = '';
	public $autoFocus = false;

	protected function renderFilterCellContent() {
		if (is_string($this->filter))
			echo $this->filter;
		elseif ($this->filter !== false && $this->grid->filter !== null && $this->name !== null && strpos($this->name, '.') === false) {
			if (is_array($this->filter))
				echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id' => false, 'prompt' => ''));
			elseif ($this->filter === null) {
				$hOptions = array('id' => false, 'accesskey' => $this->accesskey);
				if ($this->autoFocus) {
					$hOptions = array_merge($hOptions, array('autofocus' => 'autofocus'));
				}
				echo CHtml::activeTextField($this->grid->filter, $this->name, $hOptions); // 'placeholder' => '[Alt]+['.$this->accesskey.']'));
			}
		} else
			parent::renderFilterCellContent();
	}

}
