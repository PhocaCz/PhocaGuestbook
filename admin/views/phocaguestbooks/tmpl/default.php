<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\Helpers\StringHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filter\OutputFilter;


$r 			= $this->r;
$user		= Factory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering 	= ($listOrder == 'ordering');
//$saveOrder 	= ($listOrder == 'ordering' && $listDirn == 'asc');


$saveOrder	= $listOrder == 'ordering';
/*if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_phocaguestbook&task=phocaguestbooks.saveOrderAjax&tmpl=component';
	HTMLHelper::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}*/

$saveOrderingUrl = '';
if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = $r->saveOrder($this->t, $listDirn);
}
$sortFields = $this->getSortFields();

/*
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
*/



echo $r->jsJorderTable($listOrder);

echo $r->startForm($this->t['o'], $this->t['tasks'], 'adminForm');
echo $r->startMainContainer();
echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));


//echo $r->startTable('categoryList');
echo '<table class="table table-striped pgb-tableinfo" id="categoryList">' . "\n";

echo $r->startTblHeader();

echo $r->firstColumnHeader($listDirn, $listOrder);
echo $r->secondColumnHeader($listDirn, $listOrder);
echo '<th class="ph-title-short">'.HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_SUBJECT', 'a.title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-published">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_PUBLISHED', 'a.published', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-code">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_CONTENT', 'a.content', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-discount">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_NAME', 'a.username', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-date">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_IP', 'a.ip', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-date">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_DATE', 'a.date', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-date">'.HTMLHelper::_('searchtools.sort',  'JGRID_HEADING_LANGUAGE', 'a.lanugage', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";
echo $r->endTblHeader();

echo $r->startTblBody($saveOrder, $saveOrderingUrl, $listDirn);

/*


<form action="<?php echo Route::_('index.php?option=com_phocaguestbook&view=phocaguestbooks');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo Text::_('COM_PHOCAGUESTBOOK_SEARCH_IN_TITLE');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo Text::_('COM_PHOCAGUESTBOOK_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo Text::_('COM_PHOCAGUESTBOOK_SEARCH_IN_TITLE'); ?>" />
			</div>
			<div class="btn-group hidden-phone">
				<button class="btn tip" type="submit" title="<?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>


			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo Text::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo Text::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo Text::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo Text::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo Text::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo Text::_('JGLOBAL_SORT_BY');?></option>
					<?php echo HTMLHelper::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>


		<div class="clearfix"></div>
		<table class="table table-striped table-hover table-condensed" id="itemList">
			<thead><?php /*======== HEADER =========================================================
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo HTMLHelper::_('searchtools.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%" style="min-width:55px" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th >
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_SUBJECT', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="25%" class="nowrap hidden-phone">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_CONTENT', 'a.content', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_NAME', 'a.username', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" class="nowrap hidden-phone">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_IP', 'a.ip', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_DATE', 'a.date', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap hidden-phone">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tbody>
*/



            $originalOrders = array();
            $parentsStr 	= "";
            $j 				= 0;

if (is_array($this->items)) {
    foreach($this->items as $i => $item) {


			$linkEdit = Route::_('index.php?option=com_phocaguestbook&task=phocaguestbook.edit&id='.$item->id);
			$linkComment = Route::_('index.php?option=com_phocaguestbook&task=phocaguestbook.edit&id='.$item->id);
			$orderkey   = array_search($item->id, $this->ordering[$item->parent_id]);

            $urlEdit		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.edit&id=';
            $urlTask		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'];

            $ordering		= ($listOrder == 'a.ordering');
            $canCreate		= $user->authorise('core.create', $this->t['o']);
            $canEdit		= $user->authorise('core.edit', $this->t['o']);
            $canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
            $canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;


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


            $ordering = $orderkey+1;

            echo $r->startTr($i, isset($item->catid) ? (int)$item->catid : 0, $item->id, $item->level, $parentsStr);

            echo $r->firstColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $ordering);
            echo $r->secondColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $ordering);

            $checkO = '';
            if ($item->checked_out) {
                $checkO .= HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $this->t['tasks'].'.', $canCheckin);
            }
            if ($canCreate || $canEdit) {
                $checkO .= '<a href="'. Route::_($linkEdit).'">'. $this->escape($item->title).'</a>';
            } else {
                $checkO .= $this->escape($item->title);
            }

            if ($item->parent_id > 1) {
                $checkO .= '<br><span class="badge bg-primary">'. Text::_('COM_PHOCAGUESTBOOK_REPLY_TO_POST'). ': ' . $item->parent_id.'</span>';
            }
            echo $r->td($checkO, "small");

           /*
            HTMLHelper::_('bootstrap.framework');
            HTMLHelper::_('dropdown.init');
            HTMLHelper::_('dropdown.edit', $item->id, 'phocaguestbook.');
			JHtmlDropdown::addCustomItem(Text::_('COM_PHOCAGUESTBOOK_ADD_COMMENT'), 'javascript:void(0)', 'onclick="contextAction(\'' . 'cb' . $i  . '\', \'phocaguestbook.reply\')"');
            echo HTMLHelper::_('dropdown.render');
            */

            $linkComment = '<a href="javascript:void(0)" onclick="contextAction(\'' . 'cb' . $i  . '\', \'phocaguestbook.reply\')" >'.Text::_('COM_PHOCAGUESTBOOK_ADD_COMMENT').'</a>';

            echo $r->td(HTMLHelper::_('jgrid.published', $item->published, $i, $this->t['tasks'].'.', $canChange) .'<br>' . $linkComment, "small");

            $content = strip_tags($item->content);
            $content = OutputFilter::cleanText($content);
            echo $r->td(StringHelper::truncate($content, 40, true, false));

            echo $r->td($this->escape($item->username));
            echo $r->td($item->ip);
            echo $r->td(HTMLHelper::_('date', $item->date, Text::_('DATE_FORMAT_LC4')));
            echo $r->td($item->language_title ? $this->escape($item->language_title) : Text::_('JALL_LANGUAGE'));
            echo $r->td($item->id);


            /*
			?>
			<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->parent_id;?>">

				<td class="order nowrap center hidden-phone">
				<?php
					$disableClassName = '';
					$disabledLabel	  = '';
					if (!$saveOrder) :
						$disabledLabel    = Text::_('JORDERINGDISABLED');
						$disableClassName = 'inactive tip-top';
					endif; ?>
					<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
						<i class="icon-menu"></i>
					</span>
					<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey+1;?>" class="width-20 text-area-order " />
				</td>



				<td class="center hidden-phone">
					<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
				</td>

				<td class="center">
					<div class="btn-group">
						<?php
						 echo HTMLHelper::_('jgrid.published', $item->published, $i, 'phocaguestbooks.', true);
						 //echo JHtml::_('jgrid.delete', $item->published, $i, 'phocaguestbooks.', true);
						?>
					</div>
				</td>

				<td class="nowrap has-context">
					<div class="pull-left">
						<?php echo str_repeat('<span class="gi">&mdash;</span>', $item->level - 1) ?>
						<a href="<?php echo $linkEdit; ?>" title="<?php echo Text::_('JACTION_EDIT');?>">
						<?php echo $this->escape($item->title); ?></a>
						<div class="small">
							<?php echo Text::_('COM_PHOCAGUESTBOOK_FIELD_GUESTBOOK_LABEL') . ": " .
							 $this->escape($item->category_title); ?>
						</div>
					</div>
					<div class="pull-left"><?php
						// Create dropdown items
						HTMLHelper::_('dropdown.edit', $item->id, 'phocaguestbook.');
						JHtmlDropdown::addCustomItem(Text::_('COM_PHOCAGUESTBOOK_ADD_COMMENT'), 'javascript:void(0)', 'onclick="contextAction(\'' . 'cb' . $i  . '\', \'phocaguestbook.reply\')"');
						HTMLHelper::_('dropdown.divider');
						if ($item->published) :
							HTMLHelper::_('dropdown.unpublish', 'cb' . $i, 'phocaguestbooks.');
						else :
							HTMLHelper::_('dropdown.publish', 'cb' . $i, 'phocaguestbooks.');
						endif;
						// Render dropdown list
						echo HTMLHelper::_('dropdown.render');
					?></div>
				</td>
				<td class="hidden-phone"><?php
					$content = strip_tags($item->content);
					$content = OutputFilter::cleanText($content);
					echo StringHelper::truncate($content, 40, true, false);
				?></td>
				<td>
					<?php echo $this->escape($item->username) ?>
				</td>
				<td class="hidden-phone">
					<?php echo $item->ip ?>
				</td>
				<td>
					<?php echo HTMLHelper::_('date', $item->date, Text::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td class="center hidden-phone">
				<?php
				if ($item->language=='*') {
					echo Text::_('JALL');
				} else {
					echo $item->language_title ? $this->escape($item->language_title) : Text::_('JUNDEFINED');
				}
				?>
				<td class="hidden-phone">
					<?php echo $item->id; ?>
				</td>

			</tr>
			<?php endforeach;?>
			</tbody>
			<tfoot><?php /*======== TFOOT =========================================================
			<tr>
			  <td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			  </td>
			</tr>
			</tfoot>
		</table> */

        echo $r->endTr();

		//}
	}
}
echo $r->endTblBody();

echo $r->tblFoot($this->pagination->getListFooter(), 11);
echo $r->endTable();
echo $r->formInputsXML($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
/*
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
*/
?>
