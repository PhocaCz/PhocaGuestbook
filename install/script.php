<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') || die('=;)');

class com_phocaguestbookInstallerScript
{
    public function preflight($type, $parent){}

    public function install($parent) {

		$parent->getParent()->setRedirectURL('index.php?option=com_phocaguestbook');
    }
	
    public function update($parent){
		$msg 	=  JText::_('COM_PHOCAGUESTBOOK_UPDATE_TEXT');
		$app	= JFactory::getApplication();
		$app->enqueueMessage($msg, 'message');
		$app->redirect(JRoute::_('index.php?option=com_phocaguestbook'));
    }

    public function postflight($type, $parent){
		$this->setDefaultParams(  );
    }

    public function uninstall($parent) { }
    

	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_phocaguestbook"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
    
    function setDefaultParams() {
		$db = JFactory::getDbo();
		$paramsString ='{"display_title_form":"2","display_name_form":"2","display_email_form":"1","display_website_form":"0","display_content_form":"2","enable_editor":"1","display_form":"1","form_position":"2","form_style":"1","predefined_name":"","username_or_name":"0","disable_user_check":"0","review_item":"1","send_super_email":"0","date_format":"DATE_FORMAT_LC","display_posts":"1","display_name":"1","display_email":"1","display_website":"1","display_comments":"10","display_comment_name":"1","display_comment_subject":"1","display_comment_email":"1","display_comment_website":"1","display_comment_date":"1","show_pagination":"1","default_pagination":"5","show_pagination_limit":"0","enable_cache":"0","forbidden_word_filter":"","forbidden_whole_word_filter":"","form_action_hidden_word":"2","display_hidden_word":"0","max_char":"2000","max_url":"5","deny_url_words":"","form_action_denied_url":"0","enable_html_purifier":"1","session_suffix":"","contentcheck_block_spam":"0","enable_akismet":"0","akismet_api_key":"","akismet_url":"","enable_mollom":"0","mollom_publickey":"","mollom_privatekey":"","form_action_banned_ip":"0","ip_ban":"","enable_stopforumspam":"0","enable_projecthoneypot":"0","projecthoneypot_key":"","enable_botscout":"0","botscout_key":"","enable_captcha_users":"0","captcha_url":"1","standard_captcha_chars":"1,2,3,4,5,6,7,8,9, a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z, A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z","math_captcha_chars":"1,2,3,4,5,6,7,8,9, a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z, A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z","ttf_captcha_chars":"1,2,3,4,5,6,7,8,9, a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z, A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z","hn_captcha_chars":"1,2,3,4,5,6,7,8,9, A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z","hn_noise":"1","recaptcha_publickey":"","recaptcha_privatekey":"","recaptcha_theme":"red","calc_opertor":"0","calc_operand":"2","calc_max_value":20,"calc_negativ":"0","calc_string":"0","enable_hidden_field":"0","enable_time_check":"0","time_check_s":"10","enable_logging":"0","logging_failed":"0"}';
		
		$db->setQuery('UPDATE #__extensions SET params = ' .
			$db->quote( $paramsString ) .
			' WHERE element = '.$db->quote('com_phocaguestbook'));
			$db->query();
	}     
}
