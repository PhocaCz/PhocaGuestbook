<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die;

class PhocaGuestbookCpHelper
{
	public static function quickIconButton( $link, $image, $text ) {
		
		$lang	= JFactory::getLanguage();
		$app	= JFactory::getApplication();
		$option = $app->input->get('option');
		
		$button = '';
		if ($lang->isRTL()) {
			$button .= '<div class="thumbnails ph-icon">';
		} else {
			$button .= '<div class="thumbnails ph-icon">';
		}
		$button .=	''
				   .'<a class="thumbnail ph-icon-inside" href="'.$link.'">'
				  .JHTML::_('image', 'media/'.$option.'/images/administrator/'.$image, $text )
				  .'<br />'
				   .'<span>'.$text.'</span></a>'
				   .'';
		$button .= '</div>';

		return $button;
	}
	
	public static function getLinks() {
		$app	= JFactory::getApplication();
		$option = $app->input->get('option');
		$oT		= strtoupper($option);
		
		$links =  array();
		switch ($option) {
			
			case 'com_phocaguestbook':
				$links[]	= array('Phoca Guestbook site', 'http://www.phoca.cz/phocaguestbook');
				$links[]	= array('Phoca Guestbook documentation site', 'http://www.phoca.cz/documentation/category/3-phoca-guestbook-component');
				$links[]	= array('Phoca Guestbook download site', 'http://www.phoca.cz/download/category/69-phoca-guestbook');
			break;
		
		}
		
		$links[]	= array('Phoca News', 'http://www.phoca.cz/news');
		$links[]	= array('Phoca Forum', 'http://www.phoca.cz/forum');
		
		$components 	= array();
		$components[]	= array('Phoca Gallery','phocagallery', 'pg');
		$components[]	= array('Phoca Guestbook','phocaguestbook', 'pgb');
		$components[]	= array('Phoca Download','phocadownload', 'pd');
		$components[]	= array('Phoca Documentation','phocadocumentation', 'pdc');
		$components[]	= array('Phoca Favicon','phocafavicon', 'pfv');
		$components[]	= array('Phoca SEF','phocasef', 'psef');
		$components[]	= array('Phoca PDF','phocapdf', 'ppdf');
		$components[]	= array('Phoca Restaurant Menu','phocamenu', 'prm');
		$components[]	= array('Phoca Maps','phocamaps', 'pm');
		$components[]	= array('Phoca Font','phocafont', 'pf');
		$components[]	= array('Phoca Email','phocaemail', 'pe');
		$components[]	= array('Phoca Install','phocainstall', 'pi');
		$components[]	= array('Phoca Template','phocatemplate', 'pt');
		$components[]	= array('Phoca Panorama','phocapanorama', 'pp');
		$components[]	= array('Phoca Commander','phocacommander', 'pcm');
		$components[]	= array('Phoca Photo','phocaphoto', 'ph');
		$components[]	= array('Phoca Cart','phocacart', 'pc');
		
		$banners	= array();
		$banners[]	= array('Phoca Restaurant Menu','phocamenu', 'prm');
		
		$o = '';
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_USEFUL_LINKS'). '</h4>';
		$o .= '<ul>';
		foreach ($links as $k => $v) {
			$o .= '<li><a style="text-decoration:underline" href="'.$v[1].'" target="_blank">'.$v[0].'</a></li>';
		}
		$o .= '</ul>';
		
		$o .= '<div>';
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_USEFUL_TIPS'). '</h4>';
		
		$m = mt_rand(0, 10);
		if ((int)$m > 0) {
			$o .= '<div>';
			$num = range(0,(count($components) - 1 )); 
			shuffle($num);
			for ($i = 0; $i<3; $i++) {
				$numO = $num[$i];
				$o .= '<div style="float:left;width:33%;margin:0 auto;">';
				$o .= '<div><a style="text-decoration:underline;" href="http://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.JHTML::_('image',  'media/'.$option.'/images/administrator/icon-box-'.$components[$numO][2].'.png', ''). '</a></div>';
				$o .= '<div style="margin-top:-10px;"><small><a style="text-decoration:underline;" href="http://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.$components[$numO][0].'</a></small></div>';
				$o .= '</div>';
			}
			$o .= '<div style="clear:both"></div>';
			$o .= '</div>';
		} else {
			$num = range(0,(count($banners) - 1 )); 
			shuffle($num);
			$numO = $num[0];
			$o .= '<div><a href="http://www.phoca.cz/'.$banners[$numO][1].'" target="_blank">'.JHTML::_('image',  'media/'.$option.'/images/administrator/b-'.$banners[$numO][2].'.png', ''). '</a></div>';

		}
		
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_PLEASE_READ'). '</h4>';
		$o .= '<div><a style="text-decoration:underline" href="http://www.phoca.cz/phoca-needs-your-help/" target="_blank">'.JText::_($oT.'_PHOCA_NEEDS_YOUR_HELP'). '</a></div>';
		
		$o .= '</div>';
		return $o;
	}
}
