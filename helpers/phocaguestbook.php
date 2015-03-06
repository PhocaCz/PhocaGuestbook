<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');
jimport('joomla.filesystem.folder');

/**
 * phocaguestbook Helper.
 *
 * @package    phocaguestbook
 * @subpackage Helpers
 */
class PhocaguestbookHelper
{
	public static $extension = 'com_phocaguestbook';
    /**
     * Configure the Linkbar.
     *
     * @param string $viewName The name of the active view.
     */
    public static function addSubmenu($viewName = 'phocaguestbookcp')
    {
		
        JHtmlSidebar::addEntry(
        JText::_('COM_PHOCAGUESTBOOK_CONTROL_PANEL')
        , 'index.php?option=com_phocaguestbook&view=phocaguestbookcp'
        , $viewName == 'phocaguestbookcp'
        );
        
        JHtmlSidebar::addEntry(
        JText::_('COM_PHOCAGUESTBOOK_ITEMS')
        , 'index.php?option=com_phocaguestbook&view=phocaguestbooks'
        , $viewName == 'phocaguestbooks'
        );

        JHtmlSidebar::addEntry(
        JText::_('COM_PHOCAGUESTBOOK_GUESTBOOKS')
        , 'index.php?option=com_categories&extension=com_phocaguestbook'
        , $viewName == 'categories'
        );
        
        JHtmlSidebar::addEntry(
        JText::_('COM_PHOCAGUESTBOOK_LOGGING')
        , 'index.php?option=com_phocaguestbook&view=phocaguestbooklogs'
        , $viewName == 'phocaguestbooklogs'
        );
        
        JHtmlSidebar::addEntry(
        JText::_('COM_PHOCAGUESTBOOK_INFO')
        , 'index.php?option=com_phocaguestbook&view=phocaguestbookin'
        , $viewName == 'phocaguestbookin'
        );
    }
    
	/**
	 * Gets a list of the actions that can be performed.
	 */
	public static function getActions($categoryId = 0)
	{

		// Reverted a change for version 2.5.6
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId)) {
			$assetName = 'com_phocaguestbook';
		}
		else {
			$assetName = 'com_phocaguestbook.category.'.(int) $categoryId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete', 'core.post', 'core.post.reply'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	
	/**
	 * Method to get Phoca Version
	 * @return string Version of Phoca Component
	 */
	public static function getPhocaVersion()
	{
		$folder = JPATH_ADMINISTRATOR . '/components/com_phocaguestbook';
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE . '/components/com_phocaguestbook';
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = '';
		if (count($xmlFilesInDir)) {
			foreach ($xmlFilesInDir as $xmlfile) {
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.'/'.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}
		
		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}
	
}
