<?php
/**
 * @package    phocaguestbook
 * @subpackage Controllers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * phocaguestbook Controller.
 *
 * @package    phocaguestbook
 * @subpackage Controllers
 */
class PhocaguestbookControllerPhocaguestbook extends JControllerForm
{
	
	function reply() {
		$app   = JFactory::getApplication();
		$cid   = $this->input->post->get('cid', array(), 'array');
		$context = "$this->option.edit.$this->context";

		// Get the previous record id (if any) and the current record id.
		$parentId = (int) (count($cid) ? $cid[0] : '');
		
		$model = $this->getModel();		
		$data = $model->getItem($parentId);

		// Check-out succeeded, push the new record id into the session.
		//$this->holdEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		$this->setRedirect(
				/*JRoute::_(*/'index.php?option=' . $this->option . '&view=' . $this->view_item	. 
								$this->getRedirectToItemAppend($data->id, 'parentid', false).
								$this->getRedirectToItemAppend($data->catid, 'catid', false)/*)*/	);
		return true;
	}
}
