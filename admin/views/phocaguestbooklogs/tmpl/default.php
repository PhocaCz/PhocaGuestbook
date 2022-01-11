<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

$r 			= $this->r;
$user		= Factory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$sortFields = $this->getSortFields();

$saveOrder	= $listOrder == 'ordering';

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
echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));

//echo $r->startTable('categoryList');
echo '<table class="table table-striped pgb-tableinfo" id="categoryList">' . "\n";

echo $r->startTblHeader();

echo $r->firstColumnHeader($listDirn, $listOrder);
echo $r->secondColumnHeader($listDirn, $listOrder);
echo '<th class="ph-state">'.HTMLHelper::_('searchtools.sort',  'S', 'a.state', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-postid">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_ENTRY', 'a.postid', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-catid">'.HTMLHelper::_('searchtools.sort', 'GB', 'a.catid', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-captcha">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_CAPTCHA', 'a.captchaid', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-used-time">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_TIME', 'a.used_time', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-incoming-page">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_INCOMING_PAGE', 'a.incoming_page', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-fields">'.HTMLHelper::_('searchtools.sort',  $this->t['l'].'_FIELD_CHECK', 'a.fields', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-session">'.HTMLHelper::_('searchtools.sort',  		'S', 'a.session', $listDirn, $listOrder ).'</th>'."\n";

echo '<th class="ph-session">'.HTMLHelper::_('searchtools.sort',  		'HF', 'a.hidden_field', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-session">'.HTMLHelper::_('searchtools.sort',  		'FW', 'a.forbidden_word', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-session">'.HTMLHelper::_('searchtools.sort',  		'CC', 'a.content_akismet', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-session">'.HTMLHelper::_('searchtools.sort',  		'CC', 'a.content_mollom', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-ip">'.HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_IP', 'a.fields', 'a.ip_list', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-ip">'.HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_IP', 'a.ip_stopforum', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-ip">'.HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_IP', 'a.ip_honeypot', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-ip">'.HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_IP', 'a.ip_botscout', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-ip">'.HTMLHelper::_('searchtools.sort',  		$this->t['l'].'_IP', 'a.ip', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-date">'.HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_DATE', 'a.date', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.HTMLHelper::_('searchtools.sort',  	$this->t['l'].'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();

echo $r->startTblBody($saveOrder, $saveOrderingUrl, $listDirn);

/*
<form action="<?php echo Route::_('index.php?option=com_phocaguestbook&view=phocaguestbooklogs');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">


	*/

if (!$this->params->get('enable_logging', 0))  {

    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'. Text::_('COM_PHOCAGUESTBOOK_LOGGING_NOT_ENABLED').'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="'.Text::_('COM_PHOCAGUESTBOOK_CLOSE').'"></button></div>';
}

/*
		<div id="filter-bar" class="btn-toolbar">
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

		<table class="table  pgb-tableinfo" id="itemList">
			<thead>
			<tr>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'S', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<th width="2%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_ENTRY', 'a.postid', $listDirn, $listOrder); ?>
				</th>
				<th width="2%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'GB', 'a.catid', $listDirn, $listOrder); ?>
				</th>
				<th width="2%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_CAPTCHA', 'a.captchaid', $listDirn, $listOrder); ?>
				</th>
				<th width="2%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'Time', 'a.used_time', $listDirn, $listOrder); ?>
				</th>
				<th width="2%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'Come in', 'a.incoming_page', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_FIELD_CHECK', 'a.fields', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'S', 'a.session', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'HF', 'a.hidden_field', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'FW', 'a.forbidden_word', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'CC', 'a.content_akismet', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'CC', 'a.content_mollom', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_IP', 'a.ip_list', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_IP', 'a.ip_stopforum', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_IP', 'a.ip_honeypot', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_IP', 'a.ip_botscout', $listDirn, $listOrder); ?>
				</th>

				<th width="5%" class="nowrap hidden-phone">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_IP', 'a.ip', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo HTMLHelper::_('searchtools.sort', 'COM_PHOCAGUESTBOOK_DATE', 'a.date', $listDirn, $listOrder); ?>
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

			//table-striped table-bordered table-hover table-condensed
            $orderkey   = array_search($item->id, $this->ordering[0]);
            $ordering		= ($listOrder == 'a.ordering');
            $canCreate		= $user->authorise('core.create', $this->t['o']);
            $canEdit		= $user->authorise('core.edit', $this->t['o']);
            $canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
            $canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;

            echo $r->startTr($i, isset($item->catid) ? (int)$item->catid : 0);
            echo $r->firstColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $ordering);
            echo $r->secondColumn($i, $item->id, $canChange, $saveOrder, $orderkey, $ordering);



			// ++++++++++++++++++++++++++++++++ STATE +++++++++++++++++++++++++++++
			switch ($item->state) {
				case 1:
					$class  = 'success';
					$image  = 'icon-mail';
					$title  = Text::_('COM_PHOCAGUESTBOOK_PUBLISHED');
					break;
				case 2:
					$class  = 'info';
					$image  = 'icon-mail2';
					$title  = Text::_('COM_PHOCAGUESTBOOK_REVIEW');
					break;
				default:
				case 0:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title  = Text::_('COM_PHOCAGUESTBOOK_REJECT');
					break;
			}
			echo '<td class="hasTip ' .$class. '" title="' .$title .'"> <i class="' .$image. '"></i> </td>';

			// ++++++++++++++++++++++++++++++++ POST_ID +++++++++++++++++++++++++++++
			if ($item->postid > 0){
				$title = 'Title: ' . $item->title . ' from:' .$item->username;
				echo '<td class="hasTip center" title="' .$title. '">' .$item->postid. '</td>';
			} else {
				echo '<td class="center"> -- </td>';
			}

			// ++++++++++++++++++++++++++++++++ GUESBTOOK +++++++++++++++++++++++++++++
			$title = $item->guestbook_title;
			echo '<td class="hasTip center" title="' .$title. '">' .$item->catid. '</td>';


			// ++++++++++++++++++++++++++++++++ CAPTCHA_ID +++++++++++++++++++++++++++++
			switch ($item->captchaid) {
				case 0:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_NONE_CAPTCHA'); 		break;
				case 1:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_JOOMLA_CAPTCHA');		break;
				case 2:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_STANDARD_CAPTCHA'); 	break;
				case 3:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_MATH_CAPTCHA'); 		break;
				case 4:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_TTF_CAPTCHA'); 		break;
				case 5:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_RECAPTCHA_CAPTCHA'); 	break;
				case 6:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_EASYCALC_CAPTCHA'); 	break;
				case 7:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_MOLLOM_CAPTCHA'); 		break;
				case 8:	 $title  =	Text::_('COM_PHOCAGUESTBOOK_HN_CAPTCHA'); 			break;
				default: $title  = 'UNKOWN'; break;
			}
			echo '<td class="hasTip center" title="' .$title .'">' .$item->captchaid. '</td>';


			// ++++++++++++++++++++++++++++++++ USED TIME +++++++++++++++++++++++++++++
			echo '<td class="center">' .$item->used_time. '</td>';

			// ++++++++++++++++++++++++++++++++ INCOMING PAGE +++++++++++++++++++++++++++++
			$title = $item->incoming_page;
			echo '<td class="hasTip center nowrap" title="' .$title. '">' .substr($item->incoming_page,7,4). '...' .substr($item->incoming_page,-11). '</td>';

			// ++++++++++++++++++++++++++++++++ FIELDS +++++++++++++++++++++++++++++
			switch ($item->fields) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title  = 'Fields: ' . Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-key';
					$title  = Text::_('COM_PHOCAGUESTBOOK_ANY_FIELDS_INVALID');
					break;
			}
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ SESSION +++++++++++++++++++++++++++++
			switch ($item->session) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title  = 'Session: ' . Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title  = Text::_('COM_PHOCAGUESTBOOK_SESSION_INVALID');
					break;
			}
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ HIDDENFIELD +++++++++++++++++++++++++++++
			switch ($item->hidden_field) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title  = 'Session: ' . Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				case 0:
					$class  = 'info';
					$image  = 'icon-cancel-2';
					$title .= Text::_('COM_PHOCAGUESTBOOK_NOT_CHECKED');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-key';
					$title  = Text::_('COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_SPAM_BLOCKED');
					break;
			}
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ FORBIDDEN +++++++++++++++++++++++++++++
			switch ($item->forbidden_word) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title  = 'Session: ' . Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				case 0:
					$class  = 'info';
					$image  = 'icon-cancel-2';
					$title .= Text::_('COM_PHOCAGUESTBOOK_NOT_CHECKED');
					break;
				case 2:
				case 4:
				default:
					$class  = 'error';
					$image  = 'icon-key';
					$title  = Text::_('COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_SPAM_BLOCKED');
					break;
			}
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ Content:Akismet +++++++++++++++++++++++++++++
			$title = 'Content:Akismet: ';
			switch ($item->content_akismet) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title .= Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				case 0:
					$class  = 'info';
					$image  = 'icon-cancel-2';
					$title .= Text::_('COM_PHOCAGUESTBOOK_NOT_CHECKED');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title .= Text::_('COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_SPAM_BLOCKED');
					break;
			}
			$title .= '<br/>' . $item->content_akismet_txt;
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ Content:Mollom +++++++++++++++++++++++++++++
			$title = 'Content:Mollom: ';
			$title .= '<br/>' . $item->content_mollom_txt . '<br/>' ;
			switch ($item->content_mollom) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title .= Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				case 0:
					$class  = 'info';
					$image  = 'icon-cancel-2';
					$title .= Text::_('COM_PHOCAGUESTBOOK_NOT_CHECKED');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title .= Text::_('COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_SPAM_BLOCKED');
					break;
			}
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ IP:List +++++++++++++++++++++++++++++
			switch ($item->ip_list) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title  = 'IP_List: ' . Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title  = Text::_('COM_PHOCAGUESTBOOK_IP_BAN_NO_ACCESS');
					break;
			}
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ IP:Stopforum +++++++++++++++++++++++++++++
			$title = 'IP:Stopforum: ';
			switch ($item->ip_stopforum) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title .= Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				case 0:
					$class  = 'info';
					$image  = 'icon-cancel-2';
					$title .= Text::_('COM_PHOCAGUESTBOOK_NOT_CHECKED');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title .= Text::_('COM_PHOCAGUESTBOOK_IP_BAN_NO_ACCESS');
					break;
			}
			$title .= '<br/>' . $item->ip_stopforum_txt;
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ IP:Honepot +++++++++++++++++++++++++++++
			$title = 'IP:Honeypot: ';
			switch ($item->ip_honeypot) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title .=  Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				case 0:
					$class  = 'info';
					$image  = 'icon-cancel-2';
					$title .= Text::_('COM_PHOCAGUESTBOOK_NOT_CHECKED');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title .= Text::_('COM_PHOCAGUESTBOOK_IP_BAN_NO_ACCESS');
					break;
			}
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';

			// ++++++++++++++++++++++++++++++++ IP:Botscout +++++++++++++++++++++++++++++
			$title  = 'IP:Botscout: ';
			switch ($item->ip_botscout) {
				case 1:
					$class  = 'success';
					$image  = 'icon-health';
					$title .=  Text::_('COM_PHOCAGUESTBOOK_OK');
					break;
				case 0:
					$class  = 'info';
					$image  = 'icon-cancel-2';
					$title .= Text::_('COM_PHOCAGUESTBOOK_NOT_CHECKED');
					break;
				default:
					$class  = 'error';
					$image  = 'icon-lightning';
					$title .= Text::_('COM_PHOCAGUESTBOOK_IP_BAN_NO_ACCESS');
					break;
			}
			$title .= '<br/>' . $item->ip_botscout_txt;
			echo '<td class="hasTip ' .$class. ' center" title="' .$title .'"> <i class="' .$image. '"></i> </td>';


			// ++++++++++++++++++++++++++++++++ IP +++++++++++++++++++++++++++++
			echo '<td>' .$item->ip. '</td>';


			// ++++++++++++++++++++++++++++++++ DATE +++++++++++++++++++++++++++++
			echo '<td class="nowrap">' .HTMLHelper::_('date', $item->date, Text::_('DATE_FORMAT_LC4') . ' H:i:s'). '</td>';


			// ++++++++++++++++++++++++++++++++ ID +++++++++++++++++++++++++++++
			echo '<td>' .$item->id. '</td>';


			echo $r->endTr();
    }
}
echo $r->endTblBody();

echo $r->tblFoot($this->pagination->getListFooter(), 21);
echo $r->endTable();
echo $r->formInputsXML($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();

/*
?>
			</tbody>
			<tfoot>
			<tr>
			  <td colspan="20">
				<?php echo $this->pagination->getListFooter(); ?>
			  </td>
			</tr>
			</tfoot>
		</table>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
*/ ?>

