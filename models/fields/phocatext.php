<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('text');

class JFormFieldPhocaText extends JFormFieldText
{
	protected $type 		= 'PhocaText';
	
	

	protected function getInput() {
$version = 1;
		if ($version == 1 && !$this->hidden) {
			// Initialize some field attributes.
			$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
			$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
			$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
			$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
			$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
			$placeholder= htmlspecialchars($this->getTitle() . ($this->required ? ''.JText::_('COM_PHOCAGUESTBOOK_REQUIREDSIGN') : ''), ENT_COMPAT, 'UTF-8');
			// Initialize JavaScript field attributes.
			$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
			$value 		= htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
			$requInput	= $this->required ? ' required="required" aria-required="true"' : '';
			
			//prepend:
			$preIcon	= $this->element['preicon'] ?  '<i class="' . $this->element['preicon'] . ' tip" title="' . $placeholder . '"></i>' : '';

			// Get the label text from the XML element, defaulting to the element name.
			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			$text = $this->translateLabel ? JText::_($text) : $text;

			// Add the opening label tag and main attributes attributes.
			$label = '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="element-invisible" title="' . $placeholder  . '">' . $text . '</label>';

			return '<span class="add-on">' . $preIcon . $label . '</span>'
		    . '<input'. $requInput. ' type="text" name="'.$this->name.'" id="'.$this->id.'" placeholder="'.$placeholder.'" value="'.$value.'"'
				   .$class.$size.$disabled.$readonly.$onchange.$maxLength.'/>';
		} else {
			return parent::getInput();
		}
		
	}
	
	protected function getLabel($version = 0) {
		
		if ($version == 1 && !$this->hidden) {
			return '';
		} else {
			return parent::getLabel();
		}
	}
	
}
?>
