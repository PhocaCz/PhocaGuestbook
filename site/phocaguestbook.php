<?php
/**
 * @package    phocaguestbook
 * @subpackage Base
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
//-- No direct access
defined('_JEXEC') || die('=;)');

require_once JPATH_COMPONENT.'/helpers/router.php';
require_once JPATH_COMPONENT.'/helpers/phocaguestbook.php';
require_once JPATH_COMPONENT.'/helpers/pagination.php';


$controller	= JControllerLegacy::getInstance('phocaguestbook');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

return;

