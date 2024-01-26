<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

//-- No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;


/**
 * HTML View class for the phocaguestbook Component.
 *
 * @package    phocaguestbook
 * @subpackage Views
 */
class PhocaguestbookViewPhocaguestbooklogs extends HtmlView
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
		$this->t			= PhocaguestbookHelper::setVars('log');
		$this->r 			= new PhocaguestbookRenderAdminviews();

        $this->items 		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');
        $this->filterForm   	= $this->get('FilterForm');
        $this->activeFilters 	= $this->get('ActiveFilters');
        $this->params       = ComponentHelper::getParams('com_phocaguestbook');

        foreach ($this->items as &$item) {
			$this->ordering[0][] = $item->id;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolBar();
		$this->sidebar = Sidebar::render();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolBar()
    {
		require_once JPATH_COMPONENT.'/helpers/phocaguestbook.php';

		$canDo = phocaguestbookHelper::getActions();
		$user  = Factory::getUser();

		//TOOLBAR

        $bar = ToolBar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocaguestbook" class="btn btn-primary btn-small"><i class="icon-home-2" title="'.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'"></i> '.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

        ToolbarHelper::title(Text::_('COM_PHOCAGUESTBOOK_LOGGING'), 'file-2');

        if ($canDo->get('core.admin')) {
			ToolbarHelper::deleteList('COM_PHOCAGUESTBOOK_WARNING_DELETE_ITEMS', 'phocaguestbooklogs.delete', 'COM_PHOCAGUESTBOOK_DELETE');
			ToolbarHelper::preferences('com_phocaguestbook');
		}

	    ToolbarHelper::help( 'screen.phocaguestbook', true );

		//SIDEBAR
		/*JHtmlSidebar::setAction('index.php?option=com_phocaguestbook&view=phocaguestbooklogs');

		JHtmlSidebar::addFilter(
			Text::_('COM_PHOCAGUESTBOOK_SELECT_GUESTBOOK'),
			'filter_category_id',
			HTMLHelper::_('select.options', HTMLHelper::_('category.options', 'com_phocaguestbook'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			Text::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			HTMLHelper::_('select.options', array(1 => 'COM_PHOCAGUESTBOOK_PUBLISHED', 2 => 'COM_PHOCAGUESTBOOK_REVIEW', 3 => 'COM_PHOCAGUESTBOOK_REJECT'), 'value', 'text', $this->state->get('filter.state'), true)
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
			'category_title' => Text::_('COM_PHOCAGUESTBOOK_FIELD_GUESTBOOK_LABEL'),
			'a.state' => Text::_('Status'),
			'a.id' => Text::_('JGRID_HEADING_ID')
		);
	}
}
