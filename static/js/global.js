var noise = document.getElementById('noise');
var activeChannel = document.getElementById('active-channel');
var events = document.getElementsByClassName('event');
var looper = null;
var body = document.body;
document.getElementById('cc').onclick = hideShowCaptions;

activeChannel.onclick = function() {
  if (document.getElementById('picker').style.display == 'block') {
    body.classList.remove('viewing_menu');
      document.getElementById('picker').style.display = 'none';
  } else {
    body.classList.add('viewing_menu');
    document.getElementById('picker').style.display = 'block'; 
  }
}

function showCenterMessage(message, blink) {
  document.getElementById('center-message').innerHTML = message;
  if (blink)
    document.getElementById('center-message').classList.add('blink');
  else
    document.getElementById('center-message').classList.remove('blink');
  document.getElementById('center-notice').style.display = 'flex';
}

function hideCenterMessage() {
  document.getElementById('center-message').classList.remove('blink');
  document.getElementById('center-notice').style.display = 'none';
}

function hideShowCaptions(e) {
  e.stopPropagation();
  var captions = document.getElementsByClassName('caption-container');
  if (captions[0].style.display == 'none') {
    showCenterMessage("SHOW CAPTIONS", false);
    setTimeout(hideCenterMessage, 2000);

    document.getElementById('cc').classList.remove('invert');
    for (var i = 0; i < captions.length; i++) {
      captions[i].style.display = '';
    }
  } else {
    showCenterMessage("HIDE CAPTIONS", false);
    setTimeout(hideCenterMessage, 2000);

    document.getElementById('cc').classList.add('invert');
    for (var i = 0; i < captions.length; i++) {
      captions[i].style.display = 'none';
    }
  }
}

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

var body = document.body;
if(window.innerWidth < 500){
  window.addEventListener('resize', function(){
  if(window.innerWidth > window.innerHeight)
    body.requestFullscreen();
  else
    Document.exitFullscreen();
  });
}