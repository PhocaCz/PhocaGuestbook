<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();


class PhocaguestbookViewGuestbooki extends JViewLegacy
{
	function display($tpl = null)
	{		
		ob_get_clean();
		PhocaGuestbookHelperFront::checkSpecificId(1);
		$image_data = $this->get('Data');

		$session 	=& JFactory::getSession();
		$app 		= JFactory::getApplication();
		$params		= JComponentHelper::getParams('com_phocaguestbook') ;
		$namespace	= 'pgb'.$params->get('session_suffix');
		$captchaCnt = $session->get('captcha_cnt',  0, $namespace) + 1; 
		
		$session->set('captcha_result', $image_data['captcha']['outcome'], $namespace);//Save image code to session to check with post data
		$session->set('captcha_id',     $image_data['captchaid'],          $namespace);//Save captcha type
		$session->set('captcha_cnt',    $captchaCnt,                       $namespace);//Save retries
				
		$this->assignRef( 'image',	$image_data['captcha']['image'] );
		parent::display($tpl);
	}	
}
?>
