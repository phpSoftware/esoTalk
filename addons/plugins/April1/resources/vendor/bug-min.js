/*
 Bug.js - https://github.com/Auz/Bug
 Released under MIT-style license.
 Original Screen Bug http://screen-bug.googlecode.com/git/index.html
*/
"use strict";var BugDispatch={options:{minDelay:500,maxDelay:1e4,minBugs:2,maxBugs:20,minSpeed:1,maxSpeed:3,imageSprite:"fly-sprite.png",fly_width:13,fly_height:14,num_frames:5,monitorMouseMovement:false,eventDistanceToBug:40,minTimeBetweenMultipy:1e3,mouseOver:"random"},initialize:function(e){this.options=mergeOptions(this.options,e);if(this.options.minBugs>this.options.maxBugs){this.options.minBugs=this.options.maxBugs}this.modes=["die","multiply","fly","flyoff"];this.transform=null;this.transformFns={MozTransform:function(e){this.bug.style.MozTransform=e},WebkitTransform:function(e){this.bug.style.webkitTransform=e},OTransform:function(e){this.bug.style.OTransform=e},MsTransform:function(e){this.bug.style.msTransform=e},KhtmlTransform:function(e){this.bug.style.KhtmlTransform=e},W3Ctransform:function(e){this.bug.style.transform=e}};this.transforms={Moz:this.transformFns.MozTransform,webkit:this.transformFns.WebkitTransform,O:this.transformFns.OTransform,ms:this.transformFns.MsTransform,Khtml:this.transformFns.KhtmlTransform,w3c:this.transformFns.W3Ctransform};if("transform"in document.documentElement.style){this.transform=this.transforms.w3c}else{var t=["Moz","webkit","O","ms","Khtml"],n=0;for(n=0;n<t.length;n++){if(t[n]+"Transform"in document.documentElement.style){this.transform=this.transforms[t[n]];break}}}if(!this.transform)return;this.bugs=[];var r=this.options.mouseOver==="multiply"?this.options.minBugs:this.random(this.options.minBugs,this.options.maxBugs,true),n=0,i=this;for(n=0;n<r;n++){var e={imageSprite:this.options.imageSprite,fly_width:this.options.fly_width,fly_height:this.options.fly_height,num_frames:this.options.num_frames,wingsOpen:Math.random()>.5?true:false,walkSpeed:this.random(this.options.minSpeed,this.options.maxSpeed)},s=SpawnBug();s.initialize(this.transform,e);this.bugs.push(s)}for(n=0;n<r;n++){var o=this.random(this.options.minDelay,this.options.maxDelay,true),u=this.bugs[n];setTimeout(function(e){return function(){e.flyIn()}}(u),o);i.add_events_to_bug(u)}if(this.options.monitorMouseMovement){window.onmousemove=function(){i.check_if_mouse_close_to_bug()}}},add_events_to_bug:function(e){var t=this;if(e.bug){if(e.bug.addEventListener){e.bug.addEventListener("mouseover",function(n){t.on_bug(e)})}else if(e.bug.attachEvent){e.bug.attachEvent("onmouseover",function(n){t.on_bug(e)})}}},check_if_mouse_close_to_bug:function(e){e=e||window.event;if(!e){return}var t=0,n=0;if(e.client&&e.client.x){t=e.client.x;n=e.client.y}else if(e.clientX){t=e.clientX;n=e.clientY}else if(e.page&&e.page.x){t=e.page.x-(document.body.scrollLeft+document.documentElement.scrollLeft);n=e.page.y-(document.body.scrollTop+document.documentElement.scrollTop)}else if(e.pageX){t=e.pageX-(document.body.scrollLeft+document.documentElement.scrollLeft);n=e.pageY-(document.body.scrollTop+document.documentElement.scrollTop)}var r=this.bugs.length,i=0;for(i=0;i<r;i++){var s=this.bugs[i].getPos();if(s){if(Math.abs(s.top-n)+Math.abs(s.left-t)<this.options.eventDistanceToBug&&!this.bugs[i].flyperiodical){this.near_bug(this.bugs[i])}}}},near_bug:function(e){this.on_bug(e)},on_bug:function(e){if(!e.alive){return}var t=this.options.mouseOver;if(t==="random"){t=this.modes[this.random(0,this.modes.length-1,true)]}if(t==="fly"){e.stop();e.flyRand()}else if(t==="flyoff"){e.stop();e.flyOff()}else if(t==="die"){e.die()}else if(t==="multiply"){if(!this.multiplyDelay&&this.bugs.length<this.options.maxBugs){var n=SpawnBug(),r={imageSprite:this.options.imageSprite,fly_width:this.options.fly_width,fly_height:this.options.fly_height,num_frames:this.options.num_frames,wingsOpen:Math.random()>.5?true:false,walkSpeed:this.random(this.options.minSpeed,this.options.maxSpeed)},i=e.getPos,s=this;n.initialize(this.transform,r);n.drawBug(i.top,i.left);n.flyRand();e.flyRand();this.bugs.push(n);this.multiplyDelay=true;setTimeout(function(){s.add_events_to_bug(n);s.multiplyDelay=false},this.options.minTimeBetweenMultipy)}}},random:function(e,t,n){var r=e-.5+Math.random()*(t-e+1);if(r>t){r=t}else if(r<e){r=e}return n?Math.round(r):r}};var BugController=function(){this.initialize.apply(this,arguments)};BugController.prototype=BugDispatch;var Bug={options:{wingsOpen:false,walkSpeed:2,flySpeed:40,edge_resistance:50},initialize:function(e,t){this.options=mergeOptions(this.options,t);this.NEAR_TOP_EDGE=1;this.NEAR_BOTTOM_EDGE=2;this.NEAR_LEFT_EDGE=4;this.NEAR_RIGHT_EDGE=8;this.directions={};this.directions[this.NEAR_TOP_EDGE]=270;this.directions[this.NEAR_BOTTOM_EDGE]=90;this.directions[this.NEAR_LEFT_EDGE]=0;this.directions[this.NEAR_RIGHT_EDGE]=180;this.directions[this.NEAR_TOP_EDGE+this.NEAR_LEFT_EDGE]=315;this.directions[this.NEAR_TOP_EDGE+this.NEAR_RIGHT_EDGE]=225;this.directions[this.NEAR_BOTTOM_EDGE+this.NEAR_LEFT_EDGE]=45;this.directions[this.NEAR_BOTTOM_EDGE+this.NEAR_RIGHT_EDGE]=135;this.angle_deg=0;this.angle_rad=0;this.large_turn_angle_deg=0;this.near_edge=false;this.edge_test_counter=10;this.small_turn_counter=0;this.large_turn_counter=0;this.fly_counter=0;this.toggle_stationary_counter=Math.random()*50;this.stationary=false;this.bug=null;this.wingsOpen=this.options.wingsOpen;this.transform=e;this.walkIndex=0;this.flyIndex=0;this.alive=true;this.rad2deg_k=180/Math.PI;this.deg2rad_k=Math.PI/180;this.makeBug();this.angle_rad=this.deg2rad(this.angle_deg);this.angle_deg=this.random(0,360,true)},go:function(){if(this.transform){this.drawBug();var e=this;this.going=setInterval(function(){e.animate()},40)}},stop:function(){if(this.going){clearTimeout(this.going);this.going=null}if(this.flyperiodical){clearTimeout(this.flyperiodical);this.flyperiodical=null}},animate:function(){if(--this.toggle_stationary_counter<=0){this.toggleStationary()}if(this.stationary){return}if(--this.edge_test_counter<=0&&this.bug_near_window_edge()){this.angle_deg%=360;if(this.angle_deg<0)this.angle_deg+=360;if(Math.abs(this.directions[this.near_edge]-this.angle_deg)>15){var e=this.directions[this.near_edge]-this.angle_deg;var t=360-this.angle_deg+this.directions[this.near_edge];this.large_turn_angle_deg=Math.abs(e)<Math.abs(t)?e:t;this.edge_test_counter=10;this.large_turn_counter=100;this.small_turn_counter=30}}if(--this.large_turn_counter<=0){this.large_turn_angle_deg=this.random(1,150,true);this.next_large_turn()}if(--this.small_turn_counter<=0){this.angle_deg+=this.random(1,10);this.next_small_turn()}else{var n=this.random(1,5,true);if(this.large_turn_angle_deg>0&&n<0||this.large_turn_angle_deg<0&&n>0){n=-n}this.large_turn_angle_deg-=n;this.angle_deg+=n}this.angle_rad=this.deg2rad(this.angle_deg);var r=Math.cos(this.angle_rad)*this.options.walkSpeed;var i=-Math.sin(this.angle_rad)*this.options.walkSpeed;this.moveBug(r,i);this.walkFrame();this.transform("rotate("+(90-this.angle_deg)+"deg)")},makeBug:function(){if(!this.bug){var e=this.wingsOpen?"0":"-"+this.options.fly_height+"px",t=document.createElement("div");t["class"]="bug";t.style.background="transparent url("+this.options.imageSprite+") no-repeat 0 "+e;t.style.width=this.options.fly_width+"px";t.style.height=this.options.fly_height+"px";t.style.position="fixed";t.style.zIndex="9999999";this.bug=t;this.setPos()}},setPos:function(e,t){this.bug.top=e||this.random(this.options.edge_resistance,document.documentElement.clientHeight-this.options.edge_resistance);this.bug.left=t||this.random(this.options.edge_resistance,document.documentElement.clientWidth-this.options.edge_resistance);this.bug.style.top=this.bug.top+"px";this.bug.style.left=this.bug.left+"px"},drawBug:function(e,t){if(!this.bug){this.makeBug()}if(e&&t){this.setPos(e,t)}else{this.setPos(this.bug.top,this.bug.left)}if(!this.inserted){this.inserted=true;document.body.appendChild(this.bug)}},toggleStationary:function(){this.stationary=!this.stationary;this.next_stationary();var e=this.options.wingsOpen?"0":"-"+this.options.fly_height+"px";if(this.stationary){this.bug.style.backgroundPosition="0 "+e}else{this.bug.style.backgroundPosition="-"+this.options.fly_width+"px "+e}},walkFrame:function(){var e=-1*this.walkIndex*this.options.fly_width+"px",t=this.options.wingsOpen?"0":"-"+this.options.fly_height+"px";this.bug.style.backgroundPosition=e+" "+t;this.walkIndex++;if(this.walkIndex>=this.options.num_frames)this.walkIndex=0},moveBug:function(e,t){this.bug.style.top=(this.bug.top+=t)+"px";this.bug.style.left=(this.bug.left+=e)+"px"},fly:function(e){var t=parseInt(this.bug.style.top,10),n=parseInt(this.bug.style.left,10),r=n-e.left,i=t-e.top,s=Math.atan(i/r);if(Math.abs(r)+Math.abs(i)<50){this.bug.style.backgroundPosition=-2*this.options.fly_width+"px -"+2*this.options.fly_height+"px"}if(Math.abs(r)+Math.abs(i)<30){this.bug.style.backgroundPosition=-1*this.options.fly_width+"px -"+2*this.options.fly_height+"px"}if(Math.abs(r)+Math.abs(i)<10){this.bug.style.backgroundPosition="0 0";this.stop();this.go();return}var o=Math.cos(s)*this.options.flySpeed,u=Math.sin(s)*this.options.flySpeed;if(n>e.left&&o>0||n>e.left&&o<0){o=-1*o;if(Math.abs(r)<Math.abs(o)){o=o/4}}if(t<e.top&&u<0||t>e.top&&u>0){u=-1*u;if(Math.abs(i)<Math.abs(u)){u=u/4}}this.bug.style.top=t+u+"px";this.bug.style.left=n+o+"px"},flyRand:function(){this.stop();var e={};e.top=this.random(this.options.edge_resistance,document.documentElement.clientHeight-this.options.edge_resistance);e.left=this.random(this.options.edge_resistance,document.documentElement.clientWidth-this.options.edge_resistance);this.startFlying(e)},startFlying:function(e){this.bug.left=e.left;this.bug.top=e.top;var t=parseInt(this.bug.style.top,10),n=parseInt(this.bug.style.left,10),r=e.left-n,i=e.top-t;this.angle_rad=Math.atan(i/r);this.angle_deg=this.rad2deg(this.angle_rad);if(r>0){this.angle_deg=90+this.angle_deg}else{this.angle_deg=270+this.angle_deg}this.angle_rad=this.deg2rad(this.angle_deg);this.transform("rotate("+(90-this.angle_deg)+"deg)");var s=this;this.flyperiodical=setInterval(function(){s.fly(e)},10)},flyIn:function(){if(!this.bug){this.makeBug()}this.stop();var e=Math.round(Math.random()*4-.5),t=document,n=t.documentElement,r=t.getElementsByTagName("body")[0],i=window.innerWidth||n.clientWidth||r.clientWidth,s=window.innerHeight||n.clientHeight||r.clientHeight;if(e>3)e=3;if(e<0)e=0;var o={},u;if(e===0){o.top=-20;o.left=Math.random()*i}else if(e===1){o.top=Math.random()*s;o.left=i+50}else if(e===2){o.top=s+50;o.left=Math.random()*i}else{o.top=Math.random()*s;o.left=-40}this.bug.style.backgroundPosition=-3*this.options.fly_width+"px -"+2*this.options.fly_height+"px";this.bug.top=o.top;this.bug.left=o.left;this.drawBug();var a={};a.top=this.random(this.options.edge_resistance,document.documentElement.clientHeight-this.options.edge_resistance);a.left=this.random(this.options.edge_resistance,document.documentElement.clientWidth-this.options.edge_resistance);this.startFlying(a)},flyOff:function(){this.stop();var e=this.random(0,3),t={},n=document,r=n.documentElement,i=n.getElementsByTagName("body")[0],s=window.innerWidth||r.clientWidth||i.clientWidth,o=window.innerHeight||r.clientHeight||i.clientHeight;if(e===0){t.top=-200;t.left=Math.random()*s}else if(e===1){t.top=Math.random()*o;t.left=s+200}else if(e===2){t.top=o+200;t.left=Math.random()*s}else{t.top=Math.random()*o;t.left=-200}this.startFlying(t)},die:function(){this.stop();var e=this.random(0,2);this.bug.style.backgroundPosition="-"+e*2*this.options.fly_width+"px -"+3*this.options.fly_height+"px";this.alive=false;this.drop(e)},drop:function(e){var t=this.getPos().top,n=document,r=n.documentElement,i=n.getElementsByTagName("body")[0],s=window.innerHeight||r.clientHeight||i.clientHeight,s=s-this.options.fly_height,o=this.random(0,20,true),u=Date.now(),a=this;this.dropTimer=setInterval(function(){a.dropping(u,t,s,o,e)},20)},dropping:function(e,t,n,r,i){var s=Date.now(),o=s-e,u=.002*o*o,a=t+u;if(a>=n){a=n;clearTimeout(this.dropTimer);this.angle_deg=0;this.angle_rad=this.deg2rad(this.angle_deg);this.transform("rotate("+(90-this.angle_deg)+"deg)");this.bug.style.top=null;this.bug.style.bottom="-1px";this.twitch(i);return}this.angle_deg=(this.angle_deg+r)%360;this.angle_rad=this.deg2rad(this.angle_deg);this.transform("rotate("+(90-this.angle_deg)+"deg)");this.bug.style.top=a+"px"},twitch:function(e,t){if(!t)t=0;var n=this;if(e===0||e===1){setTimeout(function(){n.bug.style.backgroundPosition="-"+(e*2+t%2)*n.options.fly_width+"px -"+3*n.options.fly_height+"px";n.twitch(e,++t)},this.random(100,1e3))}},rad2deg:function(e){return e*this.rad2deg_k},deg2rad:function(e){return e*this.deg2rad_k},random:function(e,t,n){var r=Math.round(e-.5+Math.random()*(t-e+1));if(n)return Math.random()>.5?r:-r;return r},next_small_turn:function(){this.small_turn_counter=Math.round(Math.random()*10)},next_large_turn:function(){this.large_turn_counter=Math.round(Math.random()*40)},next_stationary:function(){this.toggle_stationary_counter=this.random(50,300)},bug_near_window_edge:function(){this.near_edge=0;if(this.bug.top<this.options.edge_resistance)this.near_edge|=this.NEAR_TOP_EDGE;else if(this.bug.top>document.documentElement.clientHeight-this.options.edge_resistance)this.near_edge|=this.NEAR_BOTTOM_EDGE;if(this.bug.left<this.options.edge_resistance)this.near_edge|=this.NEAR_LEFT_EDGE;else if(this.bug.left>document.documentElement.clientWidth-this.options.edge_resistance)this.near_edge|=this.NEAR_RIGHT_EDGE;return this.near_edge},getPos:function(){if(this.inserted&&this.bug&&this.bug.style){return{top:parseInt(this.bug.style.top,10),left:parseInt(this.bug.style.left,10)}}return null}};var SpawnBug=function(){var e={},t;for(t in Bug){if(Bug.hasOwnProperty(t)){e[t]=Bug[t]}}return e};var mergeOptions=function(e,t,n){if(typeof n=="undefined"){n=true}var r=n?cloneOf(e):e;for(var i in t){if(t.hasOwnProperty(i)){r[i]=t[i]}}return r};var cloneOf=function(e){if(e==null||typeof e!="object")return e;var t=e.constructor();for(var n in e){if(e.hasOwnProperty(n)){t[n]=cloneOf(e[n])}}return t};window.requestAnimFrame=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(e,t){window.setTimeout(e,1e3/60)}}();