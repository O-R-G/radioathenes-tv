<?
  $events = $oo->children(getEventsID($oo, $root));
  usort($events, "date_sort");

  $item = $oo->get($uu->id);
  $media = $oo->media($item['id']);

?>
<div id="rotate-notice">
  <div>
    Please Rotate Your Device.
  </div>
</div>

<div class="left-container">
  <div id="active-channel" class="transparent click"><?= $item['name1']; ?></div>
  <ul id="picker">
  <?foreach($events as $event) {
    ?>
    <li><div class="<?= $event['id']; ?> event-button click">
      <a href="/events/<?= $event['url'] ?>"><?= $event['name1']; ?></a>
    </div></li>
      <?
  } ?>
  <li><a href="/">*</a></li>
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

<script>
  var showing = [];
  var loopIdx = -1; // index of the looper

  var events = document.getElementsByClassName('event');
  var noise = document.getElementById('noise');
  var activeChannel = document.getElementById('active-channel');
  var eventName = activeChannel.innerHTML;

  // picks a random noise gif based on weighted order (1/2, 1/4, 1/8, etc…)
  function pickWeightedRandomNoise() {
    var noiseGifs = noise.getElementsByTagName('img');
    var n = noiseGifs.length;

    // generate a number (0, 2^(n-1)]
    var random = Math.random()*Math.pow(2,(n-1));
    var choiceIdx = -1;
    for (var i = 1; i < n; i++) {
      // if between (2^(n-i-1)-2^(n-i)], then it is index i-1
      if (Math.pow(2,(n-i-1)) < random && random <= Math.pow(2, (n-i))) {
        choiceIdx = i-1;
      }
    }
    if (choiceIdx == -1) {
      choiceIdx = n-1;
    }

    for (var i = 0; i < n; i++) {
      noiseGifs[i].classList.add('hidden');
    }
    noiseGifs[choiceIdx].classList.remove('hidden');
  }

  activeChannel.onclick = function() {
    if (document.getElementById('picker').style.display == 'block') {
        document.getElementById('picker').style.display = 'none';
    } else {
      document.getElementById('picker').style.display = 'block';
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
      activeChannel.classList.remove('transparent');
      showing.push(events[(loopIdx)%events.length]);

    }, Math.random()*1000 + 250);
  }

  // runs the loop
  var looper = setInterval(function() {
    gotoIndex(loopIdx+1);
  }, 5000);
</script>
