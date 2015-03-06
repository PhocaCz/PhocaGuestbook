<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;
 
class JFormRulePhocaguestbookCaptcha extends JFormRule
{
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{		
		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_captcha';
		$params = JComponentHelper::getParams('com_phocaguestbook');
		$session = JFactory::getSession();
		$namespace = 'pgb'.$params->get('session_suffix');
		
		$captchaId = $session->get('captcha_id',     null, $namespace);
		$value_exp = $session->get('captcha_result', null, $namespace);
		$session->clear('captcha_id',     $namespace);
		$session->clear('captcha_result', $namespace);
						
		switch ($captchaId) {
			default:
			case 1: //COM_PHOCAGUESTBOOK_JOOMLA_CAPTCHA -> do not use this check
				return new JException(JText::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), "105", E_USER_ERROR, $info, false);
				break;
			case 2: //COM_PHOCAGUESTBOOK_STANDARD_CAPTCHA
			case 3: //COM_PHOCAGUESTBOOK_MATH_CAPTCHA
			case 4: //COM_PHOCAGUESTBOOK_TTF_CAPTCHA
			case 6: //COM_PHOCAGUESTBOOK_EASYCALC_CAPTCHA
			case 8: //COM_PHOCAGUESTBOOK_HN_CAPTCHA

				if (!$value_exp) {
					return new JException(JText::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), "105", E_USER_ERROR, $info, false);
				} else if (!$value) {
					return new JException(JText::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
				} else if ($value != $value_exp) {
					return new JException(JText::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
				}
				break;
			case 5: //COM_PHOCAGUESTBOOK_RECAPTCHA_CAPTCHA
				require_once JPATH_COMPONENT.'/assets/recaptcha/recaptchalib.php';
				//We need to reed recaptcha fields!
				$app = JFactory::getApplication();
				$challange = $app->input->post->get('recaptcha_challenge_field', 'ABC', 'string');
				$response = $app->input->post->get('recaptcha_response_field', 'DEF', 'string');
				$privateKey = $params->get('recaptcha_privatekey');
				
				$resp = PhocaguestbookHelperReCaptcha::recaptcha_check_answer ($privateKey,
									$_SERVER["REMOTE_ADDR"],
									$challange,
									$response);
				if (!$resp->is_valid) {
					// What happens when the CAPTCHA was entered incorrectly
					return new JException(JText::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
				}
				break;
			case 7: //COM_PHOCAGUESTBOOK_MOLLOM_CAPTCHA -> not implemented yet!
				require_once JPATH_COMPONENT.'/helpers/phocaguestbookonlinecheck.php';
				$mollomSession = $session->get('captcha_session', null, $namespace);

				$resp = PhocaguestbookOnlinecheckHelper::checkMollomCaptcha(
									$params->get('mollom_publickey'),
									$params->get('mollom_privatekey'),
									$mollomSession, $value);
									
				if ($resp == false) {
					// What happens when the CAPTCHA was entered incorrectly
					return new JException(JText::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
				}
				break;
					
		} //Switch

		return true;
	}
}
