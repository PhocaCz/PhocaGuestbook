<?php
/**
 * @package    phocaguestbook
 * @subpackage Base
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');

if( ! JFactory::getUser()->authorise('core.manage', 'com_phocaguestbook'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('phocaguestbookHelper', JPATH_COMPONENT.'/helpers/phocaguestbook.php');

$controller	= JControllerLegacy::getInstance('phocaguestbook');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
