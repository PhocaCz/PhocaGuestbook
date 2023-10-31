<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;
use Joomla\CMS\Form\FormRule;
use Joomla\Registry\Registry;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class JFormRulePhocaguestbookHidden extends FormRule
{

	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_hidden';

		//Get POST Data - - - - - - - - -
		if ($value != '') {
			//return new JException(JText::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), "200", E_ERROR, $info, false);  //no user error! <- system error

            $app = Factory::getApplication();
            $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), 'warning');

            Factory::getApplication()->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), 'warning');

            return false;
		}

		return true;
	}
}
