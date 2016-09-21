{* ANSWERS *}
{if $replies > 0}
    <script type="text/javascript" src="include/js/accordion.js"></script>
    <div class="headline">
        <a href="javascript:void(0);" id="block-answers_toggle" class="win_block" onclick="toggleBlock('block-answers');"></a>

        <div class="wintools">
            {if $userpermissions.messages.close}
                <a class="add" href="javascript:blindtoggle('form_reply_a');" id="add_replies"
                   onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_replies','butn_link_active','butn_link');toggleClass('sm_replies','smooth','nosmooth');"><span>{#answer#}</span></a>
            {/if}
        </div>
        <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt=""/>{#replies#}</a></h2>
    </div>
    <div id="block-answers" class="block">
        <div class="nosmooth" id="sm_replies">
            <table id="acc_replies" cellpadding="0" cellspacing="0" border="0">

                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b">{#message#}</th>
                    <th class="ce" class="text-align-right">{#replies#}</th>
                    <th class="c">{#on#}</th>
                    <th class="d">{#by#}</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td colspan="5"></td>
                </tr>
                </tfoot>

                {section name=reply loop=$replies}
                    {*Color-Mix*}
                    {if $smarty.section.reply.index % 2 == 0}
                        <tbody class="color-a" id="reply_{$replies[reply].ID}">
                        {else}
                        <tbody class="color-b" id="reply_{$replies[reply].ID}">
                    {/if}
                    <tr>
                        <td></td>
                        <td>
                            <div class="toggle-in">
                                <span class="acc-toggle"
                                      onclick="javascript:accord_answer.toggle(css('#acc_replies_content{$smarty.section.reply.index}'));"></span>
                                <a href="managemessage.php?action=showmessage&amp;mid={$replies[reply].ID}&amp;id={$project.ID}">{$replies[reply].title|truncate:30:"...":true}</a>
                            </div>
                        </td>
                        <td>{$replies[reply].replies}</td>
                        <td>{$replies[reply].postdate}</td>
                        <td>
                            <a href="manageuser.php?action=profile&amp;id={$replies[reply].user}">{$replies[reply].username|truncate:20:"...":true}</a>
                        </td>
                    </tr>
                    <tr class="acc">
                        <td colspan="6">
                            <div class="accordion_content">
                                <div class="acc-in">

                                    {if $replies[reply].avatar != ""}
                                        <div class="avatar">
                                            <img src="thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$replies[reply].avatar}" alt=""/>
                                        </div>
                                    {else}
                                        {if $replies[reply].gender == "f"}
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
                                            {$replies[reply].text|nl2br}
                                        </div>

                                        {*MESSAGEFILES*}
                                        {if $replies[reply].files[0][0] > 0}
                                            <div class="content-spacer-b"></div>
                                            <strong>{#files#}:</strong>
                                            <div class="inwrapper">
                                                <ul>
                                                    {section name = file loop=$replies[reply].files}
                                                        <li id="fli_{$replies[reply].files[file].ID}">
                                                            <div class="itemwrapper">

                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td class="leftmen" valign="top">
                                                                            <div class="inmenue"></div>
                                                                        </td>
                                                                        <td class="thumb">
                                                                            <a href="managefile.php?action=downloadfile&amp;id={$replies[reply].files[file].project}&amp;file={$replies[reply].files[file].ID}"{if $replies[reply].files[file].imgfile == 1} rel="lytebox[img{$replies[reply].ID}]"{/if}
                                                                               title="{$replies[reply].files[file].name}">
                                                                                <img src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$replies[reply].files[file].type}.png"
                                                                                     alt=""/>
                                                                            </a>
                                                                        </td>
                                                                        <td class="rightmen" valign="top">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <span class="name">
																					<a href="managefile.php?action=downloadfile&amp;id={$replies[reply].files[file].project}&amp;file={$replies[reply].files[file].ID}"{if $replies[reply].files[file].imgfile == 1} rel="lytebox[img{$replies[reply].ID}]"{/if}
                                                                                       title="{$replies[reply].files[file].name}">
                                                                                        {$replies[reply].files[file].name|truncate:15:"...":true}
                                                                                    </a>
                                                                        </td>
                                                                    <tr/>
                                                                </table>
                                                            </div> {*itemwrapper End*}
                                                        </li>
                                                    {/section}
                                                </ul>
                                            </div>
                                            {*inwrapper End*}
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                {/section}
            </table>
        </div> {*nosmooth End*}

        <div class="tablemenue">
            <div class="tablemenue-in">
                <a class="butn_link" href="javascript:blindtoggle('form_reply_a');" id="add_butn_replies"
                   onclick="toggleClass('add_replies','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_replies','smooth','nosmooth');">{#answer#}</a>
            </div>
        </div>
    </div>
    {*block End*}
{literal}
    <script type="text/javascript">
        var accord_answer = new accordion2('acc_replies');
    </script>
{/literal}
    <div class="content-spacer"></div>
{/if} {*if REPLY End*}
