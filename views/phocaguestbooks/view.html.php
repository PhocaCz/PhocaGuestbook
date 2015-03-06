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
class PhocaguestbookViewPhocaguestbooks extends JViewLegacy
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var JPagination
     */
    protected $pagination;
    /**
     * @var array
     */
    protected $state;
    /**
     * Phoca Guestbook view display method
     */
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state		= $this->get('State');
        
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->parent_id][$item->lft] = $item->id;
		}

		// Levels filter.
		$options	= array();
		$options[]	= JHtml::_('select.option', '1', JText::_('COM_PHOCAGUESTBOOK_HIDE_COMMENTS'));
		$options[]	= JHtml::_('select.option', '10', JText::_('COM_PHOCAGUESTBOOK_SHOW_COMMENTS'));
		$this->f_levels = $options;
		
		$this->addToolBar();        
		$this->sidebar = JHtmlSidebar::render();
		
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolBar()
    {
		require_once JPATH_COMPONENT.'/helpers/phocaguestbook.php';
		
		$canDo = phocaguestbookHelper::getActions($this->state->get('filter.category_id'));
		$user  = JFactory::getUser();
		
		//TOOLBAR
        JToolBarHelper::title(JText::_('COM_PHOCAGUESTBOOK_ITEMS'), 'file-2');

		if ($canDo->get('core.create')  || (count($user->getAuthorisedCategories('com_phocaguestbook', 'core.create'))) > 0 ) {
			JToolBarHelper::addNew('phocaguestbook.add','JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('phocaguestbook.edit','JTOOLBAR_EDIT');
			JToolBarHelper::custom('phocaguestbook.reply', 'edit.png', 'edit_f2.png', 'COM_PHOCAGUESTBOOK_ADD_COMMENT', true);
		}
		
		
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::custom('phocaguestbooks.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('phocaguestbooks.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
        
        if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList('COM_PHOCAGUESTBOOK_WARNING_DELETE_ITEMS', 'phocaguestbooks.delete', 'COM_PHOCAGUESTBOOK_DELETE');
		}
		
		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_phocaguestbook');
		}
		
	    JToolBarHelper::help( 'screen.phocaguestbook', true );

        JFactory::getDocument()->addStyleDeclaration(
       '.icon-48-phocaguestbook'
       .' {background-image: url(components/com_phocaguestbook/assets/images/com_phocaguestbook-48.png)}');
       
       //SIDEBAR
       JHtmlSidebar::setAction('index.php?option=com_phocaguestbook&view=phocaguestbooks');
       
       	JHtmlSidebar::addFilter(
			JText::_('COM_PHOCAGUESTBOOK_SELECT_GUESTBOOK'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_phocaguestbook'), 'value', 'text', $this->state->get('filter.category_id'))
		);
		
       JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => 0, 'trash' => 0)), 'value', 'text', $this->state->get('filter.state'), true)
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_MAX_LEVELS'),
			'filter_level',
			JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'))
		);
    }

    
    /**
	 * Returns an array of fields the table can be sorted by
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.date' => JText::_('COM_PHOCAGUESTBOOK_DATE'),
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'category_title' => JText::_('COM_PHOCAGUESTBOOK_FIELD_GUESTBOOK_LABEL'),
			'a.published' => JText::_('COM_PHOCAGUESTBOOK_PUBLISHED'),
			'a.title' => JText::_('COM_PHOCAGUESTBOOK_SUBJECT'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
