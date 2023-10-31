<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

$r = $this->r;
echo $r->startCp();

echo '<div class="ph-box-cp">';
echo '<div class="ph-left-cp">';

echo '<div class="ph-cp-item-box">';
$link	= 'index.php?option='.$this->t['o'].'&view=';
foreach ($this->views as $k => $v) {

	if ($k == 'categories') {
		$linkV	= 'index.php?option=com_categories&extension=com_phocaguestbook';
	} else {
		$linkV	= $link . $this->t['c'] . $k;
	}

	echo $r->quickIconButton( $linkV, Text::_($v[0]), $v[1], $v[2]);
}
echo '</div>';
echo '</div>';

echo '<civ class="ph-right-cp">';

echo '<div class="ph-extension-info-box">';
echo '<div class="ph-cpanel-logo">'.HTMLHelper::_('image', $this->t['i'] . 'logo-'.str_replace('phoca', 'phoca-', $this->t['c']).'.png', 'Phoca.cz') . '</div>';
echo '<div style="float:right;margin:10px;">'. HTMLHelper::_('image', $this->t['i'] . 'logo-phoca.png', 'Phoca.cz' ).'</div>';

echo '<h3>'.  Text::_($this->t['l'] . '_VERSION').'</h3>'
.'<p>'.  $this->t['version'] .'</p>';

echo '<h3>'.  Text::_($this->t['l'] . '_COPYRIGHT').'</h3>'
.'<p>© 2007 - '.  date("Y"). ' Jan Pavelka</p>'
.'<p><a href="https://www.phoca.cz/" target="_blank">www.phoca.cz</a></p>';

echo '<h3>'.  Text::_($this->t['l'] . '_LICENSE').'</h3>'
.'<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>';

echo '<h3>'.  Text::_($this->t['l'] . '_TRANSLATION').': '. Text::_($this->t['l'] . '_TRANSLATION_LANGUAGE_TAG').'</h3>'
.'<p>© 2007 - '.  date("Y"). ' '. Text::_($this->t['l'] . '_TRANSLATER'). '</p>'
.'<p>'.Text::_($this->t['l'] . '_TRANSLATION_SUPPORT_URL').'</p>';

echo '<div class="ph-cp-hr"></div>'
.'<div class="btn-group ph-cp-btn-update"><a class="btn btn-large btn-primary" href="https://www.phoca.cz/version/index.php?'.$this->t['c'].'='.  $this->t['version'] .'" target="_blank"><i class="icon-loop icon-white"></i>&nbsp;&nbsp;'.  Text::_($this->t['l'] . '_CHECK_FOR_UPDATE') .'</a></div>';



echo '<div class="ph-cp-logo-footer"><a href="https://www.phoca.cz/" target="_blank">'.HtmlHelper::_('image', $this->t['i'] . 'logo.png', 'Phoca.cz' ).'</a></div>';
echo '<div class="ph-cb"></div>';


echo '</div>';


echo '<div class="ph-extension-links-box">';
echo Text::_('COM_PHOCAGUESTBOOK_SECURITY_PARAMETERS_SETTINGS') . '<br>';
echo '<a href="https://www.phoca.cz/documents/3-phoca-guestbook-component/436-trying-to-prevent-from-spam" style="text-decoration:underline;" target="_blank">Trying to prevent from spam</a>';
echo '</div>';

echo '<div class="ph-extension-links-box">';
echo $r->getLinks();
echo '</div>';

echo '</div>';

echo '</div>';
echo $r->endCp();

?>
