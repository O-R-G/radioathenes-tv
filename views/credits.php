<?
  // $events = $oo->children(getEventsID($oo, $root));
  // usort($events, "date_sort");

  $item = $credit;
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
  var activeChannel_span = document.querySelector('#active-channel span');
  activeChannel_span.innerText = 'C';
  var noise = document.getElementById('noise');
  var activeChannel = document.getElementById('active-channel');
  var eventName = "<?= $item['name1']  ?>";
  var credit = document.getElementById('credit');
  
  function displayCredit(){
    noise.classList.remove('show-media');
    credit.classList.add('show-media');
  }

  showCenterMessage('Channel ' + activeChannel.getElementsByTagName('span')[0].innerHTML, false);
  setTimeout(hideCenterMessage, 250);
  setTimeout(displayCredit, 250);
})();
</script>
