<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');
require_once JPATH_COMPONENT.'/helpers/phocaguestbookcp.php';

?>
<form action="index.php" method="post" name="adminForm">
<div id="j-sidebar-container" class="span2"><?php echo $this->sidebar; ?></div>

<div id="j-main-container" class="span10">
	<div class="adminform">
		<div class="ph-cpanel-left">
			<div id="cpanel">
				<?php
				$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
				echo PhocaGuestbookCpHelper::quickIconButton( $link, 'icon-48-pgu-item.png', JText::_( 'COM_PHOCAGUESTBOOK_ITEMS' ) );
				$link = 'index.php?option=com_categories&extension=com_phocaguestbook';
				echo PhocaGuestbookCpHelper::quickIconButton( $link, 'icon-48-pgu-guestbook.png', JText::_( 'COM_PHOCAGUESTBOOK_GUESTBOOKS' ) );
				$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooklogs';
				echo PhocaGuestbookCpHelper::quickIconButton( $link, 'icon-48-pgu-log.png', JText::_( 'COM_PHOCAGUESTBOOK_LOGGING' ) );
				$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookin';
				echo PhocaGuestbookCpHelper::quickIconButton( $link, 'icon-48-pgu-info.png', JText::_( 'COM_PHOCAGUESTBOOK_INFO' ) );
				?>
				<div style="clear:both">&nbsp;</div>
				<p>&nbsp;</p>
				
				<div class="alert alert-block alert-info ph-w80">
				<button type="button" class="close" data-dismiss="alert">×</button>
					<?php echo PhocaGuestbookCpHelper::getLinks(); ?>
				</div>
		
				<div class="alert alert-block alert-error ph-w80">
				<button type="button" class="close" data-dismiss="alert">×</button>
		<?php echo JText::_('COM_PHOCAGUESTBOOK_SECURITY_PARAMETERS_SETTINGS');?><br />
		<a href="http://www.phoca.cz/documents/3-phoca-guestbook-component/436-trying-to-prevent-from-spam" style="text-decoration:underline;" target="_blank">Trying to prevent from spam</a>
				</div>
			</div>
		</div>

		<div class="ph-cpanel-right">
			<div class="well">
				<div style="float:right;margin:10px;">
					<?php echo JHTML::_('image', 'media/com_phocaguestbook/images/administrator/logo-phoca.png', 'Phoca.cz' );?>
				</div>
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

				<div style="border-top:1px solid #c2c2c2"></div>
				<p>&nbsp;</p>

				<div class="btn-group">
				<a class="btn btn-large btn-primary" href="http://www.phoca.cz/version/index.php?phocaguestbook=<?php echo $this->tmpl['version'];?>" target="_blank"><i class="icon-loop icon-white"></i> <?php echo JText::_('COM_PHOCAGUESTBOOK_CHECK_FOR_UPDATE');?></a>
				</div>
			</div>
		</div>

				
<input type="hidden" name="option" value="com_phocaguestbook" />
<input type="hidden" name="view" value="phocaguestbookcp" />
<?php echo JHtml::_('form.token'); ?>
	</div>
</div>
</form>
