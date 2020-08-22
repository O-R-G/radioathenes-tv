var event_all = {};
var current_media = [];

// var event_ctner = document.getElementsByClassName('event-ctner');
// var events = event_ctner[0].querySelectorAll('.event');
var events = document.getElementsByClassName('event');
var events_img = document.querySelectorAll('.event img');
var events_caption = document.querySelectorAll('.caption span');
var activeChannel_span = document.querySelector('#active-channel span');
var current_event_order;


var beginningDelay = 1500;
var slideInterval = 5000;
var slideBegin;
var slideRemain = slideInterval;
var loopIdx = -1;
var eventIdx = 0;

var slidePlaying = false;
var looper_hasStarted = false;
var looper,
    looper_resume, 
    centerMessage;

var eventLength = 0;

function nextSlide(){
  slidePlaying = true;
  // slideBegin = Date.now();
  slideRemain = slideInterval;
  events[0].classList.remove('show-media');
  events[1].classList.remove('show-media');

  var this_noise_duration = Math.random()*500 + 125;

  if(loopIdx == -1){
    noise.classList.remove('show-media');
    loopIdx++;
    events_img[loopIdx%2].src = current_media[loopIdx]['url'];
    events_caption[loopIdx%2].innerText = current_media[loopIdx]['caption'];
    if(current_media.length > 1){
      events_img[(loopIdx+1)%2].src = current_media[loopIdx+1]['url'];
      events_caption[(loopIdx+1)%2].innerText = current_media[loopIdx+1]['caption'];
    }
    events[loopIdx%2].classList.add('show-media');
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
  }
  else
  {
    loopIdx++;
    if(loopIdx <= current_media.length-1){
      events_img[loopIdx%2].src = current_media[loopIdx]['url'];
      events_caption[loopIdx%2].innerText = current_media[loopIdx]['caption'];
    }
    else{
      if(!isSingleEvent)
        activeChannel_span.innerText = '';
    }
    if(loopIdx <= current_media.length-2){
      events_img[(loopIdx+1)%2].src = current_media[loopIdx+1]['url'];
      events_caption[(loopIdx+1)%2].innerText = current_media[loopIdx+1]['caption'];
    }
    
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
    noise.classList.add('show-media');
    pickWeightedRandomNoise();
    setTimeout( function() {
      // show current
      noise.classList.remove('show-media');
      events[loopIdx%2].classList.remove('show-media');
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
      
      if(loopIdx > current_media.length-1)
      {
        if(isSingleEvent){
          loopIdx = 0;
          events_img[loopIdx%2].src = current_media[loopIdx]['url'];
          events_caption[loopIdx%2].innerText = current_media[loopIdx]['caption'];
          events[loopIdx%2].classList.add('show-media');
        }
        else{
          nextEvent();
        }
      }
      else
      {
        
        events[loopIdx%2].classList.add('show-media');
      }

    }, this_noise_duration);
  }
}
function nextEvent(){
  clearInterval(looper);
  loopIdx = -1;
  eventIdx++;
  if(eventIdx > eventLength - 1)
    eventIdx = 0;
  current_media = event_all[eventIdx]['media'];
  current_event_order = event_all[eventIdx]['order'];
  activeChannel_span.innerText = current_event_order;

  [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
  noise.classList.add('show-media');

  setTimeout(function(){
    events_img[0].src = current_media[0]['url'];
    events_caption[0].innerText = current_media[0]['caption'];
  }, 0);

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

document.getElementById('container').onclick = playPause;
