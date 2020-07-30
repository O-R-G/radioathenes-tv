<?
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
  var event_img_src = {};
  var event_img_caption = {};
  var current_event_img_src = [];
  var current_event_img_caption = [];
  var slideIdx = 0;
  var isReady = false;
  var ready_count = 0;
  var load_starting;
  var load_ending;
(function() {
  var eventIds = [<?foreach($events as $event) { echo $event['id'] . ','; }?>]; // array of event ids in chronological order
  var eventNames = [<?foreach($events as $event) { echo '"' . $event['name1'] . '", '; }?>]; // array of event names
  var eventLength = eventNames.length;
  var eventIdx = parseInt(eventLength * Math.random());
  console.log(eventIdx);
  var loadQueue = []; // prevent asynch race conditions
  var loading = false;

  var showing = [];
  var loopIdx = 0; // index of the looper
  var events = document.getElementsByClassName('event');
  var event_img = document.querySelectorAll('.event img');
  var event_caption_span = document.querySelectorAll('.caption-container span');
  var activeChannel_span = document.querySelector('#active-channel span');
  var looper;
  // var events_ids_to_orders
  // loader
  function preloadNext() {

    if (loading == true) {
      return;
    }
    loading = true;
    
    var nextEventId = loadQueue.shift();
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           // Typical action to be performed when the document is ready:
          var response = JSON.parse(xhttp.responseText);
          var eventMediaList = response['media'];
          var thisId = response['id'];
          preloadImg(eventMediaList, 0, thisId);

          loading = false;
          // if (loadQueue.length > 0) {
          //   preloadNext();
          // }
        }
    };
    xhttp.open("GET", "views/getEventMedia.php?id=" + nextEventId, true);
    xhttp.send();
  }

  // queues all the images to be loaded
  for (i = eventIdx; i < eventLength; i++) {
    loadQueue.push(eventIds[i]);
  }
  for(i = 0; i< eventIdx; i++){
    loadQueue.push(eventIds[i]);
  }
  eventIdx++; //some hack to jusitify. eventIdx starts from 1.
  preloadNext();

  function playPause() {
    if (looper) {
      clearInterval(looper);
      looper = null;
      showCenterMessage('PAUSED', true);
    } else {
      showCenterMessage('PLAY', false);
      setTimeout(hideCenterMessage, 2000);

      nextSlide();
      looper = setInterval(function() {
        nextSlide();
      }, 5000);
    }
  }


  function nextSlide(){
    if(loopIdx == 0)
      activeChannel_span.innerText = eventIdx;
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
      noise.classList.add('show-media');
      pickWeightedRandomNoise();
    setTimeout(function() {
      // show current
      noise.classList.remove('show-media');
      events[(loopIdx % 2)].classList.add('show-media');
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });

      // preload next
      loopIdx++;
      if(loopIdx > current_event_img_src.length - 1){
        nextEvent();
        loopIdx = 0;
      }
      events[(loopIdx % 2)].classList.remove('show-media');
      event_img[(loopIdx % 2)].src = current_event_img_src[loopIdx];
      event_caption_span[(loopIdx % 2)].innerText = current_event_img_caption[loopIdx];
    }, Math.random()*500 + 125);
  }
  function nextEvent(){
      eventIdx++;
      if(eventIdx > eventLength)
        eventIdx = 1;
      current_event_img_src = event_img_src[eventIdx];
      current_event_img_caption = event_img_caption[eventIdx];
  }
  function preloadImg(imageArray, index, id){
    index = index || 0;
    var this_order = events_ids_to_orders[id];
    var e = imageArray[index];
    if(typeof event_img_src[this_order] == 'undefined'){
      event_img_src[this_order] = [];
      event_img_caption[this_order] = [];
    }
    var img = new Image ();
    if (imageArray && imageArray.length > index+1) {
      
      img.addEventListener('load', function(){
        // console.log('load done!');
        preloadImg(imageArray, index + 1, id);
      });
    }
    else if(index+1 == imageArray.length){
      if (loadQueue.length > 0) {
        preloadNext();
      }
    }
    if(this_order == eventIdx){
      // first event
      load_starting = Date.now();
      img.addEventListener('load', function(){
        ready_count++;
        console.log(ready_count+'/'+imageArray.length);
        if(ready_count == imageArray.length && !isReady){
          console.log('ready!');
          load_ending = Date.now();
          isReady = true;
          current_event_img_src = event_img_src[eventIdx];
          current_event_img_caption = event_img_caption[eventIdx];
          event_img[(loopIdx % 2)].src = current_event_img_src[loopIdx];
          event_caption_span[(loopIdx % 2)].innerText = current_event_img_caption[loopIdx];
          console.log('loading time = '+ (load_ending - load_starting));
          if(load_ending - load_starting > 4000){
            nextSlide();
          }
          looper = setInterval(function() {
            nextSlide();
          }, 4000);

        }
      });
    }
    img.src = e['url'];
    event_img_src[this_order].push(e['url']);
      if (e['caption'] != '') {
        event_img_caption[this_order].push(e['caption']);
    }
    else{
      event_img_caption[this_order].push('');
    }    
  }
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
      events[(loopIdx % events.length)].classList.add('show-media');
      var id = parseInt(events[loopIdx%events.length].classList[0]);
      activeChannel.innerHTML = '<span class="system-message">' + id + '</span>';
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
      showing.push(events[(loopIdx)%events.length]);
    }, Math.random()*500 + 125);
  }
  
  document.getElementById('container').onclick = playPause;
  
  // runs the loop
  
})();
var body = document.body;
var isFullscreen = false;
window.addEventListener('resize', function(){
  console.log('resized!');
  console.log(window.innerWidth);
  console.log(window.innerHeight);
  if(window.innerWidth > window.innerHeight)
    body.requestFullscreen();
  else
    Document.exitFullscreen();
});
body.addEventListener('click', function(){
  if(!isFullscreen)
    body.requestFullscreen();
  else
    Document.exitFullscreen();
  isFullscreen = !isFullscreen;
});
</script>
