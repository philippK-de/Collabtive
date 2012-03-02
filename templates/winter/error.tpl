{include file="header.tpl"  jsload = "ajax" }

<div class="login">
	<div class="login-in">

        <div class = "row">
        	<h1 style = "color:red;">{#error#}</h1>
            <div style = "color:red;" class = "row">{$errortext}</div>
        </div>

    </div>
</div>
{include file="footer.tpl"}