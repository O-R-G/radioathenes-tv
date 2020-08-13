var event_ctner = document.getElementsByClassName('event-ctner');
var events = event_ctner[0].querySelectorAll('.event');
var activeChannel_span = document.querySelector('#active-channel span');
var current_event_order;


var beginningDelay = 1500;
var slideInterval = 3000;
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
  slideBegin = Date.now();
  slideRemain = slideInterval;
  var this_noise_duration = Math.random()*500 + 125;

  if(loopIdx == -1){
    noise.classList.remove('show-media');
    loopIdx++;
    events[loopIdx].classList.add('show-media');
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
  }
  else
  {
    [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
    noise.classList.add('show-media');
    pickWeightedRandomNoise();
    setTimeout( function() {
      // show current
      noise.classList.remove('show-media');
      events[loopIdx].classList.remove('show-media');
      [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.remove('transparent') });
      loopIdx++;
      if(loopIdx > events.length-1)
      {
        if(isSingleEvent){
          loopIdx = 0;
          events[loopIdx].classList.add('show-media');
        }
        else{
          loopIdx = -1;
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
  clearInterval(looper);
  loopIdx = -1;
  eventIdx++;
  if(eventIdx > eventLength - 1)
    eventIdx = 0;
  events = event_ctner[eventIdx].querySelectorAll('.event');
  current_event_order = event_ctner[eventIdx].getAttribute('order');
  activeChannel_span.innerText = current_event_order;

  [].forEach.call(document.getElementsByClassName('hideable'), function(e) { e.classList.add('transparent') });
  noise.classList.add('show-media');

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
    slideRemain = slideRemain - (Date.now() - slideBegin);
    clearInterval(looper);
    clearTimeout(looper_resume);
    clearTimeout(centerMessage);
    looper = null;
    looper_resume = null;
    showCenterMessage('PAUSED', true);
  } else {
    slidePlaying = true;
    slideBegin = Date.now();
    showCenterMessage('PLAY', false);
    centerMessage = setTimeout(hideCenterMessage, 2000);
    looper_resume = setTimeout(function(){
      nextSlide();
      looper = setInterval(function() {
        nextSlide();
      }, slideInterval);
    }, slideRemain);
  }
}

document.getElementById('container').onclick = playPause;
