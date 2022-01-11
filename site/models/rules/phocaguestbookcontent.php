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


class JFormRulePhocaguestbookContent extends FormRule
{

	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_content';

		$app		= Factory::getApplication();
		$params 	= $app->getParams();

		//Maximum of character, they will be saved in database
		$value	= substr($value, 0, $params->get('max_char', 2000));

		return true; //parent::test
	}
}
