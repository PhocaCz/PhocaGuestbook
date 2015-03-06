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
class PhocaguestbookViewPhocaguestbookIn extends JViewLegacy
{
	/**
     * @var array
     */
    protected $tmpl;
    
    /**
     * Phoca Guestbook view display method
     */
    public function display($tpl = null)
    {
		require_once JPATH_COMPONENT.'/helpers/phocaguestbook.php';
		
		$this->tmpl['version'] = phocaguestbookHelper::getPhocaVersion();

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
		$canDo = phocaguestbookHelper::getActions(NULL);
		
		//TOOLBAR
        JToolBarHelper::title(JText::_('COM_PHOCAGUESTBOOK_PG_INFO'), 'info');
		
		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = JToolBar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocaguestbook" class="btn btn-small"><i class="icon-home-2" title="'.JText::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'"></i> '.JText::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);
		
		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_phocaguestbook');
		}
		
	    JToolBarHelper::help( 'screen.phocaguestbook', true );	   
    }
}
