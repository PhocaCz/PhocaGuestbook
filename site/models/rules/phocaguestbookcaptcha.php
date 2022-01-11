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

class JFormRulePhocaguestbookCaptcha extends FormRule
{
	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{

		//E_ERROR, E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
		$info = array();
		$info['field'] = 'guestbook_captcha';
		$app		= Factory::getApplication();
		$params 	= $app->getParams();
		$session = $app->getSession();
		$namespace = 'pgb'.$params->get('session_suffix', '');

		$captchaId = $session->get('captcha_id',     null, $namespace);
		$value_exp = $session->get('captcha_result', null, $namespace);


		$session->clear('captcha_id',     $namespace);
		$session->clear('captcha_result', $namespace);
        //$session->set('captcha_id',    '', $namespace);
	    //$session->set('captcha_result', '', $namespace);

		$recaptcha_connection_method = $params->get('recaptcha_connection_method', 1);


		switch ($captchaId) {
			default:
			case 1: //COM_PHOCAGUESTBOOK_JOOMLA_CAPTCHA -> do not use this check
				//return new JException(Text::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), "105", E_USER_ERROR, $info, false);
                 $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), 'warning');
			        return false;
				break;
			case 2: //COM_PHOCAGUESTBOOK_STANDARD_CAPTCHA
			case 3: //COM_PHOCAGUESTBOOK_MATH_CAPTCHA
			case 4: //COM_PHOCAGUESTBOOK_TTF_CAPTCHA
			case 6: //COM_PHOCAGUESTBOOK_EASYCALC_CAPTCHA
			case 8: //COM_PHOCAGUESTBOOK_HN_CAPTCHA

				if (!$value_exp) {
					//return new JException(Text::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), "105", E_USER_ERROR, $info, false);
                     $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED' ), 'warning');
			        return false;
				} else if (!$value) {
					//return new JException(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
                     $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), 'warning');
			            return false;
				} else if ($value != $value_exp) {
					//return new JException(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
                     $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), 'warning');
			            return false;
				}
				break;
			case 5: //COM_PHOCAGUESTBOOK_RECAPTCHA_CAPTCHA
				require_once JPATH_COMPONENT.'/assets/recaptcha/recaptchalib.php';
				//We need to reed recaptcha fields!
				$app = Factory::getApplication();
				$challange = $app->input->post->get('recaptcha_challenge_field', 'ABC', 'string');
				$response = $app->input->post->get('recaptcha_response_field', 'DEF', 'string');
				$privateKey = $params->get('recaptcha_privatekey', '');

				$resp = PhocaguestbookHelperReCaptcha::recaptcha_check_answer ($privateKey,
									$_SERVER["REMOTE_ADDR"],
									$challange,
									$response);
				if (!$resp->is_valid) {
					// What happens when the CAPTCHA was entered incorrectly
					//return new JException(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
                    $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), 'warning');
			        return false;
				}
				break;
			case 7: //COM_PHOCAGUESTBOOK_MOLLOM_CAPTCHA -> not implemented yet!
				require_once JPATH_COMPONENT.'/helpers/phocaguestbookonlinecheck.php';
				$mollomSession = $session->get('captcha_session', null, $namespace);

				$resp = PhocaguestbookOnlinecheckHelper::checkMollomCaptcha(
									$params->get('mollom_publickey', ''),
									$params->get('mollom_privatekey', ''),
									$mollomSession, $value);

				if ($resp == false) {
					// What happens when the CAPTCHA was entered incorrectly
					//return new JException(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), "105", E_USER_ERROR, $info, false);
                    $app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_WRONG_CAPTCHA' ), 'warning');
			        return false;
				}
				break;

			case 9://COM_PHOCAGUESTBOOK_RE_CAPTCHA 2



				$secretKey	= $params->get( 'recaptcha_privatekey', '' );
				//$response 	= $app->input->post->get('g-recaptcha-response', '', 'string');
				//$response	= $ POST['g-recaptcha-response'];
				$response 	= $app->input->post->get('g-recaptcha-response', '', 'string');
				$remoteIp	= $_SERVER['REMOTE_ADDR'];


				try {

					$url = 'https://www.google.com/recaptcha/api/siteverify';
					$data = ['secret'   => $secretKey,
							 'response' => $response,
							 'remoteip' => $remoteIp];

					$options = [
						'http' => [
							'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
							'method'  => 'POST',
							'content' => http_build_query($data)
						]
					];


					if ($recaptcha_connection_method == 1) {
						$context  = stream_context_create($options);
						$result = file_get_contents($url, false, $context);

					//$resultString = print_r($result, true);
					//PhocacartLog::add(1, 'Ask a Question - Captcha Result', 0, $resultString);
					} else {
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $url);
						curl_setopt($curl, CURLOPT_POST, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						$result = curl_exec($curl);
					}

					if (empty($result) || $result == '') {
						return false;
					}

					return json_decode($result)->success;
				}
				catch (Exception $e) {
					return null;
				}

			break;

		} //Switch

		return true;
	}
}
