<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
class PhocaguestbookHelperFront
{
		
	public static function setCaptchaReloadJS()
	{
		$js  = 'var pcid = 0;'."\n"
		     . 'function reloadCaptcha() {' . "\n"
			 . 'now = new Date();' . "\n"
			 . 'var capObj = document.getElementById(\'phocacaptcha\');' . "\n"
			 . 'if (capObj) {' . "\n"
			 . 'capObj.src = capObj.src + (capObj.src.indexOf(\'?\') > -1 ? \'&amp;pcid[\'+ pcid +\']=\' : \'?pcid[\'+ pcid +\']=\') + Math.ceil(Math.random()*(now.getTime()));' . "\n"
			 . 'pcid++;' . "\n"
			 . ' }' . "\n"
			 . '}'. "\n";
			
			return $js;
	}
	
	public static function checkWordChar($string, $length) 
	{
		if (JString::strlen($string) < $length || JString::strlen($string) == $length) {
			return $string;
		} else {
			return false;
		}
	}
	
	public static function wordDelete($string,$length,$end) 
	{
		if (JString::strlen($string) < $length || JString::strlen($string) == $length) {
			return $string;
		} else {
			return JString::substr($string, 0, $length) . $end;
		}
	}
	
	public static function getDateFormat($dateFormat) 
	{
		switch ($dateFormat) {
			case 1:
			$dateFormat = 'd. F Y';
			break;
			case 2:
			$dateFormat = 'd/m/y';
			break;
			case 3:
			$dateFormat = 'd. m. Y';
			break;
		}
		return $dateFormat;
	}
	
	public static function getInfo() {
		return '<div style="tex'.'t-alig'
		.'n: right;">Po'.'were'.'d b'.'y'
		.' <a hre'.'f="ht'.'t'.'p://ww'.'w.pho'
		.'ca.c'.'z/phocag'.'uestbook" targe'.'t="_bl'
		.'ank" tit'.'le="Ph'.'oca Guestb'.'ook">P'
		.'hoca Gues'.'tbook</a></div>';
	}

	public static function getCaptchaId($captcha_array) {
		if ($captcha_array) {
			$elements = sizeof($captcha_array);
			$rand = mt_rand(0,$elements-1);
			return  $captcha_array[$rand];
		}	
		return '';
	}	

	public static function getRandomString($length = '') {		
		$code = md5(uniqid(rand(), true));
		if ($length != '' && (int)$length > 0) {
			$length = $length - 1;
			return chr(rand(97,122)) . substr($code, 0, $length);
		} else {
			return chr(rand(97,122)) . $code;
		}
	}
	
	public static function setHiddenFieldPos($title, $name, $email, $website, $content) {
		$form = array();
		if ((int)$title > 0) {
			$form[] = 1;
		}
		if ((int)$name > 0) {
			$form[] = 2;
		}
		if ((int)$email > 0) {
			$form[] = 3;
		}
		if ((int)$website > 0) {
			$form[] = 4;
		}
		if ((int)$content > 0) {
			$form[] = 5;
		}
		$value = mt_rand(0,count($form) - 1);
		
		return $form[$value];
	}
	
	public static function getLangSef($langCode) {
		$langSef = '';
		if ($langCode != '') {
			jimport('joomla.language.helper');
			$code = JLanguageHelper::getLanguages('lang_code');
			if (isset($code[$langCode]->sef)) {
				$langSef = $code[$langCode]->sef;
			}
		}
		return $langSef;
	}
	
	public static function checkSpecificId($image = 0) {
		$paramsC 		= JComponentHelper::getParams('com_phocaguestbook') ;
		$specificItemid = $paramsC->get( 'specific_itemid', '' );
		$itemids		= explode(',', $specificItemid);
		
		$sec = 0;
		if (!empty($itemids) && isset($itemids[0]) && (int)$itemids[0] > 0) {
			$itemid	= JRequest::getCmd('Itemid');
			if (!in_array($itemid, $itemids)) {
				$sec = 1;
			}
		}
		if ($sec == 0) {
			return true;
		} else {
			// Save server resources - no redirect, no information for spam bots
			if ($image == 0) {
				echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"'
				.' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
				.'<html xmlns="http://www.w3.org/1999/xhtml">'
				.'<head><title>404 - Error: 404</title></head>'
				.'<body><div>404 - Site not found</div></body></html>';
				exit;
			} else {
				echo '';
				exit;
			}
		}
	
	}
	
	public static function getCaptchaUrl($id) {		
		$paramsC 		= JComponentHelper::getParams('com_phocaguestbook') ;
		$captcha_url	= $paramsC->get( 'captcha_url', 1 );
		
		$index = 'index.php';
		$app = JFactory::getApplication();
		
		if ($app->getLanguageFilter()) {
			$lang 		= JFactory::getLanguage();
			$langCode 	= $lang->getTag();
			$langSef 	= PhocaguestbookHelperFront::getLangSef($langCode);
			if ($langSef != '') {
				$index = 'index.php/'.$langSef.'/';
			}
		}
		switch ($captcha_url) {
			case 2:		// Full Path
				return '<img src="'. JURI::base(false).''.$index.'?option=com_phocaguestbook&view=guestbooki&id='.$id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.JText::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 3:		// No Itemid Full Path
				return '<img src="'. JURI::base(false).''.$index.'?option=com_phocaguestbook&view=guestbooki&id='.$id.'&phocasid='. md5(uniqid(time())).'" alt="'.JText::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';	
				break;
			case 4:		// No language prefix - standard path
				return '<img src="'. JURI::base(true).'/index.php?option=com_phocaguestbook&view=guestbooki&id='.$id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.JText::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 5:		// No language prefix - full path
				return '<img src="'. JURI::base(false).'index.php?option=com_phocaguestbook&view=guestbooki&id='.$id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.JText::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 6:		// No Itemid Full Path No Language
				return '<img src="'. JURI::base(false).'index.php?option=com_phocaguestbook&view=guestbooki&id='.$id.'&phocasid='. md5(uniqid(time())).'" alt="'.JText::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 7:		// No Itemid Relative Path No Language
				return '<img src="'. JURI::base(true).'/index.php?option=com_phocaguestbook&view=guestbooki&id='.$id.'&phocasid='. md5(uniqid(time())).'" alt="'.JText::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 1:	default:	// Standard Path
				return '<img src="'. JURI::base(true).'/'.$index.'?option=com_phocaguestbook&view=guestbooki&id='.$id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.JText::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
		}
		
		return false;
	
	}
	
}
?>
