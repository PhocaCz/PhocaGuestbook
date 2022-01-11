<?php
/**
 * @package    phocaguestbook
 * @subpackage Base
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

if( ! Factory::getUser()->authorise('core.manage', 'com_phocaguestbook'))
{
	throw new Exception(Text::_('COM_PHOCAGUESTBOOK_ALERTNOAUTHOR'), 404);
	return false;
}

require JPATH_ADMINISTRATOR . '/components/com_phocaguestbook/libraries/autoloadPhoca.php';
JLoader::register('phocaguestbookHelper', JPATH_COMPONENT.'/helpers/phocaguestbook.php');


require_once( JPATH_COMPONENT.'/helpers/renderadminview.php' );
require_once( JPATH_COMPONENT.'/helpers/renderadminviews.php' );
$controller	= BaseController::getInstance('phocaguestbook');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
