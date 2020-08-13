<?
  $events_num = 1;
  $item = $oo->get($uu->id);
  $media = $oo->media($item['id']);
  $media_all = array();
  $this_media_arr = array();
  foreach($media as $m){
    $this_media_arr[] = array(
      'url' => m_url($m),
      'caption' => $m['caption']
    );
  }
  $media_all[] = $this_media_arr;
  // $eventOrder = order of event;
  $eventOrder = $events_ids_to_orders[$item['id']];
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
    <? 
      foreach($media_all as $key => $media){
        ?>
          <div id = '' order = 'single' class = 'event-ctner' >
            <?
              foreach($media as $m){
                ?>
                  <div id = '' class = 'event media'>
                    <img src = '' data-src="<?= $m['url']; ?>" alt = "<?= $m['caption']; ?>" event = "">
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

  var eventOrder = <?= $eventOrder; ?>;
  var isSingleEvent = true;
  var ready_count = 0;
(function() {

  // preload images with progressive loading
  let imagesToLoad = document.querySelectorAll('img[data-src]');
  const loadImages = (image) => {
    image.setAttribute('src', image.getAttribute('data-src'));
    image.onload = () => {
      // init looper if 10 images has been loaded
      console.log(events.length);
      console.log(ready_count);
      if( (ready_count >= 10 || ready_count == events.length - 1) && !looper_hasStarted){
        looper_hasStarted = true;
        setTimeout(function(){
          current_event_order = eventOrder;
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
