<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

JFormHelper::loadRuleClass('Url');

class JFormRulePhocaguestbookHomesite extends JFormRuleUrl
{
	
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_website';
		$params = JComponentHelper::getParams('com_phocaguestbook');	
		
		//URL FORMAT
		if(!parent::test($element, $value, $group, $input, $form)){
			return new JException(JText::_('COM_PHOCAGUESTBOOK_BAD_WEBSITE' ), "105", E_USER_ERROR, $info, false);
		}
		
		return true;
	}
}
