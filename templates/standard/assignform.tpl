{include file="header.tpl" jsload = "ajax" title=""}
<h1>{$user.name}'s {#projects#}</h1>

<form name="assignform" method="post" action="admin.php?action=massassign">
{section name=proj loop=$projects}
<label for="projects{$projects[proj].ID}">{$projects[proj].name}:</label><input type="checkbox" value="{$projects[proj].ID}" id="projects{$projects[proj].ID}" name="projects[]"  {if $projects[proj].assigned == 1}checked{/if} /><br />
{/section}
<input type = "hidden" name = "id" value = "{$user.ID}" />
<input type="submit" action="submit" value="{#send#}" />
</form>

{include file="footer.tpl"}