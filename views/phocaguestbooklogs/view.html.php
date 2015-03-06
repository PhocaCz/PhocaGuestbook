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
class PhocaguestbookViewPhocaguestbooklogs extends JViewLegacy
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
		JHtml::stylesheet( 'media/com_phocaguestbook/css/administrator/phocaguestbook.css' );
		
        $this->items 		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->params       = JComponentHelper::getParams('com_phocaguestbook');
        
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
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
		
		$canDo = phocaguestbookHelper::getActions();
		$user  = JFactory::getUser();
		
		//TOOLBAR
        JToolBarHelper::title(JText::_('COM_PHOCAGUESTBOOK_ITEMS'), 'file-2');
   
        if ($canDo->get('core.admin')) {
			JToolBarHelper::deleteList('COM_PHOCAGUESTBOOK_WARNING_DELETE_ITEMS', 'phocaguestbooklogs.delete', 'COM_PHOCAGUESTBOOK_DELETE');
			JToolbarHelper::preferences('com_phocaguestbook');
		}
		
	    JToolBarHelper::help( 'screen.phocaguestbook', true );
       
		//SIDEBAR
		JHtmlSidebar::setAction('index.php?option=com_phocaguestbook&view=phocaguestbooklogs');
       
		JHtmlSidebar::addFilter(
			JText::_('COM_PHOCAGUESTBOOK_SELECT_GUESTBOOK'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_phocaguestbook'), 'value', 'text', $this->state->get('filter.category_id'))
		);
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', array(1 => 'COM_PHOCAGUESTBOOK_PUBLISHED', 2 => 'COM_PHOCAGUESTBOOK_REVIEW', 3 => 'COM_PHOCAGUESTBOOK_REJECT'), 'value', 'text', $this->state->get('filter.state'), true)
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
			'category_title' => JText::_('COM_PHOCAGUESTBOOK_FIELD_GUESTBOOK_LABEL'),
			'a.state' => JText::_('Status'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
