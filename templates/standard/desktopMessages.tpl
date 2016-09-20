{* Messages *}
{if $msgnum > 0}
    <div class="msgs padding-bottom-two-px" id="desktopmessages">
        <div class="headline">
            <a href="javascript:void(0);" id="activityhead_toggle" class="win_none" onclick=""></a>

            <div class="wintools">
                <loader block="desktopmessages" loader="loader-messages.gif"></loader>
                <div class="export-main">
                    <a class="export"><span>{#export#}</span></a>

                    <div class="export-in" style="width:46px;left: -46px;"> {* at one item *}
                        <a class="rss" href="managerss.php?action=mymsgs-rss&amp;user={$userid}"><span>{#rssfeed#}</span></a>
                        <a class="pdf" href="managemessage.php?action=mymsgs-pdf&amp;id={$userid}"><span>{#pdfexport#}</span></a>
                    </div>
                </div>
            </div>
            <h2>
                <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt=""/>{#mymessages#}
            </h2>
        </div>
        <div class="block blockaccordion_content overflow-hidden" id="activityhead">
            <div id="addmsg" class="addmenue display-none">
            </div>
            <table cellpadding="0" cellspacing="0" border="0" v-cloak>
                <thead>
                <tr>
                    <th class="tools"></th>
                    <th class="b">{#message#}</th>
                    <th class="c">{#project#}</th>
                    <th class="d">{#by#}</th>
                    <th class="d">{#on#}</th>
                    <th class="tools"></th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td colspan="6"></td>
                </tr>
                </tfoot>

                {literal}
                <tbody v-for="item in items" class="alternateColors" id="messages_{{*item.ID}}"
                       rel="{{*item.ID}},{{*item.title}},{{*item.posted}},0,0,0">
                <tr>
                    <td>
                        {/literal}{if $userpermissions.messages.close}{literal}
                            <a class="butn_reply" href="javascript:void(0);" onclick="change('managemessage.php?action=replyform&amp;mid={{*item.ID}}&amp;id={{*item.project}}','addmsg');toggleClass(this,'butn_reply_active','butn_reply');blindtoggle('addmsg');"
                            title="{/literal}{#answer#}"></a>
                        {/if}{literal}
                    </td>
                    <td>
                        <div class="toggle-in">
                            <span id="desktopmessages_toggle{{*item.ID}}"
                                  class="acc-toggle"
                                  onclick="javascript:accord_msgs.toggle(css('#desktopmessages_content{{$index}}'));"></span>
                            <a href="managemessage.php?action=showmessage&amp;mid={{*item.ID}}&amp;id={{*item.project}}" title="{{*item
                            .title}}">{{*item.title | truncate '30' }}</a>
                        </div>
                    </td>
                    <td>
                        <a href="managemessage.php?action=showproject&amp;id={{*item.project}}">{{*item.pname | truncate '30' }}</a>
                    </td>
                    <td>
                        <a href="manageuser.php?action=profile&amp;id={{*item.user}}">{{*item.username | truncate '30' }}</a>
                    </td>
                    <td>{{*item.postdate}}</td>
                    <td class="tools">
                        {/literal}{if $userpermissions.messages.edit}{literal}
                            <a class="tool_edit" href="javascript:void(0);" onclick="change('managemessage.php?action=editform&amp;mid={{*item.ID}}&amp;id={{*item.project}}','addmsg');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('addmsg');" title="{/literal}{#edit#}"></a>
                        {/if}
                        {if $userpermissions.messages.del}{literal}
                            <a class="tool_del"
                            href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','messages_{{*item.ID}}','managemessage.php?action=del&amp;mid={{*item.ID}}&amp;id={{*item.project}}', msgsView);" title="{/literal}{#delete#}"></a>
                        {/if}{literal}
                    </td>
                </tr>
                <tr class="acc">
                    <td colspan="6">
                        <div class="accordion_content">
                            <div class="acc-in">
                                <div class="message-fluid">
                                    <div class="message-in-fluid">
                                        {{{*item.text}}}
                                    </div>
                                    <!-- message milestones -->
                                    <template v-if="item.hasMilestones">
                                        <p>

                                        <div class="content-spacer-b"></div>
                                        <strong>{/literal}{#milestone#}{literal}:</strong>
                                        <a href="managemilestone.php?action=showmilestone&amp;msid={{*item.milestones.ID}}&amp;id={{*item.milestones.project}}">{{*item.milestones.name}}</a>
                                        </p>
                                    </template>

                                    <!-- message files -->
                                    <p v-if="item.hasFiles" class="tags-miles">
                                        <strong>{/literal}{#files#}:{literal}</strong>
                                    </p>

                                    <div v-if="item.hasFiles" class="inwrapper">
                                        <ul>
                                            <li v-for="file in item.files">
                                                <div class="itemwrapper" id="iw_{{*file.ID}}">

                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="leftmen" valign="top">
                                                                <div class="inmenue"></div>
                                                            </td>
                                                            <td class="thumb">

                                                                <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}"
                                                                   title="{{*file.name}}">
                                                                    <img v-bind:src="templates/standard/theme/standard/images/files/{{file.type}}.png"
                                                                         alt="{{*file.name}}"/>
                                                                </a>
                                                            </td>
                                                            <td class="rightmen" valign="top">
                                                                <div class="inmenue">
                                                                    <a class="del"
                                                                       href="managefile.php?action=delete&amp;id={{file.project}}&amp;file={{file].ID}}"
                                                                       title="{#delete#}" onclick="fadeToggle('iw_{{file].ID}}');"></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <span class="name">
                                                                    <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}"
                                                                       title="{{*file.name}}">{{*file.shortName}}</a>
                                                                </span>
                                                            </td>
                                                        <tr/>
                                                    </table>

                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="clear_both"></div>
                                </div>
                                <!-- div messages end -->
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
