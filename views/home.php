<?
  // $events is from menu.pgp
  $events_num = count($events);
  $media_all = array();
  // $eventOrder = order of event - 1;
  $eventOrder = rand(0, $events_num - 1);

  for($i = $eventOrder; $i < $events_num; $i++){
    $this_id = $events[$i]['id'];
    $this_media = $oo->media($this_id);
    $this_media_arr = array();
    if ($this_media) {
      foreach($this_media as $m) {
        $this_media_arr[] = array(
          'url' => m_url($m),
          'caption' => $m['caption']
        );
      }
      $media_all[$i+1] = $this_media_arr;
    }
  }
  if($eventOrder != 0){
    for($i = 0; $i < $eventOrder; $i++){
      $this_id = $events[$i]['id'];
      $this_media = $oo->media($this_id);
      $this_media_arr = array();
      if ($this_media) {
        foreach($this_media as $m) {
          $this_media_arr[] = array(
            'url' => m_url($m),
            'caption' => $m['caption']
          );
        }
        $media_all[$i+1] = $this_media_arr;
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
    <? 
      foreach($media_all as $key => $media){
        ?>
          <div id = 'event-<? echo $key; ?>' order = '<? echo $key; ?>' class = 'event-ctner' >
            <?
              foreach($media as $m){
                ?>
                  <div id = '' class = 'event media'>
                    <img src = '' data-src="<?= $m['url']; ?>" alt = "<?= $m['caption']; ?>" event = "<?= $key; ?>">
                    <div class = 'caption-container'>
                      <div class = 'caption'><span><?= $m['caption']; ?></span></div>
                    </div>
                  </div>
                <?
              }
            ?>
          </div>
        <?
      }
    ?>
  </div>
</div>

<script src="/static/js/global.js"></script>
<script src="/static/js/slide.js"></script>
<script>
  var media_all = <? echo json_encode($media_all); ?>;
  eventLength = media_all.length;

  var ready_count = 0;
  var eventIdx = 0;
  var isSingleEvent = false;
  var eventOrder = <?= $eventOrder; ?> + 1;
  
(function() {

  let imagesToLoad = document.querySelectorAll('img[data-src]');

  const loadImages = (image) => {
    image.setAttribute('src', image.getAttribute('data-src'));
    image.onload = () => {
      // init looper if 10 images or all the images have been loaded
      if( (ready_count >= 10 || ready_count == events.length) && !looper_hasStarted){
        looper_hasStarted = true;
        setTimeout(function(){
          current_event_order = event_ctner[eventIdx].getAttribute('order');
          activeChannel_span.innerText = current_event_order;
          nextSlide();
          looper = setInterval(function() {
            nextSlide();
          }, slideInterval);
        }, beginningDelay);
      }
      else{
        ready_count++;
      }
      image.removeAttribute('data-src');
    };
  };
  imagesToLoad.forEach((img) => {
    loadImages(img);
  });
  showCenterMessage('Channel ' + eventOrder, false);
  setTimeout(hideCenterMessage, 2000 );
})();
</script>
