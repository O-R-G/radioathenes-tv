<?
  $item = $oo->get($uu->id);
  $media = $oo->media($item['id']);
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
    <? foreach($media as $m) {
      ?>
      <div class="<?= $item['id']; ?> media event">
        <img src="<?= m_url($m); ?>">
        <div class="caption-container">
          <div class="caption">
            <span><?= $m['caption']; ?></span>
          </div>
        </div>
      </div>
      <?
    }?>
  </div>
</div>

<script src="/static/js/global.js"></script>
<script>
(function() {
  var showing = [];
  var loopIdx = -1; // index of the looper
  
  // var events = document.getElementsByClassName('event');
  // var noise = document.getElementById('noise');
  var activeChannel = document.getElementById('active-channel');
  var activeChannel_span = document.querySelector('#active-channel span');
  var eventName = events_ids_to_orders[<?= $item['id']; ?>];
  activeChannel_span.innerText = eventName;

  function playPause() {
    if (looper) {
      clearInterval(looper);
      looper = null;
      showCenterMessage('PAUSED', true);
    } else {
      showCenterMessage('PLAY', false);
      setTimeout(hideCenterMessage, 1000);

      gotoIndex(loopIdx+1);
      looper = setInterval(function() {
        gotoIndex(loopIdx+1);
      }, 5000);
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
  var looper = setInterval(function() {
    gotoIndex(loopIdx+1);
  }, 5000);

  showCenterMessage('Channel ' + activeChannel.getElementsByTagName('span')[0].innerHTML, false);
  setTimeout(hideCenterMessage, 5250);
})();
</script>
