<?php
ob_start("ob_gzhandler");
header("Content-type: text/javascript; charset=utf-8");
$offset = 6000 * 60 ;
$ExpStr = "Expires:" .
gmdate("D, d M Y H:i:s",time() + $offset) . "GMT";
header($ExpStr);

?>
//endcolor for close element flashing
closeEndcolor = '#377814';
//endcolor for delete element flashing
deleteEndcolor = '#c62424';
//various ajax functions
function change(script,element) {
   var ajax = new Ajax.Updater({success: element},script,{method: 'get',evalScripts:true});
}
function changeshow(script,element,theindicator) {

   var ajax = new Ajax.Updater({success: element},script,{method: 'get',evalScripts:true,onCreate:startWait(theindicator)});

}
function changePost(script,element,pbody) {
   var ajax = new Ajax.Updater({success: element},script,{method:'post', postBody:pbody,evalScripts:true});
}
function startWait(indic)
{

	$(indic).style.display = 'block';
}
function stopWait(indic)
{
	$(indic).style.display = 'none';
}

function deleteElement(theElement,theUrl)
{
	new Ajax.Request(theUrl, {
		  method: 'get',
		  onSuccess:function(payload) {
		    if (payload.responseText == "ok")
		    	{
		    		removeRow(theElement,deleteEndcolor);
		    		result = true;
		    	}
		   }
		});
		try
		{
		systemMsg("deleted");
		}
		catch(e){}
}


function closeElement(theElement,theUrl)
{

new Ajax.Request(theUrl, {
	  method: 'get',
	  onSuccess: function(payload) {
	    if (payload.responseText == "ok")
	    	{
				removeRow(theElement,closeEndcolor);
	    	}
	   }
	});

		try
		{
		systemMsg("closed");

		}
		catch(e){}
}
function recolorRows(therow)
{
    row = therow.options.rowid;
    var theTable = $(row).parentNode;
    tbodys = $$('#'+theTable.id +' tbody:not([id='+row+'])');
    bodies = new Array();

    tbodys.each(function(s)
    {
        if(s.style.display != 'none')
        {
            bodies.push(s);
        }
    }
    );

    for(i=0;i<bodies.length;i++)
    {
        if(i % 2 == 0)
        {
            $(bodies[i].id).className = 'color-a';
        }
        else
        {

            $(bodies[i].id).className = 'color-b';
        }
    }
}

function removeRow(row,color)
{

new Effect.Highlight(row,{duration:1.5,startcolor:'#FFFFFF',endcolor:color});
new Effect.Fade(row,{duration:1.5,
rowid:row,
afterFinish:recolorRows});
}
function make_inputs(num){
	url = 'manageajax.php?action=makeinputs&num='+num;
	change(url,'inputs');
}

function show_addtask(id){
	Effect.BlindDown(id);
}
function blindtoggle(id){
	new Effect.toggle(id,'blind',{duration:0.5});
}

function fadeToggle(id){
	new Effect.toggle(id,'appear');
}


function toggleBlock(id){
	var state = $(id).style.display;
	if(state == "none")
	{
	setCookie(id,'1','30','/','','');
	$(id).style.display = "block";
	$(id + '_toggle').className = 'win_block';
	}
	else if(state == "block" || state == "")
	{
	setCookie(id,'0','30','/','','');
	$(id).style.display = "none";
	$(id + '_toggle').className = 'win_none';
	}
}

function switchClass(id,class1,class2)
{
try{$($('selectedid').value).className = class2;} catch(e){}
	try{$('selectedid').value = id;
	$($('selectedid').value).className = class1;}
	catch (e){}

}
function toggleClass(id,class1,class2)
{
	if($(id).hasClassName(class1))
	{
		$(id).className = class2;
	}
	else
	{
		$(id).className = class1;
	}

}

function toggleAccordeon(accord,theLink)
{
	$$("#"+accord+" tbody span[class='acc-toggle-active']").each(
		function(theA)
		{
			if(theA != theLink)
			{
			theA.className = 'acc-toggle';
			}
		}
	);


	if(theLink.hasClassName('acc-toggle'))
	{
		theLink.className = 'acc-toggle-active';
	}
	else
	{
		theLink.className = 'acc-toggle';
	}
}

