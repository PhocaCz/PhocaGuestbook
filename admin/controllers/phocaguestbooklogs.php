<?php
/**
 * @package    phocaguestbook
 * @subpackage Controllers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');


//-- Import the Class JControllerAdmin
jimport('joomla.application.component.controlleradmin');

/**
 * phocaguestbook Controller.
 */
class PhocaguestbookControllerPhocaguestbooklogs extends JControllerAdmin
{
	
    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'phocaguestbooklog', $prefix = 'phocaguestbookModel'
    , $config = array('ignore_request' => true))
    {
		
        //$doSomething = 'here';
        return parent::getModel($name, $prefix, $config);
    }
    
}
