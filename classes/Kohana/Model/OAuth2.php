<?php defined('SYSPATH') or die('No direct script access.');

/**
 *
 *
 * @package    OAuth2
 * @category   Model
 * @author     Managed I.T.
 * @copyright  (c) 2011 Managed I.T.
 * @license    https://github.com/managedit/kohana-oauth2/blob/master/LICENSE.md
 */
abstract class Kohana_Model_OAuth2
	extends ORM
	implements Interface_Model_OAuth2
{
	/**
	 * @var  Config   Configuration
	 */
	protected $_config = NULL;

	public function __construct($id = NULL)
	{
		$this->_config = Kohana::$config->load('oauth2');
		
		parent::__construct($id);
	}

}