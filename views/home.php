<?
  // $events is from menu.pgp
  $events_num = count($events);
  $event_all = array();
  $image_all = array();
  // $eventOrder is order of event - 1;
  $eventOrder = rand(0, $events_num - 1);
  // $eventOrder = 51;

  for($i = $eventOrder; $i < $events_num; $i++){
    $this_id = $events[$i]['id'];
    $this_media = $oo->media($this_id);
    $this_media_arr = array();
    $this_event_arr = array();
    $this_event_arr['order'] = $i + 1;
    if ($this_media) {
      foreach($this_media as $m) {
        $this_media_arr[] = array(
          'url' => m_url($m),
          'caption' => $m['caption']
        );
        $image_all[] = m_url($m);
      }
      $this_event_arr['media'] = $this_media_arr;
      $event_all[] = $this_event_arr;
    }
  }
  if($eventOrder != 0){
    for($i = 0; $i < $eventOrder; $i++){
      $this_id = $events[$i]['id'];
      $this_media = $oo->media($this_id);
      $this_media_arr = array();
      $this_event_arr = array();
      $this_event_arr['order'] = $i + 1;
      if ($this_media) {
        foreach($this_media as $m) {
          $this_media_arr[] = array(
            'url' => m_url($m),
            'caption' => $m['caption']
          );
          $image_all[] = m_url($m);
        }
        $this_event_arr['media'] = $this_media_arr;
        $event_all[] = $this_event_arr;
      }
    }
  }
  // media_all = array of arrays (url, caption) in chronological order, starting with a randomly picked event;  

?>
<div id="rotate-notice" class="message-full">
  <div class="">
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
      <img src = "<?= $event_all[0]['media'][0]['url']; ?>" data-src="" alt = "" event = "">
      <div class = 'caption-container'>
        <div class = 'caption'><span><?= $event_all[0]['media'][0]['caption']; ?></span></div>
      </div>
    </div>
    <div id = '' class = 'event media'>
      <img src = "<?= $event_all[0]['media'][1]['url']; ?>" data-src="" alt = "" event = "">
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
  var isSingleEvent = false;
  var eventOrder = <?= $eventOrder; ?> + 1;
  var preloadIdx = 0;

(function() {

  // let imagesToLoad = document.querySelectorAll('img[data-src]');

  // const loadImages = (image) => {
  //   image.setAttribute('src', image.getAttribute('data-src'));
  //   image.onload = () => {
  //     // init looper if 10 images or all the images have been loaded
  //     if( (ready_count >= 10 || ready_count == events.length) && !looper_hasStarted){
  //       looper_hasStarted = true;
  //       setTimeout(function(){
  //         current_event_order = event_ctner[eventIdx].getAttribute('order');
  //         activeChannel_span.innerText = current_event_order;
  //         nextSlide();
  //         looper = setInterval(function() {
  //           nextSlide();
  //         }, slideInterval);
  //       }, beginningDelay);
  //     }
  //     else{
  //       ready_count++;
  //     }
  //     image.removeAttribute('data-src');
  //   };
  // };
  // imagesToLoad.forEach((img) => {
  //   loadImages(img);
  // });

  var img_preload = new Image();

  function preloadImages(){
    img_preload.onload = function(){
      preloadIdx ++; 
      if(preloadIdx < image_all.length)
        preloadImages();
      if((preloadIdx >= 10 || preloadIdx == image_all.length -1 ) && !looper_hasStarted){
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
  
})();
</script>
