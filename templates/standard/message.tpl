{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox"}
{include file="tabsmenue-project.tpl" msgstab="active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="msgs">

            <div class="breadcrumb">
                <a href="manageproject.php?action=showproject&amp;id={$project.ID}">
                    <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>
                    {$projectname|truncate:40:"...":true}
                </a>
                <a href="managemessage.php?action=showproject&amp;id={$project.ID}">
                    <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt=""/>
                    {#messages#}
                </a>
                {if $message.replyto > 0}
                    <a href="managemessage.php?action=showmessage&amp;id={$project.ID}&mid={$message.parentMessage.ID}">
                        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt=""/>
                        {$message.parentMessage.title}
                    </a>
                {/if}
            </div>

            <h1 class="second">
                <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt=""/>
                {$message.title|truncate:40:"...":true}
            </h1>

            <div class="statuswrapper">
                <ul>
                    {if $userpermissions.messages.close}
                        <li class="link"><a class="reply" id="add_reply_a" href="javascript:void(0);"
                                            onclick="blindtoggle('form_reply_a');toggleClass(this,'reply-active','reply');toggleClass('sm_replies_a','smooth','nosmooth');"
                                            title="{#reply#}"></a></li>
                    {/if}
                    {if $userpermissions.messages.edit}
                        <li class="link"><a class="edit" href="javascript:void(0);" id="edit_butn"
                                            onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_replies_a','smooth','nosmooth');"
                                            title="{#edit#}"></a></li>
                    {/if}
                    {if $message.replies}
                        <li><a>{#replies#}: {$message.replies}</a></li>
                    {/if}
                </ul>
            </div>

            {* Add Reply *}
            {if $userpermissions.messages.close}
                <div id="form_reply_a" class="addmenue display-none">
                    <div class="content-spacer"></div>
                    {include file="replyform.tpl" showhtml="no" reply="a"}
                </div>
            {/if}

            {* Edit Message *}
            {if $userpermissions.messages.edit}
                <div id="form_edit" class="addmenue display-none">
                    <div class="content-spacer"></div>
                    {include file="editmessageform.tpl" showhtml="no"}
                </div>
            {/if}

            <div class="content-spacer"></div>

            <div id="sm_replies_a" class="nosmooth">
                <div id="message" class="descript" style="border:1px dashed">
                    {if $message.avatar != ""}
                        <div class="avatar">
                            <img src="thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$message.avatar}" alt=""/>
                        </div>
                    {else}
                        <div class="avatar">
                            <img src="thumb.php?width=80&amp;height=80&amp;pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg"
                                 alt=""/>
                        </div>
                    {/if}

                    <div class="message">
                        <div class="message-in-fluid">
                            <h2>{$message.endstring}&nbsp;/&nbsp;{$message.username|truncate:20:"...":true}</h2>
                            {$message.text}
                        </div>

                        {if $message.milestones[0] != ""}
                            <div class="content-spacer-b"></div>
                            {* Milestones *}
                            {if $message.milestones[0] != ""}
                                <p>
                                    <strong>{#milestone#}: </strong>
                                    <a href="managemilestone.php?action=showmilestone&amp;msid={$message.milestones.ID}&amp;id={$project.ID}">{$message.milestones.name}</a>
                                </p>
                            {/if}

                        {/if}

                        {*Files*}
                        {if $message.files[0][0] > 0}
                            <strong>{#files#}:</strong>
                            <div class="inwrapper">
                                <ul style="list-style-type: none">
                                    {section name = file loop=$message.files}
                                        <li id="fli_{$message.files[file].ID}" style="list-style-type: none">
                                            <div class="itemwrapper" id="iw_{$message.files[file].ID}">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="leftmen" valign="top">
                                                            <div class="inmenue"></div>
                                                        </td>
                                                        <td class="thumb">
                                                            <a href="managefile.php?action=downloadfile&amp;id={$message.files[file].project}&amp;file={$message.files[file].ID}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.ID}]"{/if}
                                                               title="{$message.files[file].name}">
                                                                <img src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$message.files[file].type}.png"
                                                                     alt="{$message.files[file].name}"/>
                                                            </a>
                                                        </td>
                                                        <td class="rightmen" valign="top">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">
															<span class="name">
														    <a href="managefile.php?action=downloadfile&amp;id={$message.files[file].project}&amp;file={$message.files[file].ID}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.ID}]"{/if}
                                                               title="{$message.files[file].name}">

                                                                {if $message.files[file].title != ""}
                                                                    {$message.files[file].title|truncate:13:"...":true}
                                                                {else}
                                                                    {$message.files[file].name|truncate:13:"...":true}
                                                                {/if}
                                                            </a>
															</span>
                                                        </td>
                                                    <tr/>
                                                </table>

                                            </div> {*itemwrapper End*}
                                        </li>
                                    {/section} {*files End*}
                                </ul>
                            </div>
                            {*inwrapper End*}
                        {/if}
                    </div> {*message End*}
                </div> {*nosmooth End*}
                <div class="content-spacer"></div>
            </div> {*descript End*}

            {include file="messageReplies.tpl"}
        </div> {*MSGs END*}
    </div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}