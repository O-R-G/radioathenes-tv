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
  </div>
</div>

<script src="/static/js/global.js"></script>
<script>
  var event_img_src = {};
  var event_img_caption = {};
  var current_event_img_src = [];
  var current_event_img_caption = [];
  var slideIdx = 0;
(function() {
  var eventIds = [<?foreach($events as $event) { echo $event['id'] . ','; }?>]; // array of event ids in chronological order
  var eventNames = [<?foreach($events as $event) { echo '"' . $event['name1'] . '", '; }?>]; // array of event names
  var eventLength = eventNames.length;
  var eventIdx = parseInt(eventLength * Math.random());
  var loadQueue = []; // prevent asynch race conditions
  var loading = false;

  var showing = [];
  var loopIdx = 0; // index of the looper
  var events = document.getElementsByClassName('event');
  var event_img = document.querySelectorAll('.event img');
  var event_caption_span = document.querySelectorAll('.caption-container span');

  // loader
  function preloadNext() {

    if (loading == true) {
      return;
    }
    loading = true;
    var nextEventId = loadQueue.shift();
    // load the element with new http request
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           // Typical action to be performed when the document is ready:
          var response = JSON.parse(xhttp.responseText);
          var eventMediaList = response['media'];
          eventMediaList.forEach(function(e) {
            var newDiv = document.createElement("div");
            var this_order = events_ids_to_orders[response['id']];
            newDiv.classList.add(this_order);
            newDiv.classList.add('media');
            newDiv.classList.add('event');
            var newImage = new Image();
            newImage.src = e['url'];
            newDiv.appendChild(newImage);

            if (e['caption'] != '') {
                var newCaptionContainerDiv = document.createElement("div");
                newCaptionContainerDiv.classList.add('caption-container');
                newDiv.appendChild(newCaptionContainerDiv);

                var newCaptionDiv = document.createElement("div");
                newCaptionDiv.classList.add('caption');
                newCaptionContainerDiv.appendChild(newCaptionDiv);

                var newCaptionSpanDiv = document.createElement("span");
                newCaptionDiv.appendChild(newCaptionSpanDiv);

                newCaptionSpanDiv.innerHTML = e['caption'];
            }
            document.getElementById('container').appendChild(newDiv);
          });

          // update medias
          events = document.getElementsByClassName('event');
          // console.log(events);
          loading = false;
          if (loadQueue.length > 0) {
            preloadNext();
          }
        }
    };
    xhttp.open("GET", "views/getEventMedia.php?id=" + nextEventId, true);
    xhttp.send();
  }

  // queues all the images to be loaded
  for (i = eventIdx; i < eventLength; i++) {
    loadQueue.push(eventIds[(i)]);
  }
  for(i = 0; i< eventIdx; i++){
    loadQueue.push(eventIds[(i)]);
  }
  preloadNext();

  function playPause() {
    if (looper) {
      clearInterval(looper);
      looper = null;
      showCenterMessage('PAUSED', true);
    } else {
      showCenterMessage('PLAY', false);
      setTimeout(hideCenterMessage, 2000);

      gotoIndex(loopIdx+1);
      looper = setInterval(function() {
        gotoIndex(loopIdx+1);
      }, 5000);
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
  var looper = setInterval(function() {
    gotoIndex(loopIdx+1);
  }, 5000);
})();
</script>
