{include file="header.tpl" jsload = "ajax" jsload2="chat" focusme = "1" showheader="no"}
<input type = "hidden" id = "userto" value = "{$userto}" />
<input type = "hidden" id = "userto_id" value = "{$userto_id}" />
<input type = "hidden" id = "username" value = "{$username}" />

<div class="chat">

<h2 class="">{#chatwith#} {$userto}</h2>
<div id = "textwin" class="chattext"></div>
{if $avatar != ""}
<img src = "thumb.php?pic=files/avatar/{$avatar}&amp;width=80" style="float:left;margin-left:10px;" alt="" />
{else}
		{if $user.gender == "f"}
		<img src = "thumb.php?pic=templates/standard/images/no-avatar-female.jpg&amp;width=80;" style="float:left;margin-left:10px;" alt="" />
		{else}
		<img src = "thumb.php?pic=templates/standard/images/no-avatar-male.jpg&amp;width=80;" style="float:left;margin-left:10px;" alt="" />
		{/if}
{/if}
<div class="row">
<input style="float:left;" type = "text" class="text" id = "chattext" onkeyup="getKeypressed(event);" />
<button onclick = "handleChatSubmit();return false;"  name = "submitb" id = "submitb" disabled="disabled" onchange = "this.disabled='';" type="submit">{#send#}</button>
</div>

<div id = "focusi"></div>
<input id = "charnum" type = "hidden" value = "0" />
<input id = "hasfocus" type = "hidden" value = "" />


<script type = "text/javascript">
startChat();
$('chattext').focus();
</script>

</div>

</body>
</html>