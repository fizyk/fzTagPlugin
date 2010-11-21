/* Copyright 2010 Ian George - http://www.iangeorge.net

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/


(function($){$.fn.tagcloud=function(options){var opts=$.extend($.fn.tagcloud.defaults,options);opts.drawing_interval=1/(opts.fps/1000);$(this).each(function(){new TagCloudClass($(this),opts);});return this;};$.fn.tagcloud.defaults={zoom:75,max_zoom:120,min_zoom:25,zoom_factor:2,rotate_by:-1.75,fps:10,centrex:250,centrey:250,min_font_size:12,max_font_size:32,font_units:'px',random_points:0};var TagCloudClass=function(el,options){$(el).css('position','relative');$('ul',el).css('display','none');var eyez=-500;var rad=Math.PI/180;var basecos=Math.cos(options.rotate_by*rad);var basesin=Math.sin(options.rotate_by*rad);var sin=basesin;var cos=basecos;var hex=new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");var container=$(el);var id_stub='tc_'+$(this).attr('id')+"_";var opts=options;var zoom=opts.zoom;var depth;var lastx=0;var lasty=0;var points=[];points['data']=[];var drawing_interval;var cmx=options.centrex;var cmy=options.centrey;function getgrey(num){if(num>256){num=256;}
if(num<0){num=0;}
var rem=num%16;var div=(num-rem)/16;var dig=hex[div]+hex[Math.floor(rem)];return dig+dig+dig;}
function rotx(){for(var p in points.data)
{var temp=sin*points.data[p].y+cos*points.data[p].z;points.data[p].y=cos*points.data[p].y-sin*points.data[p].z;points.data[p].z=temp;}}
function roty(){for(var p in points.data){var temp=-sin*points.data[p].x+cos*points.data[p].z;points.data[p].x=cos*points.data[p].x+sin*points.data[p].z;points.data[p].z=temp;}}
function rotz(){for(var p in points.data){var temp=sin*points.data[p].x+cos*points.data[p].y;points.data[p].x=cos*points.data[p].x-sin*points.data[p].y;points.data[p].y=temp;}}
function zoomed(by){zoom+=by*opts.zoom_factor;if(zoom>opts.max_zoom){zoom=opts.max_zoom;}
if(zoom<opts.min_zoom){zoom=opts.min_zoom;}
depth=-(zoom*(eyez-opts.max_zoom)/100)+eyez;}
function moved(mx,my){if(mx>lastx){sin=-basesin;roty();}
if(mx<lastx){sin=basesin;roty();}
if(my>lasty){sin=basesin;rotx();}
if(my<lasty){sin=-basesin;rotx();}
lastx=mx;lasty=my;}
function draw(){var normalz=depth*depth;var minz=0;var maxz=0;for(var r_p in points.data){if(points.data[r_p].z<minz){minz=points.data[r_p].z;}
if(points.data[r_p].z>maxz){maxz=points.data[r_p].z;}}
var diffz=minz-maxz;for(var s_p in points.data){var u=(depth-eyez)/(points.data[s_p].z-eyez);var grey=parseInt((points.data[s_p].z/diffz)*165+80);var grey_hex=getgrey(grey);$('#'+points.data[s_p].id+' a',container).css('color','#'+grey_hex);$('#'+points.data[s_p].id,container).css('z-index',grey);$('#'+points.data[s_p].id,container).css('left',u*points.data[s_p].x+cmx-points.data[s_p].cwidth);$('#'+points.data[s_p].id,container).css('top',u*points.data[s_p].y+cmy);}}
points.count=$('li a',container).length;points.largest=1;points.smallest=0;$('li a',container).each(function(idx,val){var sz=parseInt($(this).attr('rel'));if(sz==0)
sz=1;points.data[idx]={id:id_stub+idx,size:sz};var h=-1+2*(idx)/(points.count-1);points.data[idx].theta=Math.acos(h);if(idx==0||idx==points.count-1){points.data[idx].phi=0;}
else{points.data[idx].phi=(points.data[idx-1].phi+3.6/Math.sqrt(points.count*(1-Math.pow(h,2))))%(2*Math.PI);}
points.data[idx].x=Math.cos(points.data[idx].phi)*Math.sin(points.data[idx].theta)*(cmx/2);points.data[idx].y=Math.sin(points.data[idx].phi)*Math.sin(points.data[idx].theta)*(cmy/2);points.data[idx].z=Math.cos(points.data[idx].theta)*(cmx/2);if(sz>points.largest)points.largest=sz;if(sz<points.smallest)points.smallest=sz;container.append('<div id="'+id_stub+idx+'" class="point" style="position:absolute;"><a href='+$(this).attr('href')+'>'+$(this).html()+'</a></div>');});if(opts.random_points>0){for(b=0;b<opts.random_points;b++){points.count++;points.data[points.count]={id:id_stub+points.count,size:1};points.data[points.count].theta=Math.random()*2*Math.PI;points.data[points.count].phi=Math.random()*2*Math.PI;points.data[points.count].x=Math.cos(points.data[points.count].phi)*Math.sin(points.data[points.count].theta)*(cmx/2);points.data[points.count].y=Math.sin(points.data[points.count].phi)*Math.sin(points.data[points.count].theta)*(cmy/2);points.data[points.count].z=Math.cos(points.data[points.count].theta)*(cmx/2);container.append('<div id="'+id_stub+points.count+'" class="point" style="position:absolute;"><a>.</a></div>');}}
var sz_range=points.largest-points.smallest+1;var sz_n_range=opts.max_font_size-opts.min_font_size+1;for(var p in points.data){var sz=points.data[p].size;var sz_n=parseInt((sz/sz_range)*sz_n_range)+opts.min_font_size;if(!$('#'+points.data[p].id,container).hasClass('background')){$('#'+points.data[p].id,container).css('font-size',sz_n);}
points.data[p].cwidth=$('#'+points.data[p].id,container).width()/2;}
$('ul',container).remove();zoomed(opts.zoom);moved(cmx,cmy);drawing_interval=setInterval(draw,opts.drawing_interval);container.mousemove(function(evt){moved(evt.clientX,evt.clientY);});container.mousewheel(function(evt,delta){zoomed(delta);evt.preventDefault();return false;});};})(jQuery);