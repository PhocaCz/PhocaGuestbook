<?php
/**
 * @package    phocaguestbook
 * @subpackage Controllers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');


//-- Import the Class JControllerAdmin
jimport('joomla.application.component.controlleradmin');

/**
 * phocaguestbook Controller.
 */
class PhocaguestbookControllerPhocaguestbooks extends JControllerAdmin
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
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$this->setRedirect(JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbooks', false));

		$model = $this->getModel();

		if ($model->rebuild()) {
			// Rebuild succeeded.
			$this->setMessage(JText::_('COM_PHOCAGUESTBOOK_REBUILD_SUCCESS'));
			return true;
		} else {
			// Rebuild failed.
			$this->setMessage(JText::_('COM_PHOCAGUESTBOOK_REBUILD_FAILURE'));
			return false;
		}
	}*/

    
    /**
	 * Method to save the submitted ordering values for records via AJAX.
	 */
	public function saveOrderAjax()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);
		if ($return)
		{
			echo "1";
		}

		
		// Close the application
		JFactory::getApplication()->close();
	}
}
