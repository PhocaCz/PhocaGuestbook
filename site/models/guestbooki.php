<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

require_once JPATH_COMPONENT.'/helpers/phocaguestbookcaptcha.php';

class PhocaguestbookModelGuestbooki extends JModelLegacy
{
	var $_data = null;
	
	function &getData()
	{
		//reuse old captcha id		
		$app		= JFactory::getApplication();
		$paramsC 	= $app->getParams();
		$session 	= JFactory::getSession();
		$captchaId = $session->get('captcha_id','', 'pgb'.$paramsC->get('session_suffix'));//Get captcha type

		/*
		$app		= JFactory::getApplication();
		$paramsC 	= $app->getParams();
		$enable_captcha = $paramsC->get( 'enable_captcha', 1 );

		// no recaptcha/joomla captcha!
		$enable_captcha = array_diff($enable_captcha, array(1, 5));
		$captchaId		= PhocaguestbookHelperFront::getCaptchaId($enable_captcha);
		*/
		switch ((int)$captchaId) {
			case 8:
				$captchaClass = new PhocaguestbookHelperCaptchaHn;
				$this->_data['captcha'] = $captchaClass->createImageData();
				break;
			case 4:
				$this->_data['captcha'] = PhocaguestbookHelperCaptchaTTF::createImageData();
				break;
			case 3:
				$this->_data['captcha'] = PhocaguestbookHelperCaptchaMath::createImageData();
				break;
			default:
				$captchaId  = 2; //no break
			case 2:
				$this->_data['captcha'] = PhocaguestbookHelperCaptchaStd::createImageData();
				break;
		}
		$this->_data['captchaid'] = $captchaId;
		
		return $this->_data;
	}
}
?>
