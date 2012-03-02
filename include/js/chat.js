function periodicUpdater(script,element) {
	var win = $('textwin');
    var ajax = new Ajax.PeriodicalUpdater(element,script,{method:'get',evalScripts:true,frequency:2	,onSuccess:function(){
	win.scrollTop = 100000;}
	});
	win.scrollTop = 100000;
}

function startChat(){
	var uid = $('userto_id').value;
	periodicUpdater('managechat.php?action=pull&uid='+uid,'textwin');
}

function postChatmsg(cont,userto,userto_id){
	var cont = "content=" + cont + "&userto=" + userto + "&userto_id=" + userto_id;
	new Ajax.Request('managechat.php?action=post', {method: 'post',postBody:cont,evalScripts:true});
}

function handleChatSubmit()
{
	var username = $('username').value;
	var userto = $('userto').value;
	var userto_id = $('userto_id').value;
	var cont = $('chattext').value;

	$('submitb').disabled = 'disabled';

	if(cont)
	{
		var now = clock();
		$('chattext').value = "";
		$('textwin').innerHTML += "[" + now + "] <strong>" + username + ":</strong> " + cont + "<br />";
		$('textwin').scrollTop = 100000;
		postChatmsg(cont,userto,userto_id);
		$('chattext').focus();
	}
}

function getKeypressed(event) {
var code = event.keyCode;
	if(code == 13)
	{
	handleChatSubmit();
	}
	else
	{
	$('submitb').disabled = '';
	}
}


function quitchat(){
var userto_id = $('userto_id').value;
var cname = "chatwin" + userto_id;

setCookie(cname,"",-1);

}

function chkChat() {
var script = "managechat.php?action=chk";
    var chk = new Ajax.PeriodicalUpdater("msgchk",script,{method:'get',evalScripts:true,frequency:40,decay:1.5});
}

function openChatwin(userto,uid){
var addr = "managechat.php?userto=" + userto + "&uid=" + uid;
  chatwin = window.open(addr, "chatwin", "width=422,height=300");
}

function clock()
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
	return hours + ":" + minutes;
}