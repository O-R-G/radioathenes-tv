<?
  $events_num = 1;
  $item = $oo->get($uu->id);
  $media = $oo->media($item['id']);
  $eventOrder = $events_ids_to_orders[$item['id']];

  $event_all = array();
  $image_all = array();
  $this_event = array('order'=>$eventOrder);
  $this_media_arr = array();
  foreach($media as $m){
    $this_media_arr[] = array(
      'url' => m_url($m),
      'caption' => $m['caption']
    );
    $image_all[] = m_url($m);
  }
  $this_event['media'] = $this_media_arr;
  $event_all[] = $this_event;
  // $eventOrder = order of event;
  
?>
<div id="rotate-notice" class="message-full">
  <div>
    Please Rotate Your Device.
  </div>
</div>
<div id="center-notice" class="message-full">
  <div>
    <span id="center-message" class="system-message">
    </span>
  </div>
</div>
<div id="cc" class="transparent hideable">
  CC
</div>

<div class="full" id="fullscreen">
  <div class="container" id="container">
    <div class="media show-media" id="noise" >
      <?
      $noiseGifs = getNoiseGifs($oo, $root);
      foreach($noiseGifs as $idx=>$gif) {
        ?><img src="<?= m_url($gif); ?>" class="<? if ($idx != 0) { echo 'hidden'; } ?>"><?
      }
      ?>
    </div>
    <div id = '' class = 'event media'>
      <img src = "<?= $event_all[0]['media'][0]['url']; ?>">
      <div class = 'caption-container'>
        <div class = 'caption'><span><?= $event_all[0]['media'][0]['caption']; ?></span></div>
      </div>
    </div>
    <div id = '' class = 'event media'>
      <img src = "<?= $event_all[0]['media'][1]['url']; ?>">
      <div class = 'caption-container'>
        <div class = 'caption'><span><?= $event_all[0]['media'][1]['caption']; ?></span></div>
      </div>
    </div>
  </div>
</div>

<script src="/static/js/global.js"></script>
<script src="/static/js/slide.js"></script>
<script>
  var event_all = <? echo json_encode($event_all); ?>;
  eventLength = event_all.length;
  current_media = event_all[0]['media'];
  var image_all = <? echo json_encode($image_all); ?>;
  var isSingleEvent = true;
  var eventOrder = <?= $eventOrder; ?>;
  var preloadIdx = 0;

(function() {

  var img_preload = new Image();

  function preloadImages(){
    img_preload.onload = function(){
      preloadIdx ++; 
      if(preloadIdx < image_all.length)
        preloadImages();
      if((preloadIdx >= 10 || preloadIdx == image_all.length) && !looper_hasStarted){
        looper_hasStarted = true;
        setTimeout(function(){
          activeChannel_span.innerText = eventOrder;
          nextSlide();
          looper = setInterval(function() {
            nextSlide();
          }, slideInterval);
        }, beginningDelay);
        setTimeout(hideCenterMessage, beginningDelay - 250 );
      }
    }
    img_preload.src = image_all[preloadIdx];
  }

  preloadImages();

  showCenterMessage('Channel ' + eventOrder, false);
  // setTimeout(hideCenterMessage, 2000 );
})();
</script>
