<?
  $events = $oo->children(getEventsID($oo, $root));
  // usort($events, "id_sort");
  usort($events, "date_sort");

  $credit_id = end($oo->urls_to_ids(array('system', 'credit')));
  $item = $oo->get($credit_id);
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
    <div class = 'media' id = "credit"><?= $item['deck'] ?></div>
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
(function() {
  var showing = [];
  var loopIdx = -1; // index of the looper

  // var events = document.getElementsByClassName('event');
  var noise = document.getElementById('noise');
  var activeChannel = document.getElementById('active-channel');
  var eventName = "<?= $item['name1']  ?>";
  var credit = document.getElementById('credit');
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

  // activeChannel.onclick = function() {
  //   if (document.getElementById('picker').style.display == 'block') {
  //       document.getElementById('picker').style.display = 'none';
  //   } else {
  //     document.getElementById('picker').style.display = 'block';
  //   }
  // }

  // function playPause() {
  //   if (looper) {
  //     clearInterval(looper);
  //     looper = null;
  //     showCenterMessage('PAUSED', true);
  //   } else {
  //     showCenterMessage('PLAY', false);
  //     setTimeout(hideCenterMessage, 1000);

  //     gotoIndex(loopIdx+1);
  //     looper = setInterval(function() {
  //       gotoIndex(loopIdx+1);
  //     }, 5000);
  //   }
  // }

  // var captions = document.getElementsByClassName('caption')
  // for (var i = 0; i < captions.length; i++) {
  //   captions[i].onclick = hideShowCaptions;
  // }
  // document.getElementById('container').onclick = playPause;

  // goes to an index with noise transition
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
  //     events[loopIdx%events.length].classList.add('show-media');
  //     [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
  //     showing.push(events[(loopIdx)%events.length]);

  //   }, Math.random()*500 + 125);
  // }

  // runs the loop
  // var looper = setInterval(function() {
  //   gotoIndex(loopIdx+1);
  // }, 5000);
  function displayCredit(){
    noise.classList.remove('show-media');
    credit.classList.add('show-media');
  }

  showCenterMessage('Channel ' + activeChannel.getElementsByTagName('span')[0].innerHTML, false);
  setTimeout(hideCenterMessage, 250);
  setTimeout(displayCredit, 250);
})();
</script>
