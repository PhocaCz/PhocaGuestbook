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
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;


/**
 * HTML View class for the phocaguestbook Component.
 *
 * @package    phocaguestbook
 * @subpackage Views
 */
class PhocaguestbookViewPhocaguestbooks extends HtmlView
{
    protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	protected $r;
	public $filterForm;
    public $activeFilters;

    public function display($tpl = null)
    {
        $this->t			= PhocaguestbookHelper::setVars('');
		$this->r 			= new PhocaguestbookRenderAdminviews();
		$this->items			= $this->get('Items');
		$this->pagination		= $this->get('Pagination');
		$this->state			= $this->get('State');
		$this->filterForm   	= $this->get('FilterForm');
        $this->activeFilters 	= $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->parent_id][$item->lft] = $item->id;
		}

		// Levels filter.
		$options	= array();
		$options[]	= HTMLHelper::_('select.option', '1', Text::_('COM_PHOCAGUESTBOOK_HIDE_COMMENTS'));
		$options[]	= HTMLHelper::_('select.option', '10', Text::_('COM_PHOCAGUESTBOOK_SHOW_COMMENTS'));
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
		$user  = Factory::getUser();

		//TOOLBAR
        ToolbarHelper::title(Text::_('COM_PHOCAGUESTBOOK_ITEMS'), 'file-2');

        $bar = ToolBar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocaguestbook" class="btn btn-small"><i class="icon-home-2" title="'.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'"></i> '.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.create')  || (count($user->getAuthorisedCategories('com_phocaguestbook', 'core.create'))) > 0 ) {
			ToolbarHelper::addNew('phocaguestbook.add','JTOOLBAR_NEW');
		}

		if ($canDo->get('core.edit')) {
			ToolbarHelper::editList('phocaguestbook.edit','JTOOLBAR_EDIT');
			ToolbarHelper::custom('phocaguestbook.reply', 'edit.png', 'edit_f2.png', 'COM_PHOCAGUESTBOOK_ADD_COMMENT', true);
		}


		if ($canDo->get('core.edit.state')) {
			ToolbarHelper::custom('phocaguestbooks.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			ToolbarHelper::custom('phocaguestbooks.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}

        if ($canDo->get('core.delete')) {
			ToolbarHelper::deleteList('COM_PHOCAGUESTBOOK_WARNING_DELETE_ITEMS', 'phocaguestbooks.delete', 'COM_PHOCAGUESTBOOK_DELETE');
		}

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_phocaguestbook');
		}

	    ToolbarHelper::help( 'screen.phocaguestbook', true );

      /*  Factory::getDocument()->addStyleDeclaration(
       '.icon-48-phocaguestbook'
       .' {background-image: url(components/com_phocaguestbook/assets/images/com_phocaguestbook-48.png)}');

       //SIDEBAR
       JHtmlSidebar::setAction('index.php?option=com_phocaguestbook&view=phocaguestbooks');

       	JHtmlSidebar::addFilter(
			Text::_('COM_PHOCAGUESTBOOK_SELECT_GUESTBOOK'),
			'filter_category_id',
			HTMLHelper::_('select.options', HTMLHelper::_('category.options', 'com_phocaguestbook'), 'value', 'text', $this->state->get('filter.category_id'))
		);

       JHtmlSidebar::addFilter(
			Text::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			HTMLHelper::_('select.options', HTMLHelper::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);

		JHtmlSidebar::addFilter(
			Text::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			HTMLHelper::_('select.options', HTMLHelper::_('jgrid.publishedOptions', array('archived' => 0, 'trash' => 0)), 'value', 'text', $this->state->get('filter.state'), true)
		);

		JHtmlSidebar::addFilter(
			Text::_('JOPTION_SELECT_MAX_LEVELS'),
			'filter_level',
			HTMLHelper::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'))
		);*/
    }


    /**
	 * Returns an array of fields the table can be sorted by
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.date' => Text::_('COM_PHOCAGUESTBOOK_DATE'),
			'ordering' => Text::_('JGRID_HEADING_ORDERING'),
			'category_title' => Text::_('COM_PHOCAGUESTBOOK_FIELD_GUESTBOOK_LABEL'),
			'a.published' => Text::_('COM_PHOCAGUESTBOOK_PUBLISHED'),
			'a.title' => Text::_('COM_PHOCAGUESTBOOK_SUBJECT'),
			'a.language' => Text::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => Text::_('JGRID_HEADING_ID')
		);
	}
}
