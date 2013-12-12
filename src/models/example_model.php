<?php

/**
 * Description of code_model
 *
 * @author robertopereznygaard
 */
class ExampleModel extends \SilexMVC\Model
{

	public function __construct($app)
	{
		parent::__construct($app);
		$this->_tableName = 'example';
	}

}
