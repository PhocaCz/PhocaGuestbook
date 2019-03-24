<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

$hiddenfield =	' 		<div class="control-group '.$this->params->get('hidden_field_class').'">'.
				'			<div class="controls input-prepend input-group">'.
				'				'. $this->form->getInput($this->params->get('hidden_field_name')) .
				'			</div>'.
				'		</div>';

// - - - - - - - - - - -
// Form
// - - - - - - - - - - -
if ($this->params->get('show_form') == 1) :?>
<div class="well pgwell pgb_background pgb_sec_font">
	<h4 class="pgb_font"><?php echo JText::_('COM_PHOCAGUESTBOOK_POST_MESSAGE');?><br/>&nbsp;</h4>
	<form action="<?php echo $this->t['actionurl']; ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

	<?php if ($this->params->get('display_title_form', 2)) : ?>
		<div class="control-group">
			<div class="controls input-prepend input-group">
				<?php

				$app				= JFactory::getApplication();
				$reportTitle 		= $app->input->get('reporttitle', '', 'string');
				$formTitle			= $this->form->getInput('title');
				if ($reportTitle != '') {
					$formTitle = str_replace('value=""', 'value="'.urldecode(strip_tags($reportTitle)).'"', $this->form->getInput('title'));
				}
				echo $formTitle; ?>
			</div>
		</div>
	<?php
	endif;
	if($this->params->get('hidden_field_position')==5){ echo $hiddenfield; }
	if ($this->params->get('display_name_form', 2)) : ?>
		<div class="control-group">
			<div class="controls input-prepend input-group">
				<?php echo $this->form->getInput('username'); ?>
			</div>
		</div>
	<?php
	endif;
	if($this->params->get('hidden_field_position')==2){ echo $hiddenfield; }
	if ($this->params->get('display_email_form', 1)) : ?>
		<div class="control-group">
			<div class="controls input-prepend input-group">
				<?php echo $this->form->getInput('email'); ?>
			</div>
		</div>
	<?php
	if($this->params->get('hidden_field_position')==3){ echo $hiddenfield; }
	endif;
	if ($this->params->get('display_website_form', 0)) : ?>
		<div class="control-group">
			<div class="controls input-prepend input-group">
				<?php echo $this->form->getInput('homesite'); ?>
			</div>
		</div>
	<?php
	if($this->params->get('hidden_field_position')==4){ echo $hiddenfield; }
	endif;
	if ($this->params->get('display_content_form', 2)) : ?>
		<div class="control-group">
			<div class="controls">
				<?php echo $this->form->getInput('content'); ?>
			</div>
		</div>
	<?php
	if($this->params->get('hidden_field_position')==5){ echo $hiddenfield; }
	endif;
	if ($this->params->get('enable_captcha', '') && $this->params->get('captcha_id', 0) > 0) :	?>
		<div class="control-group">
			<div class="controls input-prepend input-append input-group">
			<?php echo $this->form->getInput('captcha'); ?>
			</div>
		</div>

	<?php endif;?>
	<?php if ($this->params->get('display_privacy_checkbox_form', 0)):	?>
		<div class="control-group">
			<div class="controls input-group">
			<?php echo $this->form->getInput('privacy_checkbox'); ?>

			</div>
			<div class="ph-privacy-row"><?php echo $this->params->get('privacy_checkbox_text', ''); ?></div>
		</div>

	<?php endif;?>


	<hr class="hr-condensed" />
	<div class="btn-toolbar">
		<div class="btn-group">
			<button type="submit" class="btn btn-primary">
				<i class="glyphicon glyphicon-ok icon-ok"></i>  <?php echo JText::_('COM_PHOCAGUESTBOOK_SUBMIT');?></button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn" onclick="Joomla.submitbutton('phocaguestbook.cancel')">
				<i class="glyphicon glyphicon-remove icon-remove"></i>  <?php echo JText::_('COM_PHOCAGUESTBOOK_RESET');?></button>
		</div>
	</div>
	<?php echo $this->form->getInput('language'); ?>
	<input type="hidden" name="view" value="guestbook" />
	<input type="hidden" name="controller" value="phocaguestbook" />
	<input type="hidden" name="cid" value="<?php echo $this->guestbooks->id;?>" />
	<input type="hidden" name="option" value="com_phocaguestbook" />
	<input type="hidden" name="task" value="phocaguestbook.submit" />
	<?php echo JHtml::_('form.token');?>

	</form>
</div>
<?php
endif; //showform
?>