function changeElements(element,classname){
	var loop = $$(element);
	for(var i=0; i<loop.length; i++){
	  loop[i].className = classname;
	}
}

function makeTimer(funct,duration)
{
	window.setTimeout(funct,duration);
}

function confirmit(text,url)
{
	check = confirm(text);
	url = decodeURI(url);
	if(check == true)
	{
	window.location = url;
	}

}

function confirmfunction(text,toCall)
{
	check = confirm(text);
	if(check == true)
	{
		eval(toCall);
	}
}

function selectFolder(folderId)
{
    var theOptions = $('folderparent').options;
    for(i=0;i<theOptions.length;i++)
    {
        if(theOptions[i].value == folderId)
        {
            theOptions[i].selected = 'selected';
        }
    }
   var theOptions = $('upfolder').options;
    for(i=0;i<theOptions.length;i++)
    {
        if(theOptions[i].value == folderId)
        {
            theOptions[i].selected = 'selected';
        }
    }
    upfolder
}

function setCookie( name, value, expires, path, domain, secure )
{
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires )
	{
	expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );

	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function getnow(field)
{
	var currenttime = new Date();
	var hours = currenttime.getHours();
	var minutes = currenttime.getMinutes();
		if (hours < 10)
		{
			hours = "0" + hours;
		}
		if (minutes < 10)
		{
			minutes = "0" + minutes;
		}
	var clocklocation = $(field);
	clocklocation.value = hours + ":" + minutes;
}

/* Clock */
function calctime()
{
var currenttime = new Date();
var hours = currenttime.getHours();
var minutes = currenttime.getMinutes();
var seconds = currenttime.getSeconds();
	if (hours < 10)
	{
		hours = "0" + hours;
	}
	if (minutes < 10)
	{
		minutes = "0" + minutes;
	}
	if (seconds < 10)
	{
		seconds = "0" + seconds;
	}
var clocklocation = $("digitalclock");
clocklocation.innerHTML = hours + ":" + minutes + ":" + seconds;
setTimeout("calctime()", 1000);
}

function systemMsg(ele)
{
	new Effect.Appear(ele, { duration: 2.0 })
	makeTimer("new Effect.Fade('"+ele+"', { duration: 2.0 })",4000);
}

//add search provider
function addEngine(url)
{
	window.external.AddSearchProvider(url);
}


function sortit()
{

}
function sortBlock(theblock,sortmode)
{
    var tbodies = $$("#"+theblock+" tbody");
    var bodyIds = new Array();
    theParent = $(theblock).parentNode;

    for(i=0;i<tbodies.length;i++)
    {
        var tdtitle = tbodies[i].getAttribute("rel");
        titleArr = tdtitle.split(",");

        tbodies[i].setAttribute("theid",Number(titleArr[0]));
        tbodies[i].setAttribute("title",titleArr[1]);
        tbodies[i].setAttribute("daysleft",titleArr[2]);
        tbodies[i].setAttribute("project",titleArr[3]);
        tbodies[i].setAttribute("theuser",titleArr[4]);
        tbodies[i].setAttribute("done",titleArr[5]);
        if(tbodies[i].getAttribute("sortorder") == "asc")
        {
            tbodies[i].setAttribute("sortorder","desc");
        }
        else
        {
            tbodies[i].setAttribute("sortorder","asc");
        }


        $(theblock).removeChild(tbodies[i]);
        //$('jslog').innerHTML += tbodies[i].id + "<br />";
    }

    if(sortmode == "daysleft")
    {
        tbodies.sort(daysort);
    }
    else if(sortmode == "done")
    {
        tbodies.sort(done);
    }
    else if(sortmode == "project")
    {
        tbodies.sort(sortByProject);
    }
    else if(sortmode == "byuser")
    {
        tbodies.sort(sortByUser);
    }
    else
    {
        tbodies.sort(sortByTitle);
    }

    //$('jslog').innerHTML += " <br /> <br />sorted:<br/>";
    for(i=0;i<tbodies.length;i++)
    {
        var theEl = $(theblock).appendChild(tbodies[i]);
        if(i % 2 == 0)
        {
            theEl.setAttribute("class","color-a");
        }
        else
        {
            theEl.setAttribute("class","color-b");
        }
        $(theblock+"toggle"+tbodies[i].getAttribute("theid")).setAttribute("onclick","javascript:accord_tasks.activate($$('#'+theParent.id+' .accordion_toggle')["+i+"]);toggleAccordeon(theParent.id,this);");

    //$('jslog').innerHTML += tbodies[i].id + "<br />";
    }
}

