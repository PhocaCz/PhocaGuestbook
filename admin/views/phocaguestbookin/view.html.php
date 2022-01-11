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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;


/**
 * HTML View class for the phocaguestbook Component.
 *
 * @package    phocaguestbook
 * @subpackage Views
 */
class PhocaguestbookViewPhocaguestbookIn extends HtmlView
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
		require_once JPATH_COMPONENT.'/helpers/phocaguestbook.php';
		HTMLHelper::stylesheet( 'media/com_phocaguestbook/css/administrator/phocaguestbook.css' );

		$this->tmpl['version'] = phocaguestbookHelper::getPhocaVersion();

		$this->t	= PhocaguestbookHelper::setVars('info');
		$this->r	= new PhocaguestbookRenderAdminview();
		$this->t['component_head'] 	= 'COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK';
		$this->t['component_links']	= $this->r->getLinks(1);
		$this->t['version'] = PhocaguestbookHelper::getPhocaVersion();
		$this->addToolbar();

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
        ToolBarHelper::title(Text::_('COM_PHOCAGUESTBOOK_PG_INFO'), 'info');

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = JToolBar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocaguestbook" class="btn btn-small"><i class="icon-home-2" title="'.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'"></i> '.Text::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_phocaguestbook');
		}

	    ToolBarHelper::help( 'screen.phocaguestbook', true );
    }
}
