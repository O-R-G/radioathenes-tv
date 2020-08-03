<?
  $item = $oo->get($uu->id);
  $media = $oo->media($item['id']);
  $media_url_array = array();
  $media_caption_array = array();
  foreach($media as $m){
    $media_url_array[] = m_url($m);
    $media_caption_array[] = $m['caption'];
  }
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
    <div id = 'event-media-1' class = 'event media'>
      <img><div class = 'caption-container'><div class = 'caption'><span></span></div></div>
    </div>
    <div id = 'event-media-2' class = 'event media'>
      <img><div class = 'caption-container'><div class = 'caption'><span></span></div></div>
    </div>
  </div>
</div>

<script src="/static/js/global.js"></script>
<script>
  var media_url_array = <?= json_encode($media_url_array); ?>;
  var media_caption_array = <?= json_encode($media_caption_array); ?>;
(function() {
  var showing = [];
  var loopIdx = 0; // index of the looper
  
  var events = document.getElementsByClassName('event');
  var noise = document.getElementById('noise');
  var event_img = document.querySelectorAll('.event img');
  var event_caption_span = document.querySelectorAll('.caption-container span');
  var activeChannel = document.getElementById('active-channel');
  var activeChannel_span = document.querySelector('#active-channel span');
  var eventIdx = events_ids_to_orders[<?= $item['id']; ?>];
  activeChannel_span.innerText = eventIdx;
  var looper,
      looper_resume, 
      clearCenterMessage;
  var ready_count = 0;
  var isReady = false;
  var slideBegin;
  var slideRemain = 4000;
  var slidePlaying = false;

  function preloadImg_single(imageArray, index){
    var img = new Image ();
    if (imageArray && imageArray.length > index+1) {
      img.addEventListener('load', function(){
        preloadImg_single(imageArray, index + 1);
      });
    }

    load_starting = Date.now();
    img.addEventListener('load', function(){
      ready_count++;
      if((ready_count == imageArray.length || ready_count == 10) && !isReady){
        load_ending = Date.now();
        isReady = true;
        event_img[(loopIdx % 2)].src = media_url_array[loopIdx];
        event_caption_span[(loopIdx % 2)].innerText = media_caption_array[loopIdx];
        if(load_ending - load_starting > 4000){
          nextSlide_single();
        }
        looper = setInterval(function() {
          nextSlide_single();
        }, 4000);

      }
    });
    img.src = media_url_array[index];
  }

  preloadImg_single(media_url_array, 0);

  function nextSlide_single(){
    slidePlaying = true;
    slideBegin = Date.now();
    slideRemain = 4000;
      // activeChannel_span.innerText = eventIdx;
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
      noise.classList.add('show-media');
      pickWeightedRandomNoise();
    setTimeout(function() {
      // show current
      noise.classList.remove('show-media');
      events[(loopIdx % 2)].classList.add('show-media');
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });

      loopIdx++;
      
      events[(loopIdx % 2)].classList.remove('show-media');
      event_img[(loopIdx % 2)].src = media_url_array[loopIdx % media_url_array.length];
      event_caption_span[(loopIdx % 2)].innerText = media_caption_array[loopIdx % media_url_array.length];
    }, Math.random()*500 + 125);
  }
  function playPause() {
    if (slidePlaying) {
      slidePlaying = false;
      slideRemain = slideRemain - (Date.now() - slideBegin);
      clearInterval(looper);
      clearTimeout(looper_resume);
      clearTimeout(clearCenterMessage);
      looper = null;
      looper_resume = null;
      showCenterMessage('PAUSED', true);
    } else {
      slidePlaying = true;
      slideBegin = Date.now();
      showCenterMessage('PLAY', false);
      clearCenterMessage = setTimeout(hideCenterMessage, 2000);
      looper_resume = setTimeout(function(){
        nextSlide_single();
        looper = setInterval(function() {
          nextSlide_single();
        }, 5000);
      }, slideRemain);
      
      
    }
  }

  document.getElementById('container').onclick = playPause;

  // goes to an index with noise transition
  function gotoIndex(idx) {
    if (loopIdx != -1) {
      showing.forEach(function(e) {
        e.classList.remove('show-media');
      });
      showing = [];
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
      noise.classList.add('show-media');
      pickWeightedRandomNoise();
    }
    setTimeout(function() {
      noise.classList.remove('show-media');
      loopIdx = idx;
      events[loopIdx%events.length].classList.add('show-media');
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
      showing.push(events[(loopIdx)%events.length]);

    }, Math.random()*500 + 125);
  }

  // runs the loop
  // var looper = setInterval(function() {
  //   gotoIndex(loopIdx+1);
  // }, 5000);

  showCenterMessage('Channel ' + activeChannel.getElementsByTagName('span')[0].innerHTML, false);
  setTimeout(hideCenterMessage, 5250);
})();
</script>
