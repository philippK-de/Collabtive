{* Messages *}
{if $msgnum > 0}
    <div class="msgs padding-bottom-two-px" id="desktopmessages">
        <div class="headline">
            <a href="javascript:void(0);" id="activityhead_toggle" class="win_none" onclick=""></a>

            <div class="wintools">
                <loader block="desktopmessages" loader="loader-messages.gif"></loader>
            </div>
            <h2>
                <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png"
                     alt=""/>{#mymessages#}
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
                        <a v-bind:href="'managemessage.php?action=showproject&amp;id=' + item.project">{{item.pname
                            }}</a>
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
                                        <div class="content-spacer-b"></div>
                                        <h2>{/literal}{#milestones#}{literal}</h2>

                                        <div class="dtree"
                                             :id="'milestoneTree' + item.ID">

                                        </div>
                                    </template>
                                    <!-- message files -->
                                    <template v-if="item.hasFiles">
                                        <div class="content-spacer-b"></div>
                                        <h2>{/literal}{#files#}{literal}</h2>

                                        <div class="dtree"
                                             :id="'filesTree' + item.ID">

                                        </div>
                                    </template>

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
