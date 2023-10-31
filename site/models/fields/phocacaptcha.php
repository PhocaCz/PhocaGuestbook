<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\TextField;

FormHelper::loadFieldClass('text');
class JFormFieldPhocacaptcha extends TextField
{
	protected $type 		= 'phocacaptcha';

	protected function getInput() {

		$document	= Factory::getDocument();

		$app		= Factory::getApplication();
		$session 	= $app->getSession();
		$params 	= $app->getParams();
		$namespace	= 'pgb'.$params->get('session_suffix', '');
		$captchaCnt = $session->get('captcha_cnt',  0, $namespace) + 1;

		$id = $session->get('captcha_id', '', $namespace);

		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

		switch ($id){
			default:
			case 1: //COM_PHOCAGUESTBOOK_JOOMLA_CAPTCHA -> do not use this function -> is error
			case 2: //COM_PHOCAGUESTBOOK_STANDARD_CAPTCHA
			case 3: //COM_PHOCAGUESTBOOK_MATH_CAPTCHA
			case 4: //COM_PHOCAGUESTBOOK_TTF_CAPTCHA
			case 8: //COM_PHOCAGUESTBOOK_HN_CAPTCHA
				//Add relaod java script
				$js = PhocaguestbookHelperFront::setCaptchaReloadJS();
				//$document->addScriptDeclaration($js);
				$wa->addInlineScript($js);

				$retval = '<p class="pgb-captcha-in">' .phocaguestbookHelperFront::getCaptchaUrl($id). '</p> '  . parent::getInput();

				break;

			case 5: //COM_PHOCAGUESTBOOK_RECAPTCHA_CAPTCHA
				require_once JPATH_COMPONENT.'/assets/recaptcha/recaptchalib.php';
				//Recaptcha
				$publicKey = $params->get('recaptcha_publickey', '');
				$theme     = $params->get('recaptcha_theme', 'red');
				$js     = 'var RecaptchaOptions = { theme : "'.$theme.'" };';
				//$document->addScriptDeclaration($js);
				$wa->addInlineScript($js);

				$session->set('captcha_cnt', $captchaCnt, $namespace); 					//Set new Retry count
				//$retval = '</div><div>' . PhocaGuestbookHelperReCaptcha::recaptcha_get_html($publicKey);
				$retval = '</div><div>' . PhocaGuestbookHelperReCaptcha::recaptcha_get_html($publicKey, null, true);
				break;

			case 9: //COM_PHOCAGUESTBOOK_RECAPTCHA_CAPTCHA 2
				//require_once JPATH_COMPONENT.'/assets/recaptcha/recaptchalib.php';
				//Recaptcha
				//$publicKey = $params->get('recaptcha_publickey');
				//$theme     = $params->get('recaptcha_theme', 'red');
				//$js     = 'var RecaptchaOptions = { theme : "'.$theme.'" };';
				//$document->addScriptDeclaration($js);


				$session->set('captcha_cnt', $captchaCnt, $namespace); 					//Set new Retry count

				$siteKey	= strip_tags(trim($params->get( 'recaptcha_publickey', '' )));

				//$document->addScript('https://www.google.com/recaptcha/api.js');
				$wa->registerAndUseScript('com_phocaguestbook.recaptcha', 'https://www.google.com/recaptcha/api.js', array('version' => 'auto'));
				$retval =  '</div><div><div class="g-recaptcha" data-sitekey="'.$siteKey.'"></div>';

				break;

			case 6: //COM_PHOCAGUESTBOOK_EASYCALC_CAPTCHA
				require_once JPATH_COMPONENT.'/helpers/phocaguestbookcaptcha.php';
				$captcha = PhocaguestbookHelperCaptchaEasycalc::createCaptchaData(
									$params->get('calc_opertor', 0),
									$params->get('calc_operand', 2),
									$params->get('calc_string', 0),
									$params->get('calc_max_value', 20),
									$params->get('calc_negativ', 0) == 0);

				$session->set('captcha_result', $captcha['result'], $namespace);		//Save image code to session to check with post data
				$session->set('captcha_cnt', $captchaCnt, $namespace); 					//Set new Retry count

				unset($this->element['posticon']);  //no reload
				$retval =  parent::getInput() . '</div><div>'.$captcha['challenge'] . '<br/>' ;
				break;

			case 7: //COM_PHOCAGUESTBOOK_MOLLOM_CAPTCHA
				require_once JPATH_COMPONENT.'/helpers/phocaguestbookonlinecheck.php';
				//Add relaod java script
				$js = PhocaguestbookHelperFront::setCaptchaReloadJS();
				//$document->addScriptDeclaration($js);
				$wa->addInlineScript($js);
				$mollomSession = $session->get('captcha_session', null, $namespace);

				$captcha = PhocaguestbookOnlinecheckHelper::createMollomCaptcha(
									$params->get('mollom_publickey', ''),
									$params->get('mollom_privatekey', ''),
									$mollomSession);

				$session->set('captcha_session', $captcha['session_id'], $namespace);
				$session->set('captcha_cnt', $captchaCnt++, $namespace); 				//Retry count

				$retval = '<p class="pgb-captcha-in">'.$captcha['html'] . '</p>' . parent::getInput();
				break;
		}

		return $retval;
	}


}
?>
