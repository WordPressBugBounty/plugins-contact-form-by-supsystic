<?php
#[\AllowDynamicProperties]
abstract class controllerCfs {
	protected $_models = array();
	protected $_views = array();
	protected $_task = '';
	protected $_defaultView = '';
	protected $_code = '';
	public function __construct($code) {
		$this->setCode($code);
		$this->_defaultView = $this->getCode();
	}
	public function init() {
		/*load model and other preload data goes here*/
	}
	protected function _onBeforeInit() {

	}
	protected function _onAfterInit() {

	}
	public function setCode($code) {
		$this->_code = $code;
	}
	public function getCode() {
		return $this->_code;
	}
	public function exec($task = '') {
		if(method_exists($this, $task)) {
			$this->_task = $task;   //For multicontrollers module version - who know, maybe that's will be?))
			return $this->$task();
		}
		return null;
	}
	public function getView($name = '') {
		if(empty($name)) $name = $this->getCode();
		if(!isset($this->_views[$name])) {
			$this->_views[$name] = $this->_createView($name);
		}
		return $this->_views[$name];
	}
	public function getModel($name = '') {
		if(!$name)
			$name = $this->_code;
		if(!isset($this->_models[$name])) {
			$this->_models[$name] = $this->_createModel($name);
		}
		return $this->_models[$name];
	}
	protected function _createModel($name = '') {
		if(empty($name)) $name = $this->getCode();
		$parentModule = frameCfs::_()->getModule( $this->getCode() );
		$className = '';
		if(importCfs($parentModule->getModDir(). 'models'. DS. $name. '.php')) {
			$className = toeGetClassNameCfs($name. 'Model');
		}

		if($className) {
			$model = new $className();
			$model->setCode( $this->getCode() );
			return $model;
		}
		return NULL;
	}
	protected function _createView($name = '') {
		if(empty($name)) $name = $this->getCode();
		$parentModule = frameCfs::_()->getModule( $this->getCode() );
		$className = '';

		if(importCfs($parentModule->getModDir(). 'views'. DS. $name. '.php')) {
			$className = toeGetClassNameCfs($name. 'View');
		}

		if($className) {
			$view = new $className();
			$view->setCode( $this->getCode() );
			return $view;
		}
		return NULL;
	}
	public function display($viewName = '') {
		$view = NULL;
		if(($view = $this->getView($viewName)) === NULL) {
			$view = $this->getView();   //Get default view
		}
		if($view) {
			$view->display();
		}
	}
	public function __call($name, $arguments) {
		$model = $this->getModel();
		if(method_exists($model, $name))
			return $model->$name($arguments[0]);
		else
			return false;
	}
	/**
	 * Retrive permissions for controller methods if exist.
	 * If need - should be redefined in each controller where it required.
	 * @return array with permissions
	 * @example :
	 return array(
			S_METHODS => array(
				'save' => array(CFS_ADMIN),
				'remove' => array(CFS_ADMIN),
				'restore' => CFS_ADMIN,
			),
			S_USERLEVELS => array(
				S_ADMIN => array('save', 'remove', 'restore')
			),
		);
	 * Can be used on of sub-array - CFS_METHODS or CFS_USERLEVELS
	 */
	public function getPermissions() {
		return array();
	}
	/**
	 * Methods that require nonce to be generated
	 * If need - should be redefined in each controller where it required.
	 * @return array
	 */
	public function getNoncedMethods() {
		return array();
	}
	public function getModule() {
		return frameCfs::_()->getModule( $this->getCode() );
	}
	// protected function _prepareTextLikeSearch($val) {
	// 	return '';	 // Should be re-defined for each type
	// }
	// protected function _prepareModelBeforeListSelect($model) {
	// 	return $model;
	// }
	/**
	 * Common method for list table data
	 */
	//public function getListForTbl() {
		// $res = new responseCfs();
		// $res->ignoreShellData();
		// $model = $this->getModel();
		//
		// $page = (int) sanitize_text_field(reqCfs::getVar('page'));
		// $rowsLimit = (int) sanitize_text_field(reqCfs::getVar('rows'));
		//
		// // Our custom search
		// $search = reqCfs::getVar('search');
		// if($search && !empty($search) && is_array($search)) {
		// 	foreach($search as $k => $v) {
		// 		$v = trim($v);
		// 		if(empty($v)) continue;
		// 		if($k == 'text_like') {
		// 			$k = sanitize_text_field($k);
		// 			$v = sanitize_text_field($v);
		// 			$v = $this->_prepareTextLikeSearch( $v );
		// 			if(!empty($v)) {
		// 				$model->addWhere(array('additionalCondition' => $v));
		// 			}
		// 		} else {
		// 			$k = sanitize_text_field($k);
		// 			$v = sanitize_text_field($v);
		// 			$model->addWhere(array($k => $v));
		// 		}
		// 	}
		// }
		// // jqGrid search
		// $isSearch = reqCfs::getVar('_search');
		// if($isSearch) {
		// 	$searchField = sanitize_text_field(trim(reqCfs::getVar('searchField')));
		// 	$searchString = sanitize_text_field(trim(reqCfs::getVar('searchString')));
		// 	if(!empty($searchField) && !empty($searchString)) {
		// 		// For some cases - we will need to modify search keys and/or values before put it to the model
		// 		$model->addWhere(array(
		// 			$this->_prepareSearchField($searchField) => $this->_prepareSearchString($searchString)
		// 		));
		// 	}
		// }
		// $model = $this->_prepareModelBeforeListSelect($model);
		// // Get total pages count for current request
		// $totalCount = $model->getCount(array('clear' => array('selectFields')));
		// $totalPages = 0;
		// if($totalCount > 0) {
		// 	$totalPages = ceil($totalCount / $rowsLimit);
		// }
		// if($page > $totalPages) {
		// 	$page = $totalPages;
		// }
		// // Calc limits - to get data only for current set
		// $limitStart = $rowsLimit * $page - $rowsLimit; // do not put $limit*($page - 1)
		// if($limitStart < 0)
		// 	$limitStart = 0;
		//
 		// $data = $model
		// 	->setLimit($limitStart. ', '. $rowsLimit)
		// 	->setSimpleGetFields()
		// 	->getFromTbl();
		//
		// $data = $this->_prepareListForTbl( $data );
		// $res->addData('page', $page);
		// $res->addData('total', $totalPages);
		// $res->addData('rows', $data);
		// $res->addData('records', $model->getLastGetCount());
		// $res = dispatcherCfs::applyFilters($this->getCode(). '_getListForTblResults', $res);
		// $res->ajaxExec();
	//}
	public function removeGroup() {
		$res = new responseCfs();
		if($this->getModel()->removeGroup(reqCfs::getVar('listIds', 'post'))) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function clear() {
		$res = new responseCfs();
		if($this->getModel()->clear()) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	protected function _prepareListForTbl($data) {
		return $data;
	}
	protected function _prepareSearchField($searchField) {
		return $searchField;
	}
	protected function _prepareSearchString($searchString) {
		return $searchString;
	}
	// protected function _prepareSortOrder($sortOrder) {
	// 	return $sortOrder;
	// }
}
