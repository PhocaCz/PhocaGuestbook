<?php
/**
 * @package    phocaguestbook
 * @subpackage Base
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

require_once JPATH_COMPONENT.'/helpers/router.php';
require_once JPATH_COMPONENT.'/helpers/phocaguestbook.php';
require_once JPATH_COMPONENT.'/helpers/pagination.php';


$controller	= BaseController::getInstance('phocaguestbook');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();

return;

