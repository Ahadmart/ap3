<?php

/**
 * Filterform to use filters in combination with CArrayDataProvider and CGridView
 * @see http://www.yiiframework.com/wiki/232/using-filters-with-cgridview-and-carraydataprovider/
 */
class FiltersForm extends CFormModel {

	/**
	 * @var array filters, key => filter string
	 */
	public $filters = array();

	/**
	 * Override magic getter for filters
	 * @param string $name
	 */
	public function __get($name) {
		if (!array_key_exists($name, $this->filters)) {
			$this->filters[$name] = '';
		}
		return $this->filters[$name];
	}

	/**
	 * Override magic setter for filters
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$this->filters[$name] = $value;
	}

	/**
	 * Filter input array by key value pairs
	 * @param array $data rawData
	 * @return array filtered data array
	 */
	public function filter(array $data) {
		foreach ($data AS $rowIndex => $row) {
			foreach ($this->filters AS $key => $searchValue) {
				if (!empty($searchValue)) {
					$compareValue = null;

					if ($row instanceof CModel) {
						if (isset($row->$key) == false) {
							throw new CException("Property ".get_class($row)."::{$key} does not exist!");
						}
						$compareValue = $row->$key;
					} elseif (is_array($row)) {
						if (!array_key_exists($key, $row)) {
							throw new CException("Key {$key} does not exist in array!");
						}
						$compareValue = $row[$key];
					} else {
						throw new CException("Data in CArrayDataProvider must be an array of arrays or an array of CModels!");
					}
					
					if (stripos($compareValue, $searchValue) === false) {
						unset($data[$rowIndex]);
					}
				}
			}
		}
		return $data;
	}

}
