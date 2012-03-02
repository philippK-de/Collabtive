{include file="header.tpl"  jsload = "ajax" }
<script type = "text/javascript" src = "include/js/mycalendar.js"></script>

<a href = "javascript:bla.toggleTracker();">aaa</a>

<!-- Datepicker -->
<form>
<input type = "text" name = "bla" id = "bla" value = "14.10.2008"/>
<div id = "dp1" class = "calendar" style = "position:absolute;display:none;right:5%;top:10%;"></div>
</form>
<script type = "text/javascript">

theCal = new calendar({$theM},{$theY});
theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
theCal.relateTo = "bla";
theCal.getDatepicker('dp1');

</script>

<!-- Kalender -->
<div id = "mycal"></div>
<script type = "text/javascript">
relateTo = "bla";
makeCal(10,2008,'mycal');
</script>

<br />

{if $weeks[week][day].tasksnum > 0}
						<a href = "#tasks{$weeks[week][day].val}" id = "tasklink{$weeks[week][day].val}" ><img src = "templates/standard/images/symbols/task.png" alt = ""></a>
						<div id = "tasks{$weeks[week][day].val}">
						{section name = task loop=$weeks[week][day].tasks}
						{$weeks[week][day].tasks[task].title}<br/>
						{/section}
						</div>
						{literal}
						<script type = "text/javascript">
						new Control.Modal('tasklink{/literal}{$weeks[week][day].val}{literal}',{
						opacity: 0.8,
						position: 'relative',
						width: 500,
						height: 200
						});
						</script>
						{/literal}
					{/if}
<div class ="content_left">

<div id = "thecal"></div>

</div> {*Content_left end*}
{literal}
<script type = "text/javascript">
change('manageajax.php?action=newcal','thecal');
bla = new timetracker();
</script>
{/literal}
{include file="footer.tpl"}