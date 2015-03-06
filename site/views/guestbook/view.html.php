<?php
/**
 * @package    phocaguestbook
 * @subpackage views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
require_once JPATH_COMPONENT.'/helpers/phocaguestbookonlinecheck.php';

class PhocaguestbookViewGuestbook extends JViewLegacy
{
    /**
     * @var
     */    
    protected $params;
    protected $guestbooks;
    protected $items;
	protected $pagination;
	protected $form;
	protected $additional;
	protected $user;
        
	function display($tpl = null) {
		

		$app		= JFactory::getApplication();
		$user  		= JFactory::getUser();
		$document	= JFactory::getDocument();
		$session 	= JFactory::getSession();

		// - - - - - - - - - - -
		// Get constant data from model
		$state		= $this->get('State');
		$guestbooks	= $this->get('Guestbook'); // = getCategory

		// Check for errors.
		if ($guestbooks == false) {
			return JError::raiseError(404, JText::_('COM_PHOCAGUESTBOOK_GUESTBOOK_NOT_FOUND'));
		}

		// Load the parameters. 
		// Merge Global => GUESTBOOK => Menu Item params into new object in view
		$applparams = $app->getParams();		
		$bookparams = new JRegistry;
		$menuParams = new JRegistry;
		$bookparams->loadString($guestbooks->get('params'));
		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->params);
		} 
		
		$params = clone $applparams;
		$params->merge($bookparams);
		$params->merge($menuParams);
		
		if ($params->get('load_bootstrap', 0) == 1) {
			JHtml::_('bootstrap.loadCss');
		}
		JHTML::stylesheet( 'media/com_phocaguestbook/css/phocaguestbook.css' );

		// Check whether category access level allows access.
		if (!$params->get('access-view')) {
			//return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			$uri = JFactory::getURI();
			$app->redirect('index.php?option=com_users&view=login&return=' . base64_encode($uri), JText::_('COM_PHOCAGUESTBOOK_NOT_AUTHORIZED_DO_ACTION'));
			return;
		}

		// Setup custom parameters
		$params->set('date_format',	PhocaguestbookHelperFront::getDateFormat($params->get('date_format')));
		$params->set('captcha_id', 	PhocaguestbookHelperFront::getCaptchaId($params->get('enable_captcha')));
		$params->set('pgbinfo',		PhocaguestbookHelperFront::getInfo());
		$params->set('show_form', $params->get('display_form',1));
		$params->set('show_posts', $params->get('display_posts',1));
		// Captcha not for registered
		if ($params->get('enable_captcha_users') == 1 && $user->id > 0) {
			$params->set('enable_captcha', 0);
		}
		
		$namespace  = 'pgb' . $params->get('session_suffix');
		// Hidden Field
		if ($params->get('enable_hidden_field') 	== 1) {
			$params->set('hidden_field_position', PhocaguestbookHelperFront::setHiddenFieldPos($params->get('display_title_form'), $params->get('display_name_form'), $params->get('display_email_form'), $params->get('display_website_form'), $params->get('display_content_form')));
			
			$session->set('hidden_field_id', 'hf'.PhocaguestbookHelperFront::getRandomString(mt_rand(6,10)), $namespace);
			$session->set('hidden_field_name', 'hf'.PhocaguestbookHelperFront::getRandomString(mt_rand(6,10)), $namespace);
			$session->set('hidden_field_class', 'pgb'.PhocaguestbookHelperFront::getRandomString(mt_rand(6,10)), $namespace);
				
			$params->set('hidden_field_id', $session->get('hidden_field_id', '', $namespace));
			$params->set('hidden_field_name', $session->get('hidden_field_name', '', $namespace));
			$params->set('hidden_field_class', $session->get('hidden_field_class', '', $namespace));

			$document->addCustomTag('<style type="text/css"> .'.$params->get('hidden_field_class').' { '."\n\t".'display: none !important;'."\n".'}</style>');
		} else {
			$params->set('hidden_field_position', -1);
		}
		$state->set('params', $params);
		
		// - - - - - - - - - - -
		// Get data from model, depending on parmas
		$items		= $this->get('Data');
		$pagination = $this->get('Pagination');
		$form		= $this->get('Form');
		$form->setValue('language',  null, $guestbooks->language);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		// Form processing
		// - - - - - - - - - - -
		// Fill the form in case, you get no data from post
		if (!$app->getUserState('com_phocaguestbook.guestbook.data')){

			// - - - - - - - - - -
			// Add username and user e-mail if user is login
			if ($params->get('username_or_name') == 1) {
				if ($user->name && trim($user->name !='')) {
					$form_username = $user->name;
				} else {
					$form_username = $params->get('predefined_name');
				}
			} else {
				if ($user->username && trim($user->username !='')) {
					$form_username = $user->username;
				} else {
					$form_username = $params->get('predefined_name');
				}
			}
			
			if ($user->email && trim($user->email !='')) {
				$form_email = $user->email;
			} else {
				$form_email = '';
			}
			$form->setValue('username', null, $form_username);
			$form->setValue('email', null, $form_email);
		}
			
		
		//-----------------------------------------------------------------------------------------------
		// !!!! 2. Before Server Side Checking controll, don't show form (but there is a server side
		//         checking, it means, if the user hack the form which is not displayed to him
		//         there is a server checking controll too.
		//-----------------------------------------------------------------------------------------------
		//Don't show form, if IP Ban is wrong
		if ($params->get('form_action_banned_ip') == 2) {
			$ipAddr = $_SERVER["REMOTE_ADDR"];
			//$ipAddr = "87.103.128.199";
			
			$isSpam = PhocaguestbookOnlineCheckHelper::checkIpAddress($ipAddr, $params);
			if ($isSpam) {
				// Banned Client
				$params->set('show_form', 0);
				$app->enqueueMessage(JText::_('COM_PHOCAGUESTBOOK_IP_BAN_NO_ACCESS'), 'error');
			}
		} //end of ip check
		
		
		// user can create posts
		if (!$params->get('access-post')){
			$params->set('show_form', 0);
			$app->enqueueMessage(JText::_('COM_PHOCAGUESTBOOK_REG_USER_ONLY_NO_ACCESS'), 'warning');
		} 
		
		//check procedure (form was send with error/successful)
		$msgs = ($app->getMessageQueue());
		$foundSuccess = $foundError = false;
		foreach ($msgs  as $qmsg) {
			if ($qmsg['type'] == 'success') {
				$foundSuccess = true;
			} else {
				$foundError = true;
			}
		}
		if ($foundError) {
			if ($params->get('show_form'))
				$params->set('tab_active_form', 1);
		} else if ($foundSuccess) {
			//$params->set('show_form', 0);  show form, even is post was send
		}
		
		// Always set session, even if form is not set (avoid spam from cached form)
        // Time Lock or logging
        if($params->get('enable_logging') || $params->get('enable_time_check')) {
			$sesstime = $session->get('time', time(), $namespace);
			$session->set('time', $sesstime, $namespace);
		}
		if ($params->get('enable_captcha')) {
			$session->set('captcha_id', $params->get('captcha_id'), $namespace);//Save captcha type
		}
		if ($params->get('show_form')){
			//always set form id - if not set at post -> SPAM! (use default namespace)
			$session->set('form_id', PhocaguestbookHelperFront::getRandomString(mt_rand(6,10)), 'phocaguestbook'); 
		}
				
		//IF ITEMS ARE REQUIRED
		if ($params->get('show_posts')) {	
			if ($params->get('display_hidden_word') != 1) {	
				$fwfa	= explode( ',', trim( $params->get( 'forbidden_word_filter', '' ) ) );
				$fwwfa	= explode( ',', trim( $params->get( 'forbidden_whole_word_filter', '' ) ) );

				foreach ($items as $key => &$item) {
					// Forbidden Word Filter
					// Believe or not - it is more faster to replace items than the whole content :-)
					foreach ($fwfa as $values2) {
						if (function_exists('str_ireplace')) {
							$item->username 	= str_ireplace (trim($values2), '***', $item->username);
							$item->title		= str_ireplace (trim($values2), '***', $item->title);
							$item->content		= str_ireplace (trim($values2), '***', $item->content);
							$item->email		= str_ireplace (trim($values2), '***', $item->email);
							$item->homesite		= str_ireplace (trim($values2), '***', $item->homesite);
						} else {		
							$item->username 	= str_replace (trim($values2), '***', $item->username);
							$item->title		= str_replace (trim($values2), '***', $item->title);
							$item->content		= str_replace (trim($values2), '***', $item->content);
							$item->email		= str_replace (trim($values2), '***', $item->email);
							$item->homesite		= str_replace (trim($values2), '***', $item->homesite);
						}
					}
				
					
					//Forbidden Whole Word Filter
					foreach ($fwwfa as $values2) {
						if ($values2 !='') {
							//$values3			= "/([\. ])".$values3."([\. ])/";
							$values2			= "/(^|[^a-zA-Z0-9_]){1}(".preg_quote(($values2),"/").")($|[^a-zA-Z0-9_]){1}/i";
							$item->username 	= preg_replace ($values2, "\\1***\\3", $item->username);// \\2
							$item->title		= preg_replace ($values2, "\\1***\\3", $item->title);
							$item->content		= preg_replace ($values2, "\\1***\\3", $item->content);
							$item->email		= preg_replace ($values2, "\\1***\\3", $item->email);
							$item->homesite		= preg_replace ($values2, "\\1***\\3", $item->homesite);
						}
					}
				}
			}
			
			$pagination	= $this->get('pagination');
			
			$document->addCustomTag('<style type="text/css"> .pgb-comment:after { '."\n\t".'content: "'.JText::_('COM_PHOCAGUESTBOOK_CSS_COMMENT').'";'."\n".'}</style>');  
		}
		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
		
		
		// Create a shortcut	
		$this->items      = &$items;
		$this->guestbooks = &$guestbooks;
		$this->pagination = &$pagination;
		$this->user       = &$user;
		$this->params	  = &$params;
		$this->form       = &$form;
		$this->additional = &$additional;
		
		$this->_prepareDocument();
		parent::display($tpl);
	}
	
	protected function _prepareDocument() {
		$app		= JFactory::getApplication();
		$pathway 	= $app->getPathway();
	
		$title = $this->params->get('page_title'); //$this->guestbooks->title; dont use guestbook title
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);
		
		
		if ($this->guestbooks->metadesc)
		{
			$this->document->setDescription($this->guestbooks->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		if ($this->guestbooks->metakey)
		{
			$this->document->setMetadata('keywords', $this->guestbooks->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		
		$mdata = $this->guestbooks->getMetadata()->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}
		
		// Add feed links
		if ($this->params->get('show_feed_link', 1)) {
			$link = '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
		}
	}
}
?>
