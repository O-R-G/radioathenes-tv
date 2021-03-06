<? 
	$events = $oo->children(getEventsID($oo, $root));
	usort($events, "date_sort");
	$item = $oo->get($uu->id);
	$media = $oo->media($item['id']);
	$events_ids_to_orders = array();

	$credit_id = end($oo->urls_to_ids(array('system', 'credits')));
	$credit = $oo->get($credit_id);
?>

<div class="left-container">
  
  <div id="blue" class="">
    <div id="active-channel" class="click">
      <span class="system-message"></span>
    </div>
  </div>
  <ul id="picker">
  <?foreach($events as $key => $event) {
    // $event_date = date('y-m-d', strtotime($event['begin']));
    $event['order'] = $key + 1;
    $events_ids_to_orders[$event['id']] = $event['order'];
    ?>
    <li><div id = 'event-button-<?= $event['order']; ?>' class="<?= $event['order']; ?> event-button click">
      <a href="/events/<?= $event['url'] ?>" class="system-message"><?= $event['order']; ?> <?= $event['name1']; ?></a>
    </div></li>
      <?
  } ?>
  <br><li><div id = 'event-button-0' class="0 event-button click">
      <a href="/<?= $credit['url'] ?>" class="system-message"><?= $credit['name1']; ?></a>
  </div></li>
  <? if($uri[1]){ ?>
	<li class="event-button click"><a href="/" class="system-message">*</a></li>
  <? } ?>
  </ul>
</div>
<script>
	var events_ids_to_orders = <? echo json_encode($events_ids_to_orders); ?>;
</script>