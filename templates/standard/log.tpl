<div class="headline">
    <a href="javascript:void(0);" id="loghead_toggle" class="win_none" onclick=""></a>

    <div class="wintools">

        <div class="export-main">

            <div class="progress display-none" id="progressprojectLog">
                <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-neutral.gif"/>
            </div>
            <a class="export"><span>{#export#}</span></a>

            <div class="export-in" style="width:46px;left: -46px;"> {* at one item *}
                <a class="pdf" href="manageproject.php?action=projectlogpdf&amp;id={$project.ID}"><span>{#pdfexport#}</span></a>
                <a class="excel" href="manageproject.php?action=projectlogxls&amp;id={$project.ID}"><span>{#excelexport#}</span></a>
            </div>
        </div>

    </div>

    <h2>
        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/activity.png" alt=""/>{#activity#}
    </h2>
</div>

<div class="block accordion_content overflow-hidden display-none" id="loghead">
    <table id="projectLog" class="log" cellpadding="0" cellspacing="0" border="0"  v-cloak>
        <thead>
        <tr>
            <th class="a"></th>
            <th class="bc">{#action#}</th>
            <th class="d">{#user#}</th>
            <th class="tools"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="5"></td>
        </tr>
        </tfoot>
        {literal}
        <tbody v-for="logitem in items" class="alternateColors"
               v-bind:id="'log_'+logitem.ID">
        <tr>
            <td style="padding:0" class="symbols">
                <img style="margin:0 0 0 3px;"
                     v-bind:src="'./templates/{/literal}{$settings.template}/theme/{$settings.theme}{literal}/images/symbols/' + logitem.type + '.png'"
                     :alt="logitem.type" :title="logitem.type"/>
            </td>
            <td>
                <div class="toggle-in">
                    <strong>{{{logitem.name | truncate '35' }}}</strong><br/>
							<span class="info">{/literal}{#was#}{literal}
                                <span v-if="logitem.action == 1">
                                    {/literal}
                                    {#added#}
                                    {literal}
                                </span>
								<span v-if="logitem.action == 2">
                                    {/literal}
                                    {#edited#}
                                    {literal}
                                </span>
								<span v-if="logitem.action == 3">
                                    {/literal}
                                    {#deleted#}
                                    {literal}
                                </span>
								<span v-if="logitem.action == 4">
                                    {/literal}
                                    {#opened#}
                                    {literal}
                                </span>
						        <span v-if="logitem.action == 5">
                                    {/literal}
                                    {#closed#}
                                    {literal}
                                </span>
						        <span v-if="logitem.action == 6">
                                    {/literal}
                                    {#assigned#}
                                    {literal}
                                </span>
						        <span v-if="logitem.action == 7">
                                    {/literal}
                                    {#deassigned#}
                                    {literal}
                                </span>
								{{logitem.datum}}
							</span>
                </div>
            </td>
            <td>
                <a v-bind:href="'manageuser.php?action=profile&amp;id='+logitem.user">{{logitem.username | truncate '30' }}</a>
            </td>
            <td class="tools"></td>
        </tr>
        </tbody>
        {/literal}

        <tbody class="paging">
        <tr>
            <td></td>
            <td colspan="2">
                <div id="paging">
                    <pagination view="projectLogView" :pages="pages" :current-page="currentPage"></pagination>
                </div>
            </td>
            <td class="tools"></td>
        </tr>
        </tbody>

    </table>

    <div class="tablemenue"></div>
</div> {*block end*}

<div class="content-spacer"></div>
{literal}
<script type="text/javascript">
    var projectLog = {
        el: "projectLog",
        itemType: "log",
        url: "manageproject.php?action=projectLog",
        dependencies: []
    }
    projectLog.url = projectLog.url + "&id=" + {/literal}{$project.ID}{literal};

    pagination.itemsPerPage = 25;
    var projectLogView = createView(projectLog);
</script>
{/literal}