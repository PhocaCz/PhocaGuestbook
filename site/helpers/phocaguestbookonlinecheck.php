<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
class PhocaguestbookOnlineCheckHelper
{
   public static function checkSpam(&$data, &$params, &$logging){

		$return_is_spam = false;

		/* Akismet , see akismet.com
		 * after checking, that everything is valid and the captcha is good,
		 * we ask the akismet Service if this post is a spam,
		 * given that akismet check is enabled in the config
		 */
		if ($params->get('enable_akismet', 0) == 1) {
			$logging->content_akismet = 1;
			$tmp = '';

			$akismetSuspectSpam = PhocaguestbookOnlineCheckHelper::checkAkismet(
				$params->get('akismet_api_key', ''),	$params->get('akismet_url', ''),
				$data['username'],	$data['mail'],	$data['homesite'],	$data['content'], $tmp);

			// Error while setting Akismet
			if ($tmp != '') {
				Factory::getApplication()->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_AKISMET_NOT_CORRECTLY_SET' ) . $tmp, 'error');
				$return_is_spam = true;
				$logging->content_akismet = 3;
			}

			if ($akismetSuspectSpam) {
				$return_is_spam = true;
				$logging->content_akismet = 2;
			}

			$logging->content_akismet_txt = $tmp;
		}

        // Mollom
        // Further informations: http://mollom.com/
        if ($params->get('enable_mollom', 0) == 1 ){
			$logging->content_mollom = 1;
			$tmp = '';

			$mollomSuspectSpam = PhocaguestbookOnlineCheckHelper::checkMollom(
				$params->get('mollom_publickey', ''),	$params->get('mollom_privatekey', ''), null,
				$data['subject'], $data['username'], $data['mail'], $data['homesite'], $data['content'], $tmp);

            if($tmp['spam'] == 'spam')
            {
				$return_is_spam = true;
				$logging->content_mollom = 2;
            }

            $logging->content_mollom_txt = implode(",", array_keys($tmp)) . '->' . implode(",", $tmp);
        }

		return $return_is_spam;
	}


