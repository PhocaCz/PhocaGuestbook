<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span9">
	<?php echo JHTML::_('image', 'media/com_phocaguestbook/images/administrator/logo-phoca.png', 'Phoca.cz',  array('align' => 'right') );
		  echo JHTML::_('image', 'media/com_phocaguestbook/images/administrator/logo.png', 'Phoca.cz');?>
<h3><?php echo JText::_('COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK').' - '. JText::_('COM_PHOCAGUESTBOOK_INFORMATION');?></h3>
<h3><?php echo JText::_('COM_PHOCAGUESTBOOK_HELP');?></h3>
<p><a href="http://www.phoca.cz/phocaguestbook/" target="_blank">Phoca Guestbook Main Site</a><br />
   <a href="http://www.phoca.cz/documentation/" target="_blank">Phoca Guestbook User Manual</a><br />
   <a href="http://www.phoca.cz/forum/" target="_blank">Phoca Guestbook Forum</a><br />
</p>
<h3><?php echo JText::_('COM_PHOCAGUESTBOOK_VERSION');?></h3>
<p> <?php echo $this->tmpl['version'];?></p>
<h3><?php echo JText::_('COM_PHOCAGUESTBOOK_COPYRIGHT');?></h3>
<p>© 2007 - <?php echo date("Y");?> Jan Pavelka, Daniel Huber</p>
<p><a href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></p>
<h3><?php echo JText::_('COM_PHOCAGUESTBOOK_LICENCE')?></h3>
<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>
<h3><?php echo JText::_('COM_PHOCAGUESTBOOK_TRANSLATION').': '. JText::_('COM_PHOCAGUESTBOOK_TRANSLATION_LANGUAGE_TAG');?></h3>
<p>© 2007 - <?php echo date("Y"). ' '. JText::_('COM_PHOCAGUESTBOOK_TRANSLATER');?></p>
<p><?php echo JText::_('COM_PHOCAGUESTBOOK_TRANSLATION_SUPPORT_URL');?></p>

<div style="border-top:1px solid #eee"></div>
<p>&nbsp;</p>

<div class="btn-group">
<a class="btn btn-large btn-primary" href="http://www.phoca.cz/version/index.php?phocaguestbook=<?php echo $this->tmpl['version'];?>" target="_blank"><i class="icon-loop icon-white"></i> <?php echo JText::_('COM_PHOCAGUESTBOOK_CHECK_FOR_UPDATE');?></a>
</div>
<?php echo '<div style="margin-top:30px;height:39px;background: url(\''.JURI::root(true).'/media/com_phocaguestbook/images/administrator/line.png\') 100% 0 no-repeat;">&nbsp;</div>'; ?>

</div>
<div class="span1">
</div>
</form>
