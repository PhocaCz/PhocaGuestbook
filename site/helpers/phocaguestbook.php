<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

use Joomla\String\StringHelper;

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
		if (StringHelper::strlen($string) < $length || StringHelper::strlen($string) == $length) {
			return $string;
		} else {
			return false;
		}
	}

	public static function wordDelete($string,$length,$end)
	{
		if (StringHelper::strlen($string) < $length || StringHelper::strlen($string) == $length) {
			return $string;
		} else {
			return StringHelper::substr($string, 0, $length) . $end;
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

		PluginHelper::importPlugin('phocatools');
        $results = Factory::getApplication()->triggerEvent('PhocatoolsOnDisplayInfo', array('NjI5NTYxNTY5NQ=='));
        if (isset($results[0]) && $results[0] === true) {
            return '';
        }


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
			$code = LanguageHelper::getLanguages('lang_code');
			if (isset($code[$langCode]->sef)) {
				$langSef = $code[$langCode]->sef;
			}
		}
		return $langSef;
	}

	public static function checkSpecificId($image = 0) {

		$app		= Factory::getApplication();
		$paramsC 	= $app->getParams();
		$specificItemid = $paramsC->get( 'specific_itemid', '' );
		$itemids		= explode(',', $specificItemid);

		$sec = 0;
		if (!empty($itemids) && isset($itemids[0]) && (int)$itemids[0] > 0) {
			$itemid	= $app->input->get('Itemid');

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
		$app		= Factory::getApplication();
		$paramsC 	= $app->getParams();
		$captcha_url	= $paramsC->get( 'captcha_url', 1 );

		$index = 'index.php';
		$app = Factory::getApplication();

		if ($app->getLanguageFilter()) {
			$lang 		= Factory::getLanguage();
			$langCode 	= $lang->getTag();
			$langSef 	= PhocaguestbookHelperFront::getLangSef($langCode);
			if ($langSef != '') {
				$index = 'index.php/'.$langSef.'/';
			}
		}
		switch ($captcha_url) {
			case 2:		// Full Path
				return '<img src="'. Uri::base(false).''.$index.'?option=com_phocaguestbook&view=guestbooki&format=raw&id='.$id.'&Itemid='.$app->input->get('Itemid', 0, 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.Text::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 3:		// No Itemid Full Path
				return '<img src="'. Uri::base(false).''.$index.'?option=com_phocaguestbook&view=guestbooki&format=raw&id='.$id.'&phocasid='. md5(uniqid(time())).'" alt="'.Text::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 4:		// No language prefix - standard path
				return '<img src="'. Uri::base(true).'/index.php?option=com_phocaguestbook&view=guestbooki&format=raw&id='.$id.'&Itemid='.$app->input->get('Itemid', 0, 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.Text::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 5:		// No language prefix - full path
				return '<img src="'. Uri::base(false).'index.php?option=com_phocaguestbook&view=guestbooki&format=raw&id='.$id.'&Itemid='.$app->input->get('Itemid', 0, 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.Text::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 6:		// No Itemid Full Path No Language
				return '<img src="'. Uri::base(false).'index.php?option=com_phocaguestbook&view=guestbooki&format=raw&id='.$id.'&phocasid='. md5(uniqid(time())).'" alt="'.Text::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 7:		// No Itemid Relative Path No Language
				return '<img src="'. Uri::base(true).'/index.php?option=com_phocaguestbook&view=guestbooki&format=raw&id='.$id.'&phocasid='. md5(uniqid(time())).'" alt="'.Text::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
			case 1:	default:	// Standard Path
				return '<img src="'. Uri::base(true).'/'.$index.'?option=com_phocaguestbook&view=guestbooki&format=raw&id='.$id.'&Itemid='.$app->input->get('Itemid', 0, 'int').'&phocasid='. md5(uniqid(time())).'" alt="'.Text::_('COM_PHOCAGUESTBOOK_CAPTCHA_IMAGE').'" id="phocacaptcha" style="max-width: none;" />';
				break;
		}

		return false;

	}

}
?>
