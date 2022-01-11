<?php
/**
 * @package    phocaguestbook
 * @subpackage Controllers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Factory;


//-- Import the Class JControllerAdmin
jimport('joomla.application.component.controlleradmin');

/**
 * phocaguestbook Controller.
 */
class PhocaguestbookControllerPhocaguestbooks extends AdminController
{
    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'phocaguestbook', $prefix = 'phocaguestbookModel'
    , $config = array('ignore_request' => true))
    {
        //$doSomething = 'here';
        return parent::getModel($name, $prefix, $config);
    }

    /**
	 * Rebuild the nested set tree.
	 *
	 * @return	bool	False on failure or error, true on success.
	 * @since	1.6
	 */
	/*public function rebuild()
	{
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$this->setRedirect(Route::_('index.php?option=com_phocaguestbook&view=phocaguestbooks', false));

		$model = $this->getModel();

		if ($model->rebuild()) {
			// Rebuild succeeded.
			$this->setMessage(Text::_('COM_PHOCAGUESTBOOK_REBUILD_SUCCESS'));
			return true;
		} else {
			// Rebuild failed.
			$this->setMessage(Text::_('COM_PHOCAGUESTBOOK_REBUILD_FAILURE'));
			return false;
		}
	}*/


    /**
	 * Method to save the submitted ordering values for records via AJAX.
	 */
	public function saveOrderAjax()
	{
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);
		if ($return)
		{
			echo "1";
		}


		// Close the application
		Factory::getApplication()->close();
	}
}
