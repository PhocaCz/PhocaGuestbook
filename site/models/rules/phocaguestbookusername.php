<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\Rule\UsernameRule;
use Joomla\CMS\Form\FormRule;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
FormHelper::loadRuleClass('Username');

class JFormRulePhocaguestbookUsername extends UsernameRule
{

	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_username';
		$app		= Factory::getApplication();
		$params 	= $app->getParams();

		if (preg_match("~[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+]~", $value)){
			//throw new JException(Text::_('COM_PHOCAGUESTBOOK_BAD_USERNAME' ), "105", E_USER_ERROR, $info, false);
            $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_USERNAME' ), 'warning');
			return false;
		}

		//Username exists?
		if ($params->get('disable_user_check', 0) == 0) {
			if(!$this->testUser($element, $value, $group, $input, $form)){
				//throw new Exception(Text::_('COM_PHOCAGUESTBOOK_USERNAME_EXISTS' ), "105", E_USER_ERROR, $info, false);
                $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_USERNAME_EXISTS' ), 'warning');
			    return false;
			}
		}

		return true;
	}


	public function testUser(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		$user 		= Factory::getUser();
		$userId     = $user->id;

		// Get the database object and a new query object.
		$db = Factory::getDBO();


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


		$query = $db->getQuery(true);

		// Build the query.
		$query->select('COUNT(*)');
		$query->from('#__users');
		$query->where('name = ' . $db->quote($value));

		// Get the extra field check attribute.
		$query->where($db->quoteName('id') . ' <> ' . (int) $userId);

		// Set and query the database.
		$db->setQuery($query);
		$duplicate2 = (bool) $db->loadResult();

		if ($duplicate2)
		{
			return false;
		}

		return true;
	}
}
