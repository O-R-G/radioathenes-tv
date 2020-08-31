var event_all = {};
var current_media = [];
var next_media = [];

// var event_ctner = document.getElementsByClassName('event-ctner');
// var events = event_ctner[0].querySelectorAll('.event');
var events = document.getElementsByClassName('event');
var events_img = document.querySelectorAll('.event img');
var events_caption = document.querySelectorAll('.caption span');
var activeChannel_span = document.querySelector('#active-channel span');
var current_event_order;

var beginningDelay = 1500;
var slideInterval = 2500;
var slideBegin;
var slideRemain = slideInterval;
var preloadIdx = 0;
var loopIdx = -1;
var eventIdx = 0;

var slidePlaying = false;
var looper_hasStarted = false;
var looper,
    looper_resume, 
    centerMessage;

var eventLength = 0;
var sContainer = document.getElementById('container');

function nextSlide(){
  slidePlaying = true;
  // slideBegin = Date.now();
  slideRemain = slideInterval;
  
  // events[1].classList.remove('show-media');

  var this_noise_duration = Math.random()*500 + 125;

  if(loopIdx == -1){
    noise.classList.remove('show-media');
    loopIdx++;
    
    events[loopIdx].classList.add('show-media');
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
  }
  else
  {
    events[loopIdx].classList.remove('show-media');
    loopIdx++;
    if(loopIdx > current_media.length -1){
      if(!isSingleEvent){
        activeChannel_span.innerText = '';
      }
    }  
    
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
    noise.classList.add('show-media');
    pickWeightedRandomNoise();
    setTimeout( function() {
      // show current
      noise.classList.remove('show-media');
      // events[loopIdx%2].classList.remove('show-media');
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
      
      if(loopIdx > current_media.length-1)
      {
        if(isSingleEvent){
          loopIdx = 0;
          events[loopIdx].classList.add('show-media');
        }
        else{
          nextEvent();
        }
      }
      else
      {
        
        events[loopIdx].classList.add('show-media');
      }

    }, this_noise_duration);
  }
}
function nextEvent(){
  console.log('nextEvent');
  clearInterval(looper);
  loopIdx = -1;
  eventIdx++;
  if(eventIdx > eventLength - 1)
    eventIdx = 0;
  current_media = event_all[eventIdx]['media'];
  if(eventIdx+1 > eventLength - 1)
    next_media = event_all[0]['media'];
  else
    next_media = event_all[eventIdx+1]['media'];
  console.log('start preload next event: '+next_media.length);
  preloadImages(0, next_media);

  current_event_order = event_all[eventIdx]['order'];
  activeChannel_span.innerText = current_event_order;
  var sEvent = document.getElementsByClassName('event');
  
  while(sEvent[0])
    sEvent[0].parentNode.removeChild(sEvent[0]);

  init(sContainer, current_media);
  [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
  noise.classList.add('show-media');

  // setTimeout(function(){
  //   events_img[0].src = current_media[0]['url'];
  //   events_caption[0].innerText = current_media[0]['caption'];
  // }, 0);

  showCenterMessage('CHANNEL '+current_event_order, false);
  centerMessage = setTimeout(function(){
    hideCenterMessage();
  }, 2000);

  setTimeout(function(){
    nextSlide();
    looper = setInterval(function() {
      nextSlide();
    }, slideInterval);
  }, 2250);

}
function playPause() {
  if (slidePlaying) {
    slidePlaying = false;
    // slideRemain = slideRemain - (Date.now() - slideBegin);
    clearInterval(looper);
    clearTimeout(looper_resume);
    clearTimeout(centerMessage);
    looper = null;
    looper_resume = null;
    showCenterMessage('PAUSED', true);
  } else {
    slidePlaying = true;
    // slideBegin = Date.now();
    showCenterMessage('PLAY', false);
    centerMessage = setTimeout(hideCenterMessage, 2000);
    looper_resume = setTimeout(function(){
      nextSlide();
      looper = setInterval(function() {
        nextSlide();
      }, slideInterval);
    }, 1500);
  }
}
function init(ctner, current_m) {
  for(i = 0 ; i < current_m.length ; i++){
    console.log('init '+i+'th div');
    var thisEvent = document.createElement('DIV');
    thisEvent.className = 'event media';
    var thisImg = document.createElement('IMG');
    thisImg.src = current_m[i]['url'];
    var thisCaptionContainer = document.createElement('DIV');
    thisCaptionContainer.className = 'caption-container';
    var thisCaption = document.createElement('DIV');
    thisCaption.className = 'caption';
    var thisSpan = document.createElement('SPAN');
    thisSpan.innerText = current_m[i]['caption'];
    thisCaption.appendChild(thisSpan);
    thisCaptionContainer.appendChild(thisCaption);
    thisEvent.appendChild(thisImg);
    thisEvent.appendChild(thisCaptionContainer);
    ctner.appendChild(thisEvent);
  }
}

document.getElementById('container').onclick = playPause;
