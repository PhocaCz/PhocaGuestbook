<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;

class JFormFieldPhocacaptcha extends JFormFieldPhocaText
{
	protected $type 		= 'phocacaptcha';
		
	protected function getInput() {
		
		$document	= JFactory::getDocument();
		$session 	= JFactory::getSession();
		$params     = JComponentHelper::getParams('com_phocaguestbook');
		$namespace	= 'pgb'.$params->get('session_suffix');
		$captchaCnt = $session->get('captcha_cnt',  0, $namespace) + 1;
						
		$id = $session->get('captcha_id', '', $namespace);
				
		switch ($id){
			default:
			case 1: //COM_PHOCAGUESTBOOK_JOOMLA_CAPTCHA -> do not use this function -> is error
			case 2: //COM_PHOCAGUESTBOOK_STANDARD_CAPTCHA
			case 3: //COM_PHOCAGUESTBOOK_MATH_CAPTCHA
			case 4: //COM_PHOCAGUESTBOOK_TTF_CAPTCHA
			case 8: //COM_PHOCAGUESTBOOK_HN_CAPTCHA
				//Add relaod java script
				$js = PhocaguestbookHelperFront::setCaptchaReloadJS();
				$document->addScriptDeclaration($js);
				$retval = '<p class="pgb-captcha-in">' .phocaguestbookHelperFront::getCaptchaUrl($id). '</p> '  . parent::getInput();
				
				break;
							
			case 5: //COM_PHOCAGUESTBOOK_RECAPTCHA_CAPTCHA
				require_once JPATH_COMPONENT.'/assets/recaptcha/recaptchalib.php';
				//Recaptcha
				$publicKey = $params->get('recaptcha_publickey');
				$theme     = $params->get('recaptcha_theme', 'red');
				$js     = 'var RecaptchaOptions = { theme : "'.$theme.'" };';
				$document->addScriptDeclaration($js);

				$session->set('captcha_cnt', $captchaCnt, $namespace); 					//Set new Retry count
				$retval = '</div><div>' . PhocaGuestbookHelperReCaptcha::recaptcha_get_html($publicKey);
				break;
				
			case 6: //COM_PHOCAGUESTBOOK_EASYCALC_CAPTCHA
				require_once JPATH_COMPONENT.'/helpers/phocaguestbookcaptcha.php';
				$captcha = PhocaguestbookHelperCaptchaEasycalc::createCaptchaData(
									$params->get('calc_opertor'), 
									$params->get('calc_operand'), 
									$params->get('calc_string'),
									$params->get('calc_max_value', 20), 
									$params->get('calc_negativ') == 0);
			
				$session->set('captcha_result', $captcha['result'], $namespace);		//Save image code to session to check with post data
				$session->set('captcha_cnt', $captchaCnt, $namespace); 					//Set new Retry count        

				unset($this->element['posticon']);  //no reload	
				$retval =  parent::getInput() . '</div><div>'.$captcha['challenge'] . '<br/>' ;
				break;
				
			case 7: //COM_PHOCAGUESTBOOK_MOLLOM_CAPTCHA
				require_once JPATH_COMPONENT.'/helpers/phocaguestbookonlinecheck.php';
				//Add relaod java script
				$js = PhocaguestbookHelperFront::setCaptchaReloadJS();
				$document->addScriptDeclaration($js);
				$mollomSession = $session->get('captcha_session', null, $namespace);

				$captcha = PhocaguestbookOnlinecheckHelper::createMollomCaptcha(
									$params->get('mollom_publickey'),
									$params->get('mollom_privatekey'),
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
