<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');


/**
 * HTML View class for the phocaguestbook Component.
 *
 * @package    phocaguestbook
 * @subpackage Views
 */
class PhocaguestbookViewPhocaguestbook extends JViewLegacy
{
    /**
     * @var
     */
    protected $item;

    /**
     * @var JForm
     */
    protected $form;

    /**
     * @var string
     */
    protected $script;
    
    /**
     * @var
     */ 
    protected $state;
    /**
     * Phoca Guestbook view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= phocaguestbookHelper::getActions($this->state->get('filter.category_id'));

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

        $this->addToolBar();
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo		= $this->canDo;

		$text = $isNew ? JText::_( 'COM_PHOCAGUESTBOOK_NEW' ) : JText::_('COM_PHOCAGUESTBOOK_EDIT');
		JToolBarHelper::title(   JText::_( 'COM_PHOCAGUESTBOOK_ITEM' ).': <small><small>[ ' . $text.' ]</small></small>' , 'file-2');

		// Built the actions for new and existing records.

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_phocaguestbook', 'core.create')) > 0)) {
			JToolbarHelper::apply('phocaguestbook.apply');
			JToolbarHelper::save('phocaguestbook.save');
			JToolbarHelper::save2new('phocaguestbook.save2new');
			JToolbarHelper::cancel('phocaguestbook.cancel');
		}
		else {
			// Can't save the record if it's checked out.
			if (!$checkedOut) {
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || $canDo->get('core.post')) {
					JToolbarHelper::apply('phocaguestbook.apply');
					JToolbarHelper::save('phocaguestbook.save');
					JToolbarHelper::save2new('phocaguestbook.save2new');
				}
			}

			JToolbarHelper::cancel('phocaguestbook.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolBarHelper::help( 'screen.phocaguestbook', true );
	}
}

