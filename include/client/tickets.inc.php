<?php
if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !$thisclient->isValid()) die('Access Denied');

$settings = &$_SESSION['client:Q'];

// Unpack search, filter, and sort requests
if (isset($_REQUEST['clear']))
    $settings = array();
if (isset($_REQUEST['keywords'])) {
    $settings['keywords'] = $_REQUEST['keywords'];
}
if (isset($_REQUEST['topic_id'])) {
    $settings['topic_id'] = $_REQUEST['topic_id'];
}
if (isset($_REQUEST['status'])) {
    $settings['status'] = $_REQUEST['status'];
}

$org_tickets = $thisclient->canSeeOrgTickets();
if ($settings['keywords']) {
    // Don't show stat counts for searches
    $openTickets = $closedTickets = -1;
}
elseif ($settings['topic_id']) {
    $openTickets = $thisclient->getNumTopicTicketsInState($settings['topic_id'],
        'open', $org_tickets);
    $closedTickets = $thisclient->getNumTopicTicketsInState($settings['topic_id'],
        'closed', $org_tickets);
}
else {
    $openTickets = $thisclient->getNumOpenTickets($org_tickets);
    $closedTickets = $thisclient->getNumClosedTickets($org_tickets);
}

$tickets = Ticket::objects();

$qs = array();
$status=null;

$sortOptions=array('id'=>'number', 'subject'=>'cdata__subject',
                    'status'=>'status__name', 'dept'=>'dept__name','date'=>'created');
$orderWays=array('DESC'=>'-','ASC'=>'');
//Sorting options...
$order_by=$order=null;
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'date';
if($sort && $sortOptions[$sort])
    $order_by =$sortOptions[$sort];

$order_by=$order_by ?: $sortOptions['date'];
if ($_REQUEST['order'] && !is_null($orderWays[strtoupper($_REQUEST['order'])]))
    $order = $orderWays[strtoupper($_REQUEST['order'])];
else
    $order = $orderWays['DESC'];

$x=$sort.'_sort';
$$x=' class="'.strtolower($_REQUEST['order'] ?: 'desc').'" ';

$basic_filter = Ticket::objects();
if ($settings['topic_id']) {
    $basic_filter = $basic_filter->filter(array('topic_id' => $settings['topic_id']));
}

if ($settings['status'])
    $status = strtolower($settings['status']);
    switch ($status) {
    default:
        $status = 'open';
    case 'open':
    case 'closed':
		$results_type = ($status == 'closed') ? __('Closed Tickets') : __('Open Tickets');
        $basic_filter->filter(array('status__state' => $status));
        break;
}

// Add visibility constraints — use a union query to use multiple indexes,
// use UNION without "ALL" (false as second parameter to union()) to imply
// unique values
$visibility = $basic_filter->copy()
    ->values_flat('ticket_id')
    ->filter(array('user_id' => $thisclient->getId()))
    ->union($basic_filter->copy()
        ->values_flat('ticket_id')
        ->filter(array('thread__collaborators__user_id' => $thisclient->getId()))
    , false);

if ($thisclient->canSeeOrgTickets()) {
    $visibility = $visibility->union(
        $basic_filter->copy()->values_flat('ticket_id')
            ->filter(array('user__org_id' => $thisclient->getOrgId()))
    , false);
}

// Perform basic search
if ($settings['keywords']) {
    $q = trim($settings['keywords']);
    if (is_numeric($q)) {
        $tickets->filter(array('number__startswith'=>$q));
    } elseif (strlen($q) > 2) { //Deep search!
        // Use the search engine to perform the search
        $tickets = $ost->searcher->find($q, $tickets);
    }
}

$tickets->distinct('ticket_id');

TicketForm::ensureDynamicDataView();

$total=$visibility->count();
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$qstr = '&amp;'. Http::build_query($qs);
$qs += array('sort' => $_REQUEST['sort'], 'order' => $_REQUEST['order']);
$pageNav->setURL('tickets.php', $qs);
$tickets->filter(array('ticket_id__in' => $visibility));
$pageNav->paginate($tickets);

$showing =$total ? $pageNav->showing() : "";
if(!$results_type)
{
	$results_type=ucfirst($status).' '.__('Tickets');
}
$showing.=($status)?(' '.$results_type):' '.__('All Tickets');
if($search)
    $showing=__('Search Results').": $showing";

$negorder=$order=='-'?'ASC':'DESC'; //Negate the sorting

$tickets->order_by($order.$order_by);
$tickets->values(
    'ticket_id', 'number', 'created', 'isanswered', 'source', 'status_id',
    'status__state', 'status__name', 'cdata__subject', 'dept_id',
    'dept__name', 'dept__ispublic', 'user__default_email__address'
);