	public static function checkIpAddress($ipAddr, &$params, &$logging)
	{
		$return_is_spam = false;

		//Local (saved) IPs
		$logging->ip_list = 1;
		$ip_ban			= trim( $params->get( 'ip_ban', '' ) );
		$ip_ban_array	= explode( ',', $ip_ban );
		if (is_array($ip_ban_array)) {
			foreach ($ip_ban_array as $valueIp) {
				if ($valueIp != '' && strstr($ipAddr, trim($valueIp)) && strpos($ipAddr, trim($valueIp))==0) {
					$return_is_spam = true;
					$logging->ip_list = 2;
				}
			}
		}

		// StopForumSpam - Check the IP Address
		// Further informations: http://www.stopforumspam.com
		if($params->get('enable_stopforumspam', 0))
		{
			$url = 'http://www.stopforumspam.com/api?ip='.$ipAddr;
			$response = false;
			$is_spam = false;
			$logging->ip_stopforum = 1;

			if(function_exists('curl_init')) {
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				curl_close($ch);
			}

			if($response) {
				preg_match('#<appears>(.*)</appears>#', $response, $out);
				$is_spam = $out[1];
			} else {
				$handle = @ fopen($url, 'r');
				if($handle) {
					while(!feof($handle)) {
						$line = fgets($handle, 1024);
						$response .= $line;
						if(preg_match('#<appears>(.*)</appears>#', $line, $out)) {
							$is_spam = $out[1];
							//break;
						}
					}
					fclose($handle);
				}
			}

			if($is_spam == 'yes' && $response) {
				$return_is_spam = true;
				$logging->ip_stopforum = 2;
			}
			$logging->ip_stopforum_txt = strip_tags($response);  //strip tags to save memory
		}

		// Honeypot Project
		// Further informations: http://www.projecthoneypot.org/home.php
		// BL ACCESS KEY  - http://www.projecthoneypot.org/httpbl_configure.php
		if($params->get('enable_projecthoneypot', 0)) {
			require_once JPATH_COMPONENT.'/assets/honeypot/honeypot.php';
			$http_blKey = $params->get('projecthoneypot_key', '');
			$logging->ip_honeypot = 1;

			if($http_blKey)	{
				$http_bl = new http_bl($http_blKey);
				$result = $http_bl->query($ipAddr);

				if($result == 2) {
					$return_is_spam = true;
				}
				$logging->ip_honeypot = $result;
			}
		}

		// Botscout - Check the IP Address
		// Further informations:
		if($params->get('enable_botscout', 0))
		{
			//JApplication::stringURLSafe()
			$url = 'http://botscout.com/test/?ip='.$ipAddr.'&key='.$params->get('botscout_key', '');
			$response = false;
			$is_spam = false;
			$logging->ip_botscout = 1;

			if(function_exists('curl_init')) {
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				curl_close($ch);
			}

			if($response) {
				$is_spam = substr($response, 0, 1);
			} else {
				$handle = @ fopen($url, 'r');
				if($handle) {
					while(!feof($handle)) {
						$line = fgets($handle, 1024);
						$response .= $line;
						$is_spam = substr($line, 0, 1);
					}
					fclose($handle);
				}
			}

			if ($response == true || $handle == true) {
				if($is_spam == 'Y') {
					$return_is_spam = true;
					$logging->ip_botscout = 2;
				} else if($is_spam == 'N') {
					//no spam - do nothing
				} else {
					Factory::getApplication()->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_BOTSCOUT_NOT_CORRECTLY_SET' ) . ':'. $response, 'error');
					$return_is_spam = true;
					$logging->ip_botscout = 3;
				}
			} else {
				//no response - do nothing??
			}

			$logging->ip_botscout_txt = $response;
		}

		return $return_is_spam;
	}


	public static function checkAkismet($api,$blogUrl,$name,$email,$url, $comment, &$msgA){
        require_once( JPATH_COMPONENT.'/assets/akismet/Akismet.class.php' );
        $akismet = new Akismet($blogUrl, $api);
		$akismet->setCommentAuthor($name);
		$akismet->setCommentAuthorEmail($email);
		$akismet->setCommentAuthorURL($url);
		$akismet->setCommentContent($comment);
		//ip and agend set by class

		if($akismet->isKeyValid()) {}
		else {
			$msgA = 'Akismet: Key is invalid';
		}

		//trigger_error("Akismet: ".$akismet->isCommentSpam(),E_USER_WARNING);
		return $akismet->isCommentSpam();
    }


    public static function checkMollom($publickey,$privatekey,$session_id,$subject,$name,$email,$url, $comment, &$feedback){
		require_once JPATH_COMPONENT.'/assets/mollom/mollom.php';

		Mollom::setPublicKey($publickey);
        Mollom::setPrivateKey($privatekey);
        $servers  = Mollom::getServerList();
        //ip set by class

        $feedback = Mollom::checkContent(null, $subject, $comment, $name, $url, $email);
	}


    public static function createMollomCaptcha($publickey,$privatekey,$session_id){
		require_once JPATH_COMPONENT.'/assets/mollom/mollom.php';

		Mollom::setPublicKey($publickey);
        Mollom::setPrivateKey($privatekey);
        $servers  = Mollom::getServerList();

        return Mollom::getImageCaptcha($session_id);
	}


    public static function checkMollomCaptcha($publickey,$privatekey,$session_id,$value){
		require_once JPATH_COMPONENT.'/assets/mollom/mollom.php';

		Mollom::setPublicKey($publickey);
        Mollom::setPrivateKey($privatekey);
        $servers  = Mollom::getServerList();

        return Mollom::checkCaptcha($session_id, $value);
	}


	public static function sanitize($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
	}


}
?>
