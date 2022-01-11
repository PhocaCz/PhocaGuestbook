<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;


/**
 * HTML View class for the phocaguestbook Component.
 *
 * @package    phocaguestbook
 * @subpackage Views
 */
class PhocaguestbookViewPhocaguestbook extends HtmlView
{

    protected $item;
    protected $form;
    protected $script;
    protected $state;
    protected $t;
	protected $r;

    public function display($tpl = null)
    {
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= phocaguestbookHelper::getActions($this->state->get('filter.category_id'));

        $this->t	= PhocaguestbookHelper::setVars('');
		$this->r	= new PhocaGuestbookRenderAdminview();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {

			throw new Exception(implode("\n", $errors), 500);
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
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user		= Factory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo		= $this->canDo;

		$text = $isNew ? Text::_( 'COM_PHOCAGUESTBOOK_NEW' ) : Text::_('COM_PHOCAGUESTBOOK_EDIT');
		ToolbarHelper::title(   Text::_( 'COM_PHOCAGUESTBOOK_ITEM' ).': <small><small>[ ' . $text.' ]</small></small>' , 'file-2');

		// Built the actions for new and existing records.

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_phocaguestbook', 'core.create')) > 0)) {
			ToolbarHelper::apply('phocaguestbook.apply');
			ToolbarHelper::save('phocaguestbook.save');
			ToolbarHelper::save2new('phocaguestbook.save2new');
			ToolbarHelper::cancel('phocaguestbook.cancel');
		}
		else {
			// Can't save the record if it's checked out.
			if (!$checkedOut) {
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || $canDo->get('core.post')) {
					ToolbarHelper::apply('phocaguestbook.apply');
					ToolbarHelper::save('phocaguestbook.save');
					ToolbarHelper::save2new('phocaguestbook.save2new');
				}
			}

			ToolbarHelper::cancel('phocaguestbook.cancel', 'JTOOLBAR_CLOSE');
		}

		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.phocaguestbook', true );
	}
}