?>
<form action="tickets.php" method="get" id="ticketSearchForm">
    <div class="search flex">
        <div class="half-lg">
            <input type="hidden" name="a"  value="search">
            <input type="text" name="keywords" size="30" value="<?php echo Format::htmlchars($settings['keywords']); ?>">
            <input type="submit" class="button-sm" value="<?php echo __('Search');?>">
        </div>
        <div class="half-lg right-lg">
            <div class="head-room">
                <?php echo __('Help Topic'); ?>:
                <select name="topic_id" class="nowarn" onchange="javascript: this.form.submit(); ">
                    <option value="">&mdash; <?php echo __('All Help Topics');?> &mdash;</option>
                    <?php foreach (Topic::getHelpTopics(true) as $id=>$name) {
                            $count = $thisclient->getNumTopicTickets($id, $org_tickets);
                            if ($count == 0)
                                continue;
                    ?>
                            <option value="<?php echo $id; ?>"i
                                <?php if ($settings['topic_id'] == $id) echo 'selected="selected"'; ?>
                                ><?php echo sprintf('%s (%d)', Format::htmlchars($name),
                                    $thisclient->getNumTopicTickets($id)); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <?php if ($settings['keywords'] || $settings['topic_id'] || $_REQUEST['sort']) { ?>
        <div style="margin-top:10px"><strong><a href="?clear" style="color:#777"><i class="icon-remove-circle"></i> <?php echo __('Clear all filters and sort'); ?></a></strong></div>
        <?php } ?>
    </div>
</form>


<div class="flex nowrap head-room">
    <div class="half">    
        <h2 style="margin:10px 0">
            <a href="<?php echo Format::htmlchars($_SERVER['REQUEST_URI']); ?>"
                ><i class="refresh icon-refresh"></i>
                <?php echo __('Tickets'); ?>
            </a>
        </h2>
    </div>
    <div class="half right">
        <div class="button">
            <a href="/open.php">
                <i class="fas fa-plus"></i> Open Ticket
            </a>
        </div>
    </div>
</div>

<div class="timestamp">
    <?php if ($openTickets) { ?>
        <i class="icon-file-alt"></i>
        <a class="state <?php if ($status == 'open') echo 'active'; ?>"
            href="?<?php echo Http::build_query(array('a' => 'search', 'status' => 'open')); ?>">
        <?php echo _P('ticket-status', 'Open'); if ($openTickets > 0) echo sprintf(' (%d)', $openTickets); ?>
        </a>
        <?php if ($closedTickets) { ?>
        &nbsp;
        <span style="color:lightgray">|</span>
        <?php }
    }
    if ($closedTickets) {?>
        &nbsp;
        <i class="icon-file-text"></i>
        <a class="state <?php if ($status == 'closed') echo 'active'; ?>"
            href="?<?php echo Http::build_query(array('a' => 'search', 'status' => 'closed')); ?>">
        <?php echo __('Closed'); if ($closedTickets > 0) echo sprintf(' (%d)', $closedTickets); ?>
        </a>
    <?php } ?>
</div>
<div class="ticket-list">
<?php
     $subject_field = TicketForm::objects()->one()->getField('subject');
     $defaultDept=Dept::getDefaultDeptName(); //Default public dept.
     if ($tickets->exists(true)) {
         foreach ($tickets as $T) {
            $dept = $T['dept__ispublic']
                ? Dept::getLocalById($T['dept_id'], 'name', $T['dept__name'])
                : $defaultDept;
            $subject = $subject_field->display(
                $subject_field->to_php($T['cdata__subject']) ?: $T['cdata__subject']
            );
            $status = TicketStatus::getLocalById($T['status_id'], 'value', $T['status__name']);
            if (false) // XXX: Reimplement attachment count support
                $subject.='  &nbsp;&nbsp;<span class="Icon file"></span>';

            $ticketNumber=$T['number'];
            if($T['isanswered'] && !strcasecmp($T['status__state'], 'open')) {
                $subject="<b>$subject</b>";
                $ticketNumber="<b>$ticketNumber</b>";
            }
            $thisclient->getId() != $T['user_id'] ? $isCollab = true : $isCollab = false;
            ?>
            <div class="foot-room link <?php if ($status !== 'Open') { echo 'timestamp'; } ?>" id="<?php echo $T['ticket_id']; ?>" href="tickets.php?id=<?php echo $T['ticket_id']; ?>">
                <div class="ticket flex">
                    <div class="ticket-type head-room"><h6>(<?php echo $status; ?>) &nbsp; <?php echo $dept; ?> Ticket #<span><?php echo $ticketNumber; ?></span></h6></div>
                    <div class="ticket-description head-room"><div class="truncate"><?php echo $subject; ?></div></div>
                    <div class="timestamp head-room">Created <?php echo Format::date($T['created']); ?><br /></div>
                  </div>
            </div>
        <?php
        }

     } else {
         echo '<tr><td colspan="5">'.__('Your query did not match any records').'</td></tr>';
     }
    ?>
    
</div>
<?php
if ($total) {
    echo '<div class="center">&nbsp;'.__('Page').':'.$pageNav->getPageLinks().'&nbsp;</div>';
}
?>

