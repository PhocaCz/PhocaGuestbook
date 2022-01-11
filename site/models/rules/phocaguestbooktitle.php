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
use Joomla\CMS\Language\Text;

class JFormRulePhocaguestbookTitle extends FormRule
{

	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_title';

		if (preg_match("~[<|>]~",$value)) {
			//return new JException(Text::_('COM_PHOCAGUESTBOOK_BAD_SUBJECT' ), "105", E_USER_ERROR, $info, false);
            $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_SUBJECT' ), 'warning');
			return false;
		}

		return true;
	}
}
