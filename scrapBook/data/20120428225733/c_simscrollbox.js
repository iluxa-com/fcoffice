
var now_wheel=null;var scroller=function(a){var self=this;var container=document.getElementById(a+"_container");var scroller=document.getElementById(a+"_scroller");var scroll_bar=document.getElementById(a+"_scroll_bar");var clearselect=window.getSelection?function(){window.getSelection().removeAllRanges();}:function(){document.selection.empty();};this.resetRight=function(){var sTop=(scroller.clientHeight-scroll_bar.clientHeight)*container.scrollTop/(container.scrollHeight-container.clientHeight);(!isNaN(sTop))&&(scroll_bar.style.top=sTop+'px');};function onmousewheel(event){var e=e||window.event;var act=e.wheelDelta?e.wheelDelta/120:(0-e.detail/3);container.scrollTop-=act*80;self.resetRight();event.preventDefault&&event.preventDefault();event.stopPropagation&&event.stopPropagation();event.returnValue=false;event.cancelBubble=true;}
$e(container).bind('mousewheel',onmousewheel).bind('DOMMouseScroll',onmousewheel);$e(container).bind('mouseenter',function(){self.resetRight();});scroll_bar.ondrag=scroll_bar.oncontextmenu=scroll_bar.onselectstart=function(){return false;}
scroll_bar.onmousedown=function(event){clearselect();setCapture(this);window.moving=this;var y=(event||window.event).clientY;;var t=scroll_bar.offsetTop;document.onmousemove=function(event){var _y=(event||window.event).clientY;var sTop=Math.max(0,Math.min(_y-y+t,scroller.clientHeight-scroll_bar.clientHeight));scroll_bar.style.top=sTop+"px";container.scrollTop=(container.scrollHeight-container.clientHeight)*sTop/(scroller.clientHeight-scroll_bar.offsetHeight);};return false;};scroll_bar.onmouseup=function(){releaseCapture(this);window.moving=null;document.onmousemove=null;return false;};}
function stopDrag(){if(window.moving){window.moving.onmouseup();releaseCapture(window.moving);}}
if(window.addEventListener){document.addEventListener("mouseup",stopDrag,false);}else{document.attachEvent("onmouseup",stopDrag);}
function setCapture(element){if(element.setCapture){element.setCapture();}else if(window.captureEvents){window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);}}
function releaseCapture(element){if(element.releaseCapture){element.releaseCapture();}else if(window.releaseEvents){window.releaseEvents(Event.MOUSEMOVE|Event.MOUSEUP);}}/*  |xGv00|5d18d6587378133a0c6a4742aeeeec46 */