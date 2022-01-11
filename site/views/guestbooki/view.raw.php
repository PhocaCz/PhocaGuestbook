<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;


class PhocaguestbookViewGuestbooki extends HtmlView
{
	protected $image;

	function display($tpl = null)
	{
		ob_get_clean();
		PhocaGuestbookHelperFront::checkSpecificId(1);
		$image_data = $this->get('Data');

		$app		= Factory::getApplication();
		$session 	= $app->getSession();
		$params 	= $app->getParams();
		$namespace	= 'pgb'.$params->get('session_suffix', '');
		$captchaCnt = $session->get('captcha_cnt',  0, $namespace) + 1;


		$session->set('captcha_result', $image_data['captcha']['outcome'], $namespace);//Save image code to session to check with post data
		$session->set('captcha_id',     $image_data['captchaid'],          $namespace);//Save captcha type
		$session->set('captcha_cnt',    $captchaCnt,                       $namespace);//Save retries

		//$this->assignRef( 'image',	$image_data['captcha']['image'] );
		$this->image = $image_data['captcha']['image'];
		parent::display($tpl);


	}
}
?>