function done(a,b){
    var x = a.getAttribute("done");
    var y = b.getAttribute("done");
    var sortorder = b.getAttribute("sortorder");

    //desc
    if(sortorder == "asc")
    {
        return y - x
    }
    else
    {
    //asc
        return x - y
    }
}

function daysort(a,b){
    var x = a.getAttribute("daysleft");
    var y = b.getAttribute("daysleft");
    var sortorder = b.getAttribute("sortorder");

    //desc
    if(sortorder == "asc")
    {
        return y - x
    }
    else
    {
    //asc
        return x - y
    }
}

function sortByTitle(a, b) {
    var x = a.title.toLowerCase();
    var y = b.title.toLowerCase();
    var sortorder = b.getAttribute("sortorder");

    if(sortorder == "asc")
    {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else
    {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}

function sortByProject(a, b) {
    var x = a.getAttribute("project");
    var y = b.getAttribute("project");
    var sortorder = b.getAttribute("sortorder");

    if(sortorder == "asc")
    {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else
    {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}

function sortByUser(a, b) {
    var x = a.getAttribute("theuser");
    var y = b.getAttribute("theuser");
    var sortorder = b.getAttribute("sortorder");

    if(sortorder == "asc")
    {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else
    {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}

function sortByDays(a, b) {
    var x = a.getAttribute("daysleft");
    var y = b.getAttribute("daysleft");

    var sortorder = b.getAttribute("sortorder");

    if(sortorder == "asc")
    {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else
    {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}




// accordion.js v2.0
//
// Copyright (c) 2007 stickmanlabs
// Author: Kevin P Miller | http://www.stickmanlabs.com
//
// Accordion is freely distributable under the terms of an MIT-style license.
//
// I don't care what you think about the file size...
//   Be a pro:
//	    http://www.thinkvitamin.com/features/webapps/serving-javascript-fast
//      http://rakaz.nl/item/make_your_pages_load_faster_by_combining_and_compressing_javascript_and_css_files
//

/*-----------------------------------------------------------------------------------------------*/

if (typeof Effect == 'undefined')
	throw("accordion.js requires including script.aculo.us' effects.js library!");

var accordion = Class.create();
accordion.prototype = {

	//
	//  Setup the Variables
	//
	showAccordion : null,
	currentAccordion : null,
	duration : null,
	effects : [],
	animating : false,

	//
	//  Initialize the accordions
	//
	initialize: function(container, options) {
	  if (!$(container)) {
	    throw(container+" doesn't exist!");
	    return false;
	  }

		this.options = Object.extend({
			resizeSpeed : 8,
			classNames : {
				toggle : 'accordion_toggle',
				toggleActive : 'accordion_toggle_active',
				content : 'accordion_content'
			},
			defaultSize : {
				height : null,
				width : null
			},
			direction : 'vertical',
			onEvent : 'click'
		}, options || {});

		//this.duration = ((11-this.options.resizeSpeed)*0.15);
		this.duration = 0.3;

		var accordions = $$('#'+container+' .'+this.options.classNames.toggle);
		accordions.each(function(accordion) {
			Event.observe(accordion, this.options.onEvent, this.activate.bind(this, accordion), false);
			if (this.options.onEvent == 'click') {
			  accordion.onclick = function() {return false;};
			}

			if (this.options.direction == 'horizontal') {
				var options = {width: '0px', display:'none'};
			} else {
				var options = {height: '0px', display:'none'};
			}
//			options.merge({display: 'none'});

			this.currentAccordion = $(accordion.next(0)).setStyle(options);
		}.bind(this));
	},

	//
	//  Activate an accordion
	//
	activate : function(accordion) {
		if (this.animating) {
			return false;
		}

		this.effects = [];

		this.currentAccordion = $(accordion.next(0));
		this.currentAccordion.setStyle({
			display: 'block'
		});

		this.currentAccordion.previous(0).addClassName(this.options.classNames.toggleActive);

		if (this.options.direction == 'horizontal') {
			this.scaling = $H({
				scaleX: true,
				scaleY: false
			});
		} else {
			this.scaling = $H({
				scaleX: false,
				scaleY: true
			});
		}

		if (this.currentAccordion == this.showAccordion) {
		  this.deactivate();
		} else {
		  this._handleAccordion();
		}
	},
	//
	// Deactivate an active accordion
	//
	deactivate : function() {
		var options = $H({
		  duration: this.duration,
			scaleContent: false,
			transition: Effect.Transitions.sinoidal,
			queue: {
				position: 'end',
				scope: 'accordionAnimation'
			},
			scaleMode: {
				originalHeight: this.options.defaultSize.height ? this.options.defaultSize.height : this.currentAccordion.scrollHeight,
				originalWidth: this.options.defaultSize.width ? this.options.defaultSize.width : this.currentAccordion.scrollWidth
			},
			afterFinish: function() {
				this.showAccordion.setStyle({
          height: 'auto',
					display: 'none'
				});
				this.showAccordion = null;
				this.animating = false;
			}.bind(this)
		});
//    options.merge(this.scaling);

    this.showAccordion.previous(0).removeClassName(this.options.classNames.toggleActive);

		new Effect.Scale(this.showAccordion, 0, options.update(this.scaling).toObject());
	},

  //
  // Handle the open/close actions of the accordion
  //
	_handleAccordion : function() {
		var options = $H({
			sync: true,
			scaleFrom: 0,
			scaleContent: false,
			transition: Effect.Transitions.sinoidal,
			scaleMode: {
				originalHeight: this.options.defaultSize.height ? this.options.defaultSize.height : this.currentAccordion.scrollHeight,
				originalWidth: this.options.defaultSize.width ? this.options.defaultSize.width : this.currentAccordion.scrollWidth
			}
		});
		options.merge(this.scaling);

		this.effects.push(
			new Effect.Scale(this.currentAccordion, 100, options.update(this.scaling).toObject())
		);

		if (this.showAccordion) {
			this.showAccordion.previous(0).removeClassName(this.options.classNames.toggleActive);

			options = $H({
				sync: true,
				scaleContent: false,
				transition: Effect.Transitions.sinoidal
			});
			options.merge(this.scaling);

			this.effects.push(
				new Effect.Scale(this.showAccordion, 0, options.update(this.scaling).toObject())
			);
		}

    new Effect.Parallel(this.effects, {
			duration: this.duration,
			queue: {
				position: 'end',
				scope: 'accordionAnimation'
			},
			beforeStart: function() {
				this.animating = true;
			}.bind(this),
			afterFinish: function() {
				if (this.showAccordion) {
					this.showAccordion.setStyle({
						display: 'none'
					});
				}
				$(this.currentAccordion).setStyle({
				  height: 'auto'
				});
				this.showAccordion = this.currentAccordion;
				this.animating = false;
			}.bind(this)
		});
	}
}

if(typeof (Control)=="undefined"){Control={};}Control.Modal=Class.create();Object.extend(Control.Modal,{loaded:false,loading:false,loadingTimeout:false,overlay:false,container:false,current:false,ie:false,effects:{containerFade:false,containerAppear:false,overlayFade:false,overlayAppear:false},targetRegexp:/#(.+)$/,imgRegexp:/\.(jpe?g|gif|png|tiff?)$/,overlayStyles:{position:"fixed",top:0,left:0,width:"100%",height:"100%",zIndex:9998},overlayIEStyles:{position:"absolute",top:0,left:0,zIndex:9998},disableHoverClose:false,load:function(){if(!Control.Modal.loaded){Control.Modal.loaded=true;Control.Modal.ie=!(typeof document.body.style.maxHeight!="undefined");Control.Modal.overlay=$(document.createElement("div"));Control.Modal.overlay.id="modal_overlay";Object.extend(Control.Modal.overlay.style,Control.Modal["overlay"+(Control.Modal.ie?"IE":"")+"Styles"]);Control.Modal.overlay.hide();Control.Modal.container=$(document.createElement("div"));Control.Modal.container.id="modal_container";Control.Modal.container.hide();Control.Modal.loading=$(document.createElement("div"));Control.Modal.loading.id="modal_loading";Control.Modal.loading.hide();var _1=document.getElementsByTagName("body")[0];_1.appendChild(Control.Modal.overlay);_1.appendChild(Control.Modal.container);_1.appendChild(Control.Modal.loading);Control.Modal.container.observe("mouseout",function(_2){if(!Control.Modal.disableHoverClose&&Control.Modal.current&&Control.Modal.current.options.hover&&!Position.within(Control.Modal.container,Event.pointerX(_2),Event.pointerY(_2))){Control.Modal.close();}});}},open:function(_3,_4){_4=_4||{};if(!_4.contents){_4.contents=_3;}var _5=new Control.Modal(false,_4);_5.open();return _5;},close:function(_6){if(typeof (_6)!="boolean"){_6=false;}if(Control.Modal.current){Control.Modal.current.close(_6);}},attachEvents:function(){Event.observe(window,"load",Control.Modal.load);Event.observe(window,"unload",Event.unloadCache,false);},center:function(_7){if(!_7._absolutized){_7.setStyle({position:"absolute"});_7._absolutized=true;}var _8=_7.getDimensions();Position.prepare();var _9=(Position.deltaX+Math.floor((Control.Modal.getWindowWidth()-_8.width)/2));var _a=(Position.deltaY+((Control.Modal.getWindowHeight()>_8.height)?Math.floor((Control.Modal.getWindowHeight()-_8.height)/2):0));_7.setStyle({top:((_8.height<=Control.Modal.getDocumentHeight())?((_a!=null&&_a>0)?_a:"0")+"px":0),left:((_8.width<=Control.Modal.getDocumentWidth())?((_9!=null&&_9>0)?_9:"0")+"px":0)});},getWindowWidth:function(){return (self.innerWidth||document.documentElement.clientWidth||document.body.clientWidth||0);},getWindowHeight:function(){return (self.innerHeight||document.documentElement.clientHeight||document.body.clientHeight||0);},getDocumentWidth:function(){return Math.min(document.body.scrollWidth,Control.Modal.getWindowWidth());},getDocumentHeight:function(){return Math.max(document.body.scrollHeight,Control.Modal.getWindowHeight());},onKeyDown:function(_b){if(_b.keyCode==Event.KEY_ESC){Control.Modal.close();}}});Object.extend(Control.Modal.prototype,{mode:"",html:false,href:"",element:false,src:false,imageLoaded:false,ajaxRequest:false,initialize:function(_c,_d){this.element=$(_c);this.options={beforeOpen:Prototype.emptyFunction,afterOpen:Prototype.emptyFunction,beforeClose:Prototype.emptyFunction,afterClose:Prototype.emptyFunction,onSuccess:Prototype.emptyFunction,onFailure:Prototype.emptyFunction,onException:Prototype.emptyFunction,beforeImageLoad:Prototype.emptyFunction,afterImageLoad:Prototype.emptyFunction,autoOpenIfLinked:true,contents:false,loading:false,fade:false,fadeDuration:0.75,image:false,imageCloseOnClick:true,hover:false,iframe:false,iframeTemplate:new Template("<iframe src=\"#{href}\" width=\"100%\" height=\"100%\" frameborder=\"0\" id=\"#{id}\"></iframe>"),evalScripts:true,requestOptions:{},overlayDisplay:true,overlayClassName:"",overlayCloseOnClick:true,containerClassName:"",opacity:0.3,zIndex:1000,width:null,height:null,offsetLeft:0,offsetTop:0,position:"absolute"};Object.extend(this.options,_d||{});var _e=false;var _f=false;if(this.element){_e=Control.Modal.targetRegexp.exec(this.element.href);_f=Control.Modal.imgRegexp.exec(this.element.href);}if(this.options.position=="mouse"){this.options.hover=true;}if(this.options.contents){this.mode="contents";}else{if(this.options.image||_f){this.mode="image";this.src=this.element.href;}else{if(_e){this.mode="named";var x=$(_e[1]);this.html=x.innerHTML;x.remove();this.href=_e[1];}else{this.mode=(this.options.iframe)?"iframe":"ajax";this.href=this.element.href;}}}if(this.element){if(this.options.hover){this.element.observe("mouseover",this.open.bind(this));this.element.observe("mouseout",function(_11){if(!Position.within(Control.Modal.container,Event.pointerX(_11),Event.pointerY(_11))){this.close();}}.bindAsEventListener(this));}else{this.element.onclick=function(_12){this.open();Event.stop(_12);return false;}.bindAsEventListener(this);}}var _13=Control.Modal.targetRegexp.exec(window.location);this.position=function(_14){if(this.options.position=="absolute"){Control.Modal.center(Control.Modal.container);}else{var xy=(_14&&this.options.position=="mouse"?[Event.pointerX(_14),Event.pointerY(_14)]:Position.cumulativeOffset(this.element));Control.Modal.container.setStyle({position:"absolute",top:xy[1]+(typeof (this.options.offsetTop)=="function"?this.options.offsetTop():this.options.offsetTop)+"px",left:xy[0]+(typeof (this.options.offsetLeft)=="function"?this.options.offsetLeft():this.options.offsetLeft)+"px"});}if(Control.Modal.ie){Control.Modal.overlay.setStyle({height:Control.Modal.getDocumentHeight()+"px",width:Control.Modal.getDocumentWidth()+"px"});}}.bind(this);if(this.mode=="named"&&this.options.autoOpenIfLinked&&_13&&_13[1]&&_13[1]==this.href){this.open();}},showLoadingIndicator:function(){if(this.options.loading){Control.Modal.loadingTimeout=window.setTimeout(function(){var _16=$("modal_image");if(_16){_16.hide();}Control.Modal.loading.style.zIndex=this.options.zIndex+1;Control.Modal.loading.update("<img id=\"modal_loading\" src=\""+this.options.loading+"\"/>");Control.Modal.loading.show();Control.Modal.center(Control.Modal.loading);}.bind(this),250);}},hideLoadingIndicator:function(){if(this.options.loading){if(Control.Modal.loadingTimeout){window.clearTimeout(Control.Modal.loadingTimeout);}var _17=$("modal_image");if(_17){_17.show();}Control.Modal.loading.hide();}},open:function(_18){if(!_18&&this.notify("beforeOpen")===false){return;}if(!Control.Modal.loaded){Control.Modal.load();}Control.Modal.close();if(!this.options.hover){Event.observe($(document.getElementsByTagName("body")[0]),"keydown",Control.Modal.onKeyDown);}Control.Modal.current=this;if(!this.options.hover){Control.Modal.overlay.setStyle({zIndex:this.options.zIndex,opacity:this.options.opacity});}Control.Modal.container.setStyle({zIndex:this.options.zIndex+1,width:(this.options.width?(typeof (this.options.width)=="function"?this.options.width():this.options.width)+"px":null),height:(this.options.height?(typeof (this.options.height)=="function"?this.options.height():this.options.height)+"px":null)});if(Control.Modal.ie&&!this.options.hover){$A(document.getElementsByTagName("select")).each(function(_19){_19.style.visibility="hidden";});}Control.Modal.overlay.addClassName(this.options.overlayClassName);Control.Modal.container.addClassName(this.options.containerClassName);switch(this.mode){case "image":this.imageLoaded=false;this.notify("beforeImageLoad");this.showLoadingIndicator();var img=document.createElement("img");img.onload=function(img){this.hideLoadingIndicator();this.update([img]);if(this.options.imageCloseOnClick){$(img).observe("click",Control.Modal.close);}this.position();this.notify("afterImageLoad");img.onload=null;}.bind(this,img);img.src=this.src;img.id="modal_image";break;case "ajax":this.notify("beforeLoad");var _1c={method:"post",onSuccess:function(_1d){this.hideLoadingIndicator();this.update(_1d.responseText);this.notify("onSuccess",_1d);this.ajaxRequest=false;}.bind(this),onFailure:function(){this.notify("onFailure");}.bind(this),onException:function(){this.notify("onException");}.bind(this)};Object.extend(_1c,this.options.requestOptions);this.showLoadingIndicator();this.ajaxRequest=new Ajax.Request(this.href,_1c);break;case "iframe":this.update(this.options.iframeTemplate.evaluate({href:this.href,id:"modal_iframe"}));break;case "contents":this.update((typeof (this.options.contents)=="function"?this.options.contents():this.options.contents));break;case "named":this.update(this.html);break;}if(!this.options.hover){if(this.options.overlayCloseOnClick&&this.options.overlayDisplay){Control.Modal.overlay.observe("click",Control.Modal.close);}if(this.options.overlayDisplay){if(this.options.fade){if(Control.Modal.effects.overlayFade){Control.Modal.effects.overlayFade.cancel();}Control.Modal.effects.overlayAppear=new Effect.Appear(Control.Modal.overlay,{queue:{position:"front",scope:"Control.Modal"},to:this.options.opacity,duration:this.options.fadeDuration/2});}else{Control.Modal.overlay.show();}}}if(this.options.position=="mouse"){this.mouseHoverListener=this.position.bindAsEventListener(this);this.element.observe("mousemove",this.mouseHoverListener);}this.notify("afterOpen");},update:function(_1e){if(typeof (_1e)=="string"){Control.Modal.container.update(_1e);}else{Control.Modal.container.update("");(_1e.each)?_1e.each(function(_1f){Control.Modal.container.appendChild(_1f);}):Control.Modal.container.appendChild(node);}if(this.options.fade){if(Control.Modal.effects.containerFade){Control.Modal.effects.containerFade.cancel();}Control.Modal.effects.containerAppear=new Effect.Appear(Control.Modal.container,{queue:{position:"end",scope:"Control.Modal"},to:1,duration:this.options.fadeDuration/2});}else{Control.Modal.container.show();}this.position();Event.observe(window,"resize",this.position,false);Event.observe(window,"scroll",this.position,false);},close:function(_20){if(!_20&&this.notify("beforeClose")===false){return;}if(this.ajaxRequest){this.ajaxRequest.transport.abort();}this.hideLoadingIndicator();if(this.mode=="image"){var _21=$("modal_image");if(this.options.imageCloseOnClick&&_21){_21.stopObserving("click",Control.Modal.close);}}if(Control.Modal.ie&&!this.options.hover){$A(document.getElementsByTagName("select")).each(function(_22){_22.style.visibility="visible";});}if(!this.options.hover){Event.stopObserving(window,"keyup",Control.Modal.onKeyDown);}Control.Modal.current=false;Event.stopObserving(window,"resize",this.position,false);Event.stopObserving(window,"scroll",this.position,false);if(!this.options.hover){if(this.options.overlayCloseOnClick&&this.options.overlayDisplay){Control.Modal.overlay.stopObserving("click",Control.Modal.close);}if(this.options.overlayDisplay){if(this.options.fade){if(Control.Modal.effects.overlayAppear){Control.Modal.effects.overlayAppear.cancel();}Control.Modal.effects.overlayFade=new Effect.Fade(Control.Modal.overlay,{queue:{position:"end",scope:"Control.Modal"},from:this.options.opacity,to:0,duration:this.options.fadeDuration/2});}else{Control.Modal.overlay.hide();}}}if(this.options.fade){if(Control.Modal.effects.containerAppear){Control.Modal.effects.containerAppear.cancel();}Control.Modal.effects.containerFade=new Effect.Fade(Control.Modal.container,{queue:{position:"front",scope:"Control.Modal"},from:1,to:0,duration:this.options.fadeDuration/2,afterFinish:function(){Control.Modal.container.update("");this.resetClassNameAndStyles();}.bind(this)});}else{Control.Modal.container.hide();Control.Modal.container.update("");this.resetClassNameAndStyles();}if(this.options.position=="mouse"){this.element.stopObserving("mousemove",this.mouseHoverListener);}this.notify("afterClose");},resetClassNameAndStyles:function(){Control.Modal.overlay.removeClassName(this.options.overlayClassName);Control.Modal.container.removeClassName(this.options.containerClassName);Control.Modal.container.setStyle({height:null,width:null,top:null,left:null});},notify:function(_23){try{if(this.options[_23]){return [this.options[_23].apply(this.options[_23],$A(arguments).slice(1))];}}catch(e){if(e!=$break){throw e;}else{return false;}}}});if(typeof (Object.Event)!="undefined"){Object.Event.extend(Control.Modal);}Control.Modal.attachEvents();

