<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('text');

class JFormFieldPhocaColorText extends JFormField
{
	protected $type 		= 'PhocaColorText';
	protected $phocaParams 	= null;

	protected function getInput() {
	
		$document		= JFactory::getDocument();
		$option 		= JRequest::getCmd('option');
		$globalValue 	= $this->_getPhocaParams( $this->element['name'] );
		
		JHTML::stylesheet( 'administrator/components/com_phocaguestbook/assets/jcp/picker.css' );
		$document->addScript(JURI::base(true).'/components/com_phocaguestbook/assets/jcp/picker.js');
		
		// Initialize some field attributes.
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		
		$value 		= htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		
		// TODO 1.6
		// MENU - Set Default value to "" because of saving "" value into the menu link ( use global = "")
		if ($option == "com_menus") {
			$DefaultValue	= (string)$this->element['default'];
			if ($value == $DefaultValue) {
				$value = '';
			}
		}

		$html ='<input type="text" name="'.$this->name.'" id="'.$this->id.'" value="'.$value.'"'
			   .$class.$size.$disabled.$readonly.$onchange.$maxLength.'/>';		
		
		// Color Picker
		$nameCP = str_replace('[', '_', $this->name);
		$nameCP = str_replace(']', '', $nameCP);
		$html .= '<span style="margin-left:10px" onclick="openPicker(\''.$nameCP.'\')"  class="picker_buttons">' . JText::_('COM_PHOCAGUESTBOOK_PICK_COLOR') . '</span>';
		
		// MENU - Display the global value
		if ($option == "com_menus") {
			$html .= '<span style="margin-left:10px;">[</span><span style="background:#fff"> ' . $globalValue . ' </span><span>]</span>';
		}
		return $html;

	}
	
	protected function getLabel() {
		echo '<div class="clr"></div>';
		return parent::getLabel();
		echo '<div class="clr"></div>';
	}
	
	protected function _setPhocaParams(){
	
		$component 			= 'com_phocaguestbook';
		$table 				= JTable::getInstance('extension');
		$idCom				= $table->find( array('element' => $component ));
		$table->load($idCom);
		$phocaParams 		= new JParameter( $table->params );
		$this->phocaParams	= $phocaParams;
	}

	protected function _getPhocaParams( $name ){
	
		// Don't call sql query by every param item (it will be loaded only one time)
		if (!$this->phocaParams) {
			$params = $this->_setPhocaParams();
		}
		$globalValue 	= $this->phocaParams->get( $name, '' );	
		return $globalValue;
	}
}
?>
