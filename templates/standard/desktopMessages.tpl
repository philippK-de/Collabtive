{* Messages *}
{if $msgnum > 0}
    <div class="msgs" style="padding-bottom:2px;">
        <div class="headline">
            <a href="javascript:void(0);" id="activityhead_toggle" class="win_none"
               onclick="changeElements('a.win_block','win_none');toggleBlock('activityhead');"></a>

            <div class="wintools">

                <div class="export-main">
                    <a class="export"><span>{#export#}</span></a>

                    <div class="export-in" style="width:46px;left: -46px;"> {* at one item *}
                        <a class="rss" href="managerss.php?action=mymsgs-rss&amp;user={$userid}"><span>{#rssfeed#}</span></a>
                        <a class="pdf" href="managemessage.php?action=mymsgs-pdf&amp;id={$userid}"><span>{#pdfexport#}</span></a>
                    </div>
                </div>
                <div class="progress" id="progressdesktopmessages" style="display:none;">
                    <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-cal.gif"/>
                </div>
            </div>

            <h2>
                <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt=""/>{#mymessages#}
            </h2>
        </div>
        <div class="acc_toggle"></div>
        <div class="block acc_content" id="activityhead" style="overflow:hidden;">
            <div id="addmsg" class="addmenue" style="display:none;">
            </div>
            <table id="desktopmessages" cellpadding="0" cellspacing="0" border="0" v-cloak>

                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b">{#message#}</th>
                    <th class="ce">{#project#}</th>
                    <th class="de">{#by#}</th>
                    <th class="e">{#on#}</th>
                    <th class="tools"></th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td colspan="6"></td>
                </tr>
                </tfoot>

                {literal}
                <tbody v-for="item in items" class="alternateColors" id="messages_{{item.ID}}" rel="{{item.ID}},{{item.title}},{{item..posted}},0,0,0">


                <tr>
                    <td>
                        {/literal}{if $userpermissions.messages.close}{literal}
                            <a class="butn_reply" href="javascript:void(0);"
                            onclick="change('managemessage.php?action=replyform&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}','addmsg');toggleClass(this,'butn_reply_active','butn_reply');blindtoggle('addmsg');"
                            title="{/literal}{#answer#}"></a>
                        {/if}{literal}
                    </td>
                    <td>
                        <div class="toggle-in">
                                            <span class="acc-toggle"
                                                  onclick="javascript:accord_msgs.activate($$('#activityhead .accordion_toggle')[{{$index}}]);toggleAccordeon('activityhead',this);"></span>
                            <a href="managemessage.php?action=showmessage&amp;mid={item.ID}&amp;id={item.project}" title="{{item.title}}">{{item.title}}</a>
                        </div>
                    </td>
                    <td>
                        <a href="managemessage.php?action=showproject&amp;id={{item.project}}">{{item.pname}}</a>
                    </td>
                    <td>
                        <a href="manageuser.php?action=profile&amp;id={{item.user}}">{{item.username}}</a>
                    </td>
                    <td>{{item.postdate}}</td>
                    <td class="tools">
                        {/literal}{if $userpermissions.messages.edit}{literal}
                            <a class="tool_edit" href="javascript:void(0);" onclick="change('managemessage.php?action=editform&amp;mid={{item.ID}}&amp;id={{item.project}}','addmsg');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('addmsg');" title="{/literal}{#edit#}"></a>
                        {/if}
                        {if $userpermissions.messages.del}{literal}
                            <a class="tool_del"
                            href="javascript:confirmfunction('{/literal}{#confirmdel#}{literal}','deleteElement(\'messages_{$messages[message].ID}\',\'managemessage.php?action=del&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}\')');"
                            title="{/literal}{#delete#}"></a>
                        {/if}{literal}
                    </td>
                </tr>

                <tr class="acc">
                    <td colspan="6">
                        <div class="accordion_toggle"></div>
                        <div class="accordion_content">
                            <div class="acc-in">

                                {if $messages[message].avatar != ""}
                                <div class="avatar"><img
                                            src="thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$messages[message].avatar}"
                                            alt=""/></div>
                                {else}
                                {if $messages[message].gender == "f"}
                                <div class="avatar"><img
                                            src="thumb.php?width=80&amp;height=80&amp;pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-female.jpg"
                                            alt=""/></div>
                                {else}
                                <div class="avatar"><img
                                            src="thumb.php?width=80&amp;height=80&amp;pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg"
                                            alt=""/></div>
                                {/if}
                                {/if}

                                <div class="message">
                                    <div class="message-in">
                                        {{{item.text}}}
                                    </div>

                                    {* MILESTONE and TAGS *}
                                    {if $messages[message].tagnum > 1 or $messages[message].milestones[0] != ""}
                                    <div class="content-spacer-b"></div>
                                    {* MESSAGES-MILESTONES *}
                                    {if $messages[message].milestones[0] != ""}
                                    <p>
                                        <strong>{#milestone#}:</strong>
                                        <a href="managemilestone.php?action=showmilestone&amp;msid={$messages[message].milestones.ID}&amp;id={$messages[message].project}">{$messages[message].milestones.name}</a>
                                    </p>
                                    {/if}

                                    {/if}

                                    {* MESSAGES-FILES *}
                                    {if $messages[message].files[0][0] > 0}
                                    <p class="tags-miles">
                                        <strong>{#files#}:</strong>
                                    </p>
                                    <div class="inwrapper">
                                        <ul>

                                            {section name=file loop=$messages[message].files}
                                            <li>
                                                <div class="itemwrapper" id="iw_{$messages[message].files[file].ID}">

                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="leftmen" valign="top">
                                                                <div class="inmenue"></div>
                                                            </td>
                                                            <td class="thumb">

                                                                <a href="managefile.php?action=downloadfile&amp;id={$messages[message].files[file].project}&amp;file={$messages[message].files[file].ID}"{if $messages[message].files[file].imgfile == 1} rel="lytebox[img{$messages[message].ID}]"{/if}
                                                                title="{$messages[message].files[file].name}"> <img
                                                                        src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$messages[message].files[file].type}.png"
                                                                        alt="{$messages[message].files[file].name}"/>
                                                                <
                                                                </a>
                                                            </td>
                                                            <td class="rightmen" valign="top">
                                                                <div class="inmenue">
                                                                    <a class="del"
                                                                       href="managefile.php?action=delete&amp;id={$myprojects[project].ID}&amp;file={$messages[message].files[file].ID}"
                                                                       title="{#delete#}"
                                                                       onclick="fadeToggle('iw_{$messages[message].files[file].ID}');"></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><span class="name">
																							<a href="managefile.php?action=downloadfile&amp;id={$messages[message].files[file].project}&amp;file={$messages[message].files[file].ID}"{if $messages[message].files[file].imgfile == 1} rel="lytebox[img{$messages[message].ID}]"{/if}
                                                                                               title="{$messages[message].files[file].name}">{$messages[message].files[file].name|truncate:15:"...":true}</a></span>
                                                            </td>
                                                        <tr/>
                                                    </table>

                                                </div> {*itemwrapper End*}
                                            </li>
                                            {/section}

                                        </ul>
                                    </div>
                                    {*inwrapper End*}
                                    <div style="clear:both"></div>
                                    {/if}

                                </div> {*div messages end*}

                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                {/literal}
            </table>

            <div class="tablemenue"></div>

        </div> {* block END *}
    </div>
    {* messages END *}

{/if}
