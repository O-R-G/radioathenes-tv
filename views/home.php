<?
  // $events is from menu.pgp
  $events_num = count($events);
  $media_all = array();

  for($i = 0; $i < $events_num; $i++){
    $this_id = $events[$i]['id'];
    $this_media = $oo->media($this_id);
    $this_media_arr = array();
    if ($this_media) {
      foreach($this_media as $m) {
        $this_media_arr[] = array(
          'event_name' => $events[$i]['name1'],
          'url' => m_url($m),
          'caption' => $m['caption']
        );
      }
      $media_all[] = $this_media_arr;
    }
  }
  // media_all = array of arrays (url, caption) in chronological order;  
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
  var media_all = <? echo json_encode($media_all); ?>;
  // console.log(media_all);
  // var media_url_all = <? echo json_encode($media_url_all, true); ?>;
  // var media_caption_all = <? echo json_encode($media_caption_all, true); ?>;
  for(i = 0 ; i < media_all.length ; i++){
    // console.log(i);
    // console.log(media_all[i][0]['event_name']);
  }
  var event_img_src = {};
  var event_img_caption = {};
  var current_event_img_src = [];
  var current_event_img_caption = [];
  var slideIdx = 0;
  var ready_count = 0;
  var load_starting;
  var load_ending;
  var eventIds = [<?foreach($events as $event) { echo $event['id'] . ','; }?>]; // array of event ids in chronological order
  var eventLength = media_all.length;
  var loopIdx = 0; // index of the images within the current event
  var eventIdx = parseInt(eventLength * Math.random()) + 1; // 1 to eventLength;
  var eventIdx_loading = eventIdx - 1; // 0 to eventLength;
  var media_current = media_all[eventIdx - 1];

(function() {
  var eventPreloadCounter = 1;
  var loadQueue = []; // prevent asynch race conditions
  var loading = false;

  var showing = [];
  
  var events = document.getElementsByClassName('event');
  var event_img = document.querySelectorAll('.event img');
  var event_caption_span = document.querySelectorAll('.caption-container span');
  var activeChannel_span = document.querySelector('#active-channel span');
  var looper,
      looper_resume, 
      centerMessage;
  var looper_hasStarted = false;
  var slideInterval = 5000;
  var slideBegin;
  var slideRemain = slideInterval;
  var slidePlaying = false;
  var beginningDelay = 1500;
  
  // starting with first image of the first event in the randomized order, load every images.
  function preload_eventImg(event_idx){
    var this_event_idx = event_idx;
    var this_media_arr = media_all[this_event_idx];
    var this_media_num = this_media_arr.length;
    var img_idx = 0;
    var img = new Image ();
    img.addEventListener('load', function(){
      ready_count++;
      if(eventPreloadCounter == eventLength){
        return false;
      }
      else if(img_idx >= this_media_num - 1)
      {
        eventPreloadCounter ++;
        this_event_idx ++;
        if(this_event_idx == eventLength)
          this_event_idx = 0;
        this_media_arr = media_all[this_event_idx];
        this_media_num = this_media_arr.length;
        img_idx = 0;
      }
      else
      {
        img_idx ++;
        if(this_event_idx == eventIdx_loading && ( img_idx == this_media_num - 1 || ready_count > 10) && !looper_hasStarted ){
          looper_hasStarted = true;
          event_img[0].src = media_current[0]['url'];
          event_caption_span[0].innerText = media_current[0]['caption'];
          setTimeout(function(){
            nextSlide();
            looper = setInterval(function() {
              nextSlide();
            }, slideInterval);
          }, beginningDelay);
        }
        img.src = this_media_arr[img_idx]['url'];
      }
    });
    img.src = this_media_arr[img_idx]['url'];
  }
  preload_eventImg(eventIdx_loading);

  function playPause() {
    if (slidePlaying) {
      slidePlaying = false;
      slideRemain = slideRemain - (Date.now() - slideBegin);
      clearInterval(looper);
      clearTimeout(looper_resume);
      clearTimeout(centerMessage);
      looper = null;
      looper_resume = null;
      showCenterMessage('PAUSED', true);
    } else {
      slidePlaying = true;
      slideBegin = Date.now();
      showCenterMessage('PLAY', false);
      centerMessage = setTimeout(hideCenterMessage, 2000);
      looper_resume = setTimeout(function(){
        nextSlide();
        looper = setInterval(function() {
          nextSlide();
        }, slideInterval);
      }, slideRemain);
    }
  }


  function nextSlide(){
    slidePlaying = true;
    slideBegin = Date.now();
    slideRemain = slideInterval;
    var this_noise_duration = Math.random()*500 + 125;
    if(loopIdx == 0){
      console.log('first image');
      activeChannel_span.innerText = eventIdx;
      this_noise_duration = 2000;
      event_img[(loopIdx % 2)].src = media_current[loopIdx]['url'];
      event_caption_span[(loopIdx % 2)].innerText = media_current[loopIdx]['caption'];
      events[(loopIdx % 2)].classList.remove('show-media');
      showCenterMessage('CHANNEL '+eventIdx, false);
      centerMessage = setTimeout(function(){
        hideCenterMessage();
      }, 2000);
    }
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
    noise.classList.add('show-media');
    pickWeightedRandomNoise();
    setTimeout( function() {
      // show current
      noise.classList.remove('show-media');
      events[(loopIdx % 2)].classList.add('show-media');
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
      loopIdx++;
      if(loopIdx > media_current.length-1)
      {
        events[(loopIdx % 2)].classList.remove('show-media');
        nextEvent();
        loopIdx = 0;
      }
      else
      {
        event_img[(loopIdx % 2)].src = media_current[loopIdx]['url'];
        event_caption_span[(loopIdx % 2)].innerText = media_current[loopIdx]['caption'];
        events[(loopIdx % 2)].classList.remove('show-media');
      }

    }, this_noise_duration);
  }
  function nextEvent(){
    
    
    eventIdx++;
    if(eventIdx > eventLength)
      eventIdx = 1;
    media_current = media_all[eventIdx-1];
  }
  
  // function gotoIndex(idx) {

  //   if (loopIdx != -1) {
  //     showing.forEach(function(e) {
  //       e.classList.remove('show-media');
  //     });
  //     showing = [];
  //     [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
  //     noise.classList.add('show-media');
  //     pickWeightedRandomNoise();
  //   }
  //   setTimeout(function() {
  //     noise.classList.remove('show-media');
  //     loopIdx = idx;
  //     events[(loopIdx % events.length)].classList.add('show-media');
  //     var id = parseInt(events[loopIdx%events.length].classList[0]);
  //     activeChannel.innerHTML = '<span class="system-message">' + id + '</span>';
  //     [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
  //     showing.push(events[(loopIdx)%events.length]);
  //   }, Math.random()*500 + 125);
  // }
  
  document.getElementById('container').onclick = playPause;
  
})();
var body = document.body;
if(window.innerWidth < 500){
  window.addEventListener('resize', function(){
  if(window.innerWidth > window.innerHeight)
    body.requestFullscreen();
  else
    Document.exitFullscreen();
  });
}

</script>
