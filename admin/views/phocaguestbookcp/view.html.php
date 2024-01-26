<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;


/**
 * HTML View class for the phocaguestbook Component.
 *
 * @package    phocaguestbook
 * @subpackage Views
 */
class PhocaguestbookViewPhocaguestbookCp extends HtmlView
{
	/**
     * @var array
     */
    protected $t;

    /**
     * Phoca Guestbook view display method
     */
    public function display($tpl = null)
    {

		$this->t	= PhocaguestbookHelper::setVars('cp');
		$this->r	= new PhocaguestbookRenderAdminview();
		$i = ' icon-';
		$d = 'duotone ';

		$this->views= array(
        's'		=> array($this->t['l'] . '_ITEMS', $d.$i.'comment', '#c1a46d'),
		'categories'		=> array($this->t['l'] . '_GUESTBOOKS', $d.$i.'folder-open', '#da7400'),
        'logs'		=> array($this->t['l'] . '_LOGGING', $d.$i.'logs', '#c0c0c0'),
		'in'		=> array($this->t['l'] . '_INFO', $d.$i.'info-circle', '#3378cc')
		);

		$this->t['version'] = PhocaguestbookHelper::getPhocaVersion();

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
		$canDo = phocaguestbookHelper::getActions(NULL);

		//TOOLBAR
        ToolBarHelper::title(Text::_('COM_PHOCAGUESTBOOK_PG_CONTROL_PANEL'), 'home-2 cpanel');

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = ToolBar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocaguestbook" class="btn btn-primary btn-small"><i class="icon-home-2" title="'.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'"></i> '.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_phocaguestbook');
		}

	    ToolBarHelper::help( 'screen.phocaguestbook', true );
    }
}
