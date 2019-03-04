<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );


class PhocaguestbookEmailHelper
{
	public static function sendPhocaGuestbookMail ($id, $post2, $url, $tmpl) {
		
		$app			= JFactory::getApplication();
		$db 			= JFactory::getDBO();
		$sitename 		= $app->get( 'sitename' );
		$title 			= $post2['title'];
		
		$paramsC 	= $app->getParams();
		$numCharEmail	= $paramsC->get( 'num_char_email', 400 );
		
		//get all selected users
		$query = 'SELECT name, email, sendEmail' .
		' FROM #__users' .
		' WHERE id = '.(int)$id;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if ($post2['published']) {
			$subject = $sitename .' ('.JText::_( 'COM_PHOCAGUESTBOOK_PG_NEW_POST' ). ')';
		} else {
			$subject = $sitename .' ('.JText::_( 'COM_PHOCAGUESTBOOK_PG_NEW_POST_WAITING' ). ')';
		}
		
		
		if (isset($post2['title']) && $post2['title'] != '') {
			$subject = $subject . ': '.PhocaguestbookHelperFront::wordDelete($post2['title'], 25,'...');
		}
		
		if (isset($post2['username']) && $post2['username'] != '') {
			$fromname = $post2['username'];
		} else {
			$fromname = 'Unknown';
		}
		
		if (isset($post2['email']) && $post2['email'] != '') {
			$mailfrom = $post2['email'];
		} else {
			$mailfrom = $rows[0]->email;
		}
		
		if (isset($post2['content']) && $post2['content'] != '') {
			$content = $post2['content'];
		} else {
			$content = "...";
		}
		
		$email = $rows[0]->email;
		
		$post2['content'] = str_replace("</p>", "\n", $post2['content'] );
		$post2['content'] = strip_tags($post2['content']);
		
		$message = JText::_( 'COM_PHOCAGUESTBOOK_PG_NEW_POST_ADDED' ) . "\n\n"
							. JText::_( 'COM_PHOCAGUESTBOOK_WEBSITE' ) . ': '. $sitename . "\n"
							. JText::_( 'COM_PHOCAGUESTBOOK_FROM' ) . ': '. $fromname . "\n"
							. JText::_( 'COM_PHOCAGUESTBOOK_DATE' ) . ': '. JHTML::_('date',  gmdate('Y-m-d H:i:s'), JText::_( 'DATE_FORMAT_LC2' )) . "\n\n"
							. JText::_( 'COM_PHOCAGUESTBOOK_SUBJECT' ) . ': '.$title."\n"
							. JText::_( 'COM_PHOCAGUESTBOOK_CONTENT' ) . ': '."\n"
							. "\n\n"
							.PhocaguestbookHelperFront::wordDelete($post2['content'], $numCharEmail, '...')."\n\n"
							. "\n\n"
							. JText::_( 'COM_PHOCAGUESTBOOK_CLICK_LINK' ) ."\n"
							. $url."\n\n"
							. JText::_( 'COM_PHOCAGUESTBOOK_REGARDS' ) .", \n"
							. $sitename ."\n";
					
		$subject = html_entity_decode($subject, ENT_QUOTES);
		$message = html_entity_decode($message, ENT_QUOTES);
				
		//return JFactory::getMailer()->sendMail($mailfrom, $fromname, $email, $subject, $message);
		
		//return JFactory::getMailer()->sendMail($mailfrom, $fromname, $email, $subject, $message, false, null, null, null, $mailfrom, $fromname);
		$mailFromAdmin = $rows[0]->email;
		$recipient = $rows[0]->email;
		return JFactory::getMailer()->sendMail($mailFromAdmin, $fromname, $recipient, $subject, $message, false, null, null, null, $mailfrom, $fromname);
	}
    
}
?>
