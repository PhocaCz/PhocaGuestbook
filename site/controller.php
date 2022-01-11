<?php
/**
 * @package    phocaguestbook
 * @subpackage Base
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Phoca Guestbook default Controller.
 *
 * @package    phocaguestbook
 * @subpackage Controllers
 */
class PhocaguestbookController extends BaseController
{
	public function display($cachable = false, $urlparams = false)
	{
		$app		= Factory::getApplication();
		$paramsC 	= $app->getParams();
		$cache 		= $paramsC->get( 'enable_cache', 0 );
		$cachable 	= false;
		if ($cache == 1) {
			$cachable 	= true;
		}
		
		// Set the default view name and format from the Request.
		$vName		= $this->input->get('view', 'phocaguestbook');
		$this->input->get('view', $vName);

		$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY', 'limit'=>'INT','limitstart'=>'INT',
			'return'=>'BASE64','print'=>'BOOLEAN','lang'=>'CMD');

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_phocaguestbook.edit.guestbook', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			
			throw new Exception( Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 403);
			return false;
		}

		parent::display($cachable,$safeurlparams);
		return $this;
	}	
	
}
