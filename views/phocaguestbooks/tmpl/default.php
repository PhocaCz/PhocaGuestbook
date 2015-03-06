<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');

// Include the component HTML helpers.
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering 	= ($listOrder == 'ordering');
//$saveOrder 	= ($listOrder == 'ordering' && $listDirn == 'asc');
$saveOrder 	= ($listOrder == 'ordering' && ($listDirn == 'asc' || $listDirn == 'desc') );
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_phocaguestbook&task=phocaguestbooks.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
	
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbooks');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_PHOCAGUESTBOOK_SEARCH_IN_TITLE');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_PHOCAGUESTBOOK_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_PHOCAGUESTBOOK_SEARCH_IN_TITLE'); ?>" />
			</div>
			<div class="btn-group hidden-phone">
				<button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			
			
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>			
		</div>
		<div class="clearfix"></div>

		<table class="table table-striped table-hover table-condensed" id="itemList">
			<thead><?php /*======== HEADER =========================================================*/ ?>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%" style="min-width:55px" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'COM_PHOCAGUESTBOOK_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th >
					<?php echo JHtml::_('grid.sort', 'COM_PHOCAGUESTBOOK_SUBJECT', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="25%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_PHOCAGUESTBOOK_CONTENT', 'a.content', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_PHOCAGUESTBOOK_NAME', 'a.username', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_PHOCAGUESTBOOK_IP', 'a.ip', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_PHOCAGUESTBOOK_DATE', 'a.date', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_PHOCAGUESTBOOK_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tbody><?php /*======== TBODY =========================================================*/ ?>
			<?php foreach($this->items as $i => $item): ?>
			<?php 
			$linkEdit = JRoute::_('index.php?option=com_phocaguestbook&task=phocaguestbook.edit&id='.$item->id); 
			$linkComment = JRoute::_('index.php?option=com_phocaguestbook&task=phocaguestbook.edit&id='.$item->id); 
			$orderkey   = array_search($item->id, $this->ordering[$item->parent_id]);
			
			// Get the parents of item for sorting
			if ($item->level > 1) {
				$parentsStr = "";
				$_currentParentId = $item->parent_id;
				$parentsStr = " ".$_currentParentId;
				for ($i = 0; $i < $item->level; $i++) {
					foreach ($this->ordering as $k => $v) {
						$v = implode("-", $v);
						$v = "-".$v."-";
						if (strpos($v, "-" . $_currentParentId . "-") !== false) {
							$parentsStr .= " ".$k;
							$_currentParentId = $k;
							break;
						}
					}
				}
			}
			else
			{
				$parentsStr = "";
			}
			?>
			<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->parent_id;?>" item-id="<?php echo $item->id?>" parents="<?php echo $parentsStr?>" level="<?php echo $item->level?>">
				<td class="order nowrap center hidden-phone">
				<?php
					$disableClassName = '';
					$disabledLabel	  = '';
					if (!$saveOrder) :
						$disabledLabel    = JText::_('JORDERINGDISABLED');
						$disableClassName = 'inactive tip-top';
					endif; ?>
					<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
						<i class="icon-menu"></i>
					</span>
					<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey+1;?>" class="width-20 text-area-order " />
				</td>



				<td class="center hidden-phone">	
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>

				<td class="center">
					<div class="btn-group">
						<?php
						 echo JHtml::_('jgrid.published', $item->published, $i, 'phocaguestbooks.', true);
						 //echo JHtml::_('jgrid.delete', $item->published, $i, 'phocaguestbooks.', true);
						?>
					</div>
				</td>
				
				<td class="nowrap has-context">
					<div class="pull-left">
						<?php echo str_repeat('<span class="gi">&mdash;</span>', $item->level - 1) ?>
						<a href="<?php echo $linkEdit; ?>" title="<?php echo JText::_('JACTION_EDIT');?>">
						<?php echo $this->escape($item->title); ?></a>
						<div class="small">
							<?php echo JText::_('COM_PHOCAGUESTBOOK_FIELD_GUESTBOOK_LABEL') . ": " .
							 $this->escape($item->category_title); ?>
						</div>
					</div>
					<div class="pull-left"><?php
						// Create dropdown items	
						JHtml::_('dropdown.edit', $item->id, 'phocaguestbook.');
						JHtmlDropdown::addCustomItem(JText::_('COM_PHOCAGUESTBOOK_ADD_COMMENT'), 'javascript:void(0)', 'onclick="contextAction(\'' . 'cb' . $i  . '\', \'phocaguestbook.reply\')"');
						JHtml::_('dropdown.divider');
						if ($item->published) :
							JHtml::_('dropdown.unpublish', 'cb' . $i, 'phocaguestbooks.');
						else :
							JHtml::_('dropdown.publish', 'cb' . $i, 'phocaguestbooks.');
						endif;
						// Render dropdown list
						echo JHtml::_('dropdown.render');
					?></div>
				</td>
				<td class="hidden-phone"><?php
					$content = strip_tags($item->content);
					$content = JFilterOutput::cleanText($content);
					echo JHtmlString::truncate($content, 40, true, false);
				?></td>
				<td>
					<?php echo $this->escape($item->username) ?>
				</td>
				<td class="hidden-phone">
					<?php echo $item->ip ?>
				</td>
				<td>
					<?php echo JHtml::_('date', $item->date, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td class="center hidden-phone">
				<?php
				if ($item->language=='*') {
					echo JText::_('JALL');
				} else {
					echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED');
				}
				?>
				<td class="hidden-phone">
					<?php echo $item->id; ?>
				</td>

			</tr>
			<?php endforeach;?>
			</tbody>
			<tfoot><?php /*======== TFOOT =========================================================*/ ?>
			<tr>
			  <td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			  </td>
			</tr>
			</tfoot>
		</table>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>


