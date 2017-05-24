{* Messages *}
{if $msgnum > 0}
    <div class="msgs padding-bottom-two-px" id="desktopmessages">
        <div class="headline">
            <a href="javascript:void(0);" id="activityhead_toggle" class="win_none" onclick=""></a>

            <div class="wintools">
                <loader block="desktopmessages" loader="loader-messages.gif"></loader>
            </div>
            <h2>
                <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt=""/>{#mymessages#}
            </h2>
        </div>
        <div class="block blockaccordion_content overflow-hidden display-none" id="activityhead">
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
                <tbody v-for="item in items" class="alternateColors" v-bind:id="'messages_' +item.ID">
                <tr>
                    <td>
                        {/literal}{if $userpermissions.messages.close}{literal}
                            <a class="butn_reply" href="javascript:void(0);"
                            v-bind:onclick="'change(\'managemessage.php?action=replyform&amp;mid='+item.ID+'&amp;id='+item.project+'\',\'addmsg\');blindtoggle(\'addmsg\');'"
                            title="{/literal}{#answer#}"></a>
                        {/if}{literal}
                    </td>
                    <td>
                        <div class="toggle-in">
                            <span v-bind:id="'desktopmessages_toggle' + item.ID"
                                  class="acc-toggle"
                                  v-bind:onclick="'javascript:accord_msgs.toggle(css(\'#desktopmessages_content'+$index+'\'));'"></span>
                            <a :href="'managemessage.php?action=showmessage&amp;mid='+item.ID+'&amp;id='+item.project"
                               :title="item.title">
                                {{{item.title | truncate '30'}}}</a>
                        </div>
                    </td>
                    <td>
                        <a v-bind:href="'managemessage.php?action=showproject&amp;id=' + item.project">{{item.pname }}</a>
                    </td>
                    <td>
                        <a v-bind:href="'manageuser.php?action=profile&amp;id=' + item.user">{{item.username }}</a>
                    </td>
                    <td>{{item.postdate}}</td>
                    <td class="tools">
                        {/literal}{if $userpermissions.messages.edit}{literal}
                            <a class="tool_edit" href="javascript:void(0);"
                            :onclick="'change(\'managemessage.php?action=editform&amp;mid='+item.ID+'&amp;id='+item.project+'\',\'addmsg\');blindtoggle(\'addmsg\');'"
                            title="{/literal}{#edit#}"></a>
                        {/if}
                        {if $userpermissions.messages.del}{literal}
                            <a class="tool_del"
                            :href="'javascript:confirmDelete(\'{/literal}{#confirmdel#}{literal}\',\'messages_'+item.ID+'\',\'managemessage.php?action=del&amp;mid='+item.ID+'&amp;id='+item.project+'\',msgsView);'"
                            title="{/literal}{#delete#}"></a>
                        {/if}{literal}
                    </td>
                </tr>
                <tr class="acc">
                    <td colspan="6">
                        <div class="accordion_content">
                            <div class="acc-in">
                                <div class="message-fluid">
                                    <div class="message-in-fluid">
                                        <div class="avatar">
                                            <template v-if="item.avatar != ''">
                                                <img v-bind:src="'thumb.php?width=80&amp;height=80&amp;pic=files/standard/avatar/' +item.avatar"
                                                     alt=""/>
                                            </template>
                                            <template v-else>
                                                <img src="thumb.php?width=80&amp;height=80&amp;pic=templates/{/literal}{$settings.template}/theme/{$settings.theme}{literal}/images/no-avatar-male.jpg"/>
                                            </template>
                                        </div>
                                        {{{item.text}}}
                                    </div>
                                    <!-- message milestones -->
                                    <template v-if="item.hasMilestones">
                                        <p>

                                        <div class="content-spacer-b"></div>
                                        <strong>{/literal}{#milestone#}{literal}:</strong>
                                        <a v-bind:href="'managemilestone.php?action=showmilestone&amp;msid=' + item.milestones.ID + '&amp;id=' +item.milestones.project">
                                            {{item.milestones.name}}
                                        </a>
                                        </p>
                                    </template>

                                    <!-- message files -->
                                    <p v-if="item.hasFiles" class="tags-miles">
                                        <strong>{/literal}{#files#}:{literal}</strong>
                                    </p>

                                    <div v-if="item.hasFiles" class="inwrapper">
                                        <ul>
                                            <li v-for="file in item.files">
                                                <div class="itemwrapper" v-bind:id="'iw_' + file.ID">

                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="leftmen" valign="top">
                                                                <div class="inmenue"></div>
                                                            </td>
                                                            <td class="thumb">

                                                                <a v-bind:href="'managefile.php?action=downloadfile&amp;id=' + file.project +'&amp;file=' + file.ID"
                                                                   v-bind:title=file.name>
                                                                    <img v-bind:src="'templates/standard/theme/standard/images/files/' +file.type +'.png'"
                                                                         v-bind:alt=file.name />
                                                                </a>
                                                            </td>
                                                            <td class="rightmen" valign="top">
                                                                <div class="inmenue">
                                                                    <a class="del"
                                                                       v-bind:href="'managefile.php?action=delete&amp;id=' + file.project + '&amp;file=' + file.ID"
                                                                       title="{#delete#}"
                                                                       :onclick="'fadeToggle(\'iw_'+file.ID+'\');'"></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <span class="name">
                                                                    <a v-bind:href="'managefile.php?action=downloadfile&amp;id=' +file.project +'&amp;file=' + file.ID"
                                                                       v-bind:title=file.name>{{file.shortName}}</a>
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
