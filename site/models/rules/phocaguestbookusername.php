<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
JFormHelper::loadRuleClass('Username');

class JFormRulePhocaguestbookUsername extends JFormRuleUsername
{
	
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_username';
		$params = JComponentHelper::getParams('com_phocaguestbook');

		if (preg_match("~[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+]~", $value)){
			return new JException(JText::_('COM_PHOCAGUESTBOOK_BAD_USERNAME' ), "105", E_USER_ERROR, $info, false);
		}

		//Username exists?
		if ($params->get('disable_user_check') == 0) {
			if(!$this->testUser($element, $value, $group, $input, $form)){
				return new JException(JText::_('COM_PHOCAGUESTBOOK_USERNAME_EXISTS' ), "105", E_USER_ERROR, $info, false);
			}
		}
		
		return true;
	}
	
	
	public function testUser(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		$user 		= JFactory::getUser();
		$userId     = $user->id;
		
		// Get the database object and a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Build the query.
		$query->select('COUNT(*)');
		$query->from('#__users');
		$query->where('username = ' . $db->quote($value));

		// Get the extra field check attribute.
		$query->where($db->quoteName('id') . ' <> ' . (int) $userId);

		// Set and query the database.
		$db->setQuery($query);
		$duplicate = (bool) $db->loadResult();

		if ($duplicate)
		{
			return false;
		}

		return true;
	}
}
