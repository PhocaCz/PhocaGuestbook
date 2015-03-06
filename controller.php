<?php
/**
 * @package    phocaguestbook
 * @subpackage Base
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * phocaguestbook default Controller.
 *
 * @package    phocaguestbook
 * @subpackage Controllers
 */
class PhocaguestbookController extends JControllerLegacy
{
	protected $default_view = 'phocaguestbookcp';
	
    /**
     * Method to display the view.
     *
     * @param bool $cachable
     * @param bool $urlparams
     *
     * @return void
     */
    public function display($cachable = false, $urlparams = false)
    {
		$view	= JFactory::getApplication()->input->get('view');
		$layout	= JFactory::getApplication()->input->get('layout');
		$id     = JFactory::getApplication()->input->getInt('id');
	
		phocaguestbookHelper::addSubmenu($view);
        parent::display();
        
        return $this;
    }
}
