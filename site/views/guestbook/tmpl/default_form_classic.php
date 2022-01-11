<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

//jimport('joomla.html.html.bootstrap');
//JHtml::_('behavior.keepalive');
///JHtml::_('behavior.formvalidation');
///JHtml::_('behavior.tooltip');
///JHtml::_('behavior.modal');

// - - - - - - - - - - -
// Form
// - - - - - - - - - - -
if ($this->params->get('show_form') == 1) : ?>
<div class="well pgwell pgb_background pgb_sec_font">
	<h4 class="pgb_font"><?php echo Text::_('COM_phocaguestbook_POST_MESSAGE');?></h4>
	<form action="<?php echo $this->t['actionurl']; ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

	<?php if ($this->params->get('display_title_form', 2)) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
			<div class="controls"><?php

				$app				= Factory::getApplication();
				$reportTitle 		= $app->input->get('reporttitle', '', 'string');
				$formTitle			= $this->form->getInput('title');
				if ($reportTitle != '') {
					$formTitle = str_replace('value=""', 'value="'.urldecode(strip_tags($reportTitle)).'"', $this->form->getInput('title'));
				}
				echo $formTitle;

				if($this->params->get('hidden_field_position')==1){
					echo $this->form->getInput($this->params->get('hidden_field_name'));
				}  ?></div>
		</div>
	<?php	endif;
	if ($this->params->get('display_name_form', 2)) :?>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('username'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('username');
				if($this->params->get('hidden_field_position')==2){echo $this->form->getInput($this->params->get('hidden_field_name'));}  ?></div>
		</div>
	<?php	endif;
	if ($this->params->get('display_email_form', 1)) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('email');
				if($this->params->get('hidden_field_position')==3){echo $this->form->getInput($this->params->get('hidden_field_name'));}  ?></div>
		</div>
	<?php	endif;
	if ($this->params->get('display_website_form', 0)) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('homesite'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('homesite');
				if($this->params->get('hidden_field_position')==4){echo $this->form->getInput($this->params->get('hidden_field_name'));}  ?></div>
		</div>
	<?php	endif;
	if ($this->params->get('display_content_form', 2)) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('content'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('content');
				if($this->params->get('hidden_field_position')==5){echo $this->form->getInput($this->params->get('hidden_field_name'));}  ?></div>
		</div>
	<?php	endif;
	if ($this->params->get('enable_captcha', '') && $this->params->get('captcha_id', 0) > 0) :
		// Set fix height because of pane slider
		$maxImageHeight = '';/*'style="height:100px"';*/?>
		<div class="control-group" <?php echo $maxImageHeight;?>>
			<div class="control-label"><?php echo $this->form->getLabel('captcha'); ?></div>
			<div class="controls">
				<?php /* OWN CAPTCHA
				if ($this->params->get(captcha_id) == 4) {
					echo phocaguestbookHelperReCaptcha::recaptcha_get_html($this->params->get('recaptcha_publickey'));
				} else {
					echo phocaguestbookHelperFront::getCaptchaUrl($this->id);
				}
				//Remove because of IE6 - href="javascript:void(0)" onclick="javascript:reloadCaptcha();"
				?><br />
			<div class="input-append">
				<?php echo $this->form->getInput('guestbook_captcha'); ?>
				<a href="javascript:reloadCaptcha();" title="<?php echo Text::_('COM_PHOCAGUESTBOOK_RELOAD_IMAGE');?>" class="btn hasTooltip" ><i class="icon-loop"></i></a>
			</div> JOOMLA CAPTCHA:*/?>
			<?php echo $this->form->getInput('captcha'); ?>
			</div>
		</div>
	<?php	endif; ?>


	<?php if ($this->params->get('display_privacy_checkbox_form', 0)):	?>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('privacy_checkbox'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('privacy_checkbox');  ?>
				<div class="ph-privacy-row"><?php echo $this->params->get('privacy_checkbox_text', ''); ?></div></div>
		</div>
	<?php endif;?>

	<hr class="hr-condensed" />
	<div class="btn-toolbar">
		<div class="btn-group">
			<button type="submit" class="btn btn-primary pgb-btn-primary">
				<?php /*<i class="glyphicon glyphicon-ok icon-ok"></i> <?php*/ echo JText::_('COM_PHOCAGUESTBOOK_SUBMIT');?></button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-danger pgb-btn-danger" onclick="Joomla.submitbutton('phocaguestbook.cancel')">
				<?php /*<i class="glyphicon glyphicon-remove icon-remove"></i><?php*/ echo JText::_('COM_PHOCAGUESTBOOK_RESET');?>	</button>
		</div>
	</div>

	<?php echo $this->form->getInput('language'); ?>
	<input type="hidden" name="view" value="guestbook" />
	<input type="hidden" name="controller" value="phocaguestbook" />
	<input type="hidden" name="cid" value="<?php echo $this->guestbooks->id;?>" />
	<input type="hidden" name="option" value="com_phocaguestbook" />
	<input type="hidden" name="task" value="phocaguestbook.submit" />
	<?php echo HTMLHelper::_('form.token');?>

	</form>
</div>
<?php
endif; //showForm
?>
