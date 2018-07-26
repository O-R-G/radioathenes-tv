<?
  $events = $oo->children(getEventsID($oo, $root));
  usort($events, "date_sort");
?>
<div id="rotate-notice" class="message-full system-message">
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
<div id="cc" class="system-message">
  CC
</div>

<div class="left-container">
  <div id="active-channel" class="transparent click"><span class="system-message"></span></div>
  <ul id="picker">
  <?foreach($events as $event) {
    ?>
    <li><div class="<?= $event['id']; ?> event-button click">
      <a href="/events/<?= $event['url'] ?>" class="system-message"><?= $event['id']; ?> <?= $event['name1']; ?></a>
    </div></li>
      <?
  } ?>
  </ul>
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
    <!--  -->
  </div>
</div>

<script src="/static/js/global.js"></script>
<script>
(function() {
  var eventIds = [<?foreach($events as $event) { echo $event['id'] . ','; }?>]; // array of event ids in chronological order
  var eventNames = [<?foreach($events as $event) { echo '"' . $event['name1'] . '", '; }?>]; // array of event names
  var eventIdx = 0; // keeps track of the index of the next event submitted to be loaded

  var loadQueue = []; // prevent asynch race conditions
  var loading = false;

  var showing = [];
  var loopIdx = -1; // index of the looper

  // var events = document.getElementsByClassName('event');
  // var noise = document.getElementById('noise');
  // var eventButtons = document.getElementsByClassName('event-button');
  // var activeChannel = document.getElementById('active-channel');

  // picks a random noise gif based on weighted order (1/2, 1/4, 1/8, etc…)
  // function pickWeightedRandomNoise() {
  //   var noiseGifs = noise.getElementsByTagName('img');
  //   var n = noiseGifs.length;
  //
  //   // generate a number (0, 2^(n-1)]
  //   var random = Math.random()*Math.pow(2,(n-1));
  //   var choiceIdx = -1;
  //   for (var i = 1; i < n; i++) {
  //     // if between (2^(n-i-1)-2^(n-i)], then it is index i-1
  //     if (Math.pow(2,(n-i-1)) < random && random <= Math.pow(2, (n-i))) {
  //       choiceIdx = i-1;
  //     }
  //   }
  //   if (choiceIdx == -1) {
  //     choiceIdx = n-1;
  //   }
  //
  //   for (var i = 0; i < n; i++) {
  //     noiseGifs[i].classList.add('hidden');
  //   }
  //   noiseGifs[choiceIdx].classList.remove('hidden');
  // }

  // loader
  function loadNext() {
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
            newDiv.classList.add(response['id']);
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
                newCaptionDiv.onclick = hideShowCaptions;
                newCaptionContainerDiv.appendChild(newCaptionDiv);

                var newCaptionSpanDiv = document.createElement("span");
                // newCaptionSpanDiv.classList.add('');
                newCaptionDiv.appendChild(newCaptionSpanDiv);

                newCaptionSpanDiv.innerHTML = e['caption'];
            }

            document.getElementById('container').appendChild(newDiv);
          });

          // update medias
          events = document.getElementsByClassName('event');
          loading = false;
          if (loadQueue.length > 0) {
            loadNext();
          }
        }
    };
    xhttp.open("GET", "views/getEventMedia.php?id=" + nextEventId, true);
    xhttp.send();
  }

  // queues all the images to be loaded
  for (var i = eventIdx; i < 1; i++) {
    loadQueue.push(eventIds[eventIdx++]);
  }
  loadNext();

  // setup jumping to indexes
  // for (var i = 0; i < eventButtons.length; i++) {
  //   var eventId = parseInt(eventButtons[i].classList[0]);
  //   eventButtons[i].onclick = clickFunction(eventId);
  // }

  // handles clicks on the events
  // function clickFunction(id) {
  //   return function() {
  //     var subevents = document.getElementsByClassName('event ' + id);
  //     var gotoIdx = Array.prototype.indexOf.call(events, subevents[0]);
  //     document.getElementById('picker').style.display = 'none';
  //     gotoIndex(gotoIdx);
  //   }
  // }

  // activeChannel.onclick = function() {
  //   if (document.getElementById('picker').style.display == 'block') {
  //       document.getElementById('picker').style.display = 'none';
  //   } else {
  //     document.getElementById('picker').style.display = 'block';
  //   }
  // }
  //
  // document.getElementById('container').onclick = playPause;
  // document.getElementById('container').getElementsByTagName('img').onclick = playPause;

  // var captions = document.getElementsByClassName('caption');
  // for (var i = 0; i < captions.length; i++) {
  //   captions[i].onclick = hideShowCaptions;
  // }

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

  // goes to an index with noise transition
  function gotoIndex(idx) {
    if (loopIdx != -1) {
      showing.forEach(function(e) {
        e.classList.remove('show-media');
      });
      showing = [];
      activeChannel.classList.add('transparent');
      noise.classList.add('show-media');
      pickWeightedRandomNoise();
    }
    setTimeout(function() {
      noise.classList.remove('show-media');
      loopIdx = idx;
      events[loopIdx%events.length].classList.add('show-media');
      var id = parseInt(events[loopIdx%events.length].classList[0]);
      activeChannel.innerHTML = '<span class="system-message">' + id + '</span>';
      activeChannel.classList.remove('transparent');
      showing.push(events[(loopIdx)%events.length]);

    }, Math.random()*1000 + 250);
  }

  document.getElementById('container').onclick = playPause;

  // runs the loop
  var looper = setInterval(function() {
    gotoIndex(loopIdx+1);
  }, 5000);
})();
</script>
