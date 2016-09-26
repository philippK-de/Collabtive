{include file="header.tpl" jsload = "ajax"}
{include file="tabsmenue-user.tpl" usertab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="user">

            <div class="headline">
                <a href="javascript:void(0);" id="acc-tracker_toggle" class="win_block" onclick = ""></a>
                <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user.png" alt="" />{$user.name}</h2>

                <div class="wintools">
                    <div class="export-main">
                        <a class="export"><span>{#export#}</span></a>

                        <div class="export-in" style="width:64px;left: -32px;"> {*at vcard items*}
                            <a class="vcardmale" href="manageuser.php?action=vcard&amp;id={$user.ID}"><span>{#vcardexport#}</span></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="userwrapper" style="width:100%">

                <table cellpadding="0" cellspacing="0" border="0" style="width:100%">
                    <tr>
                        <td class="avatarcell" valign="top" style="width:20%">
                            {if $user.avatar != ""}
                                <a href="#avatarbig" id="ausloeser">
                                    <div class="avatar-profile"><img src="thumb.php?pic=files/{$cl_config}/avatar/{$user.avatar}&amp;width=122;"
                                                                     alt=""/></div>
                                </a>
                            {else}
                                {if $user.gender == "f"}
                                    <div class="avatar-profile"><img
                                                src="thumb.php?pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-female.jpg&amp;width=122;"
                                                alt=""/></div>
                                {else}
                                    <div class="avatar-profile">
                                        <img src="thumb.php?pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg&amp;width=122;"
                                             alt=""/>
                                    </div>
                                {/if}
                            {/if}
                        </td>
                        <td>
                            <div class="message-fluid">
                                <div class="block">

                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <colgroup>
                                            <col class="a"/>
                                            <col class="b"/>
                                        </colgroup>

                                        <thead>
                                        <tr>
                                            <th colspan="2"></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tfoot>

                                        <tbody class="color-b">
                                        <tr>
                                            <td><strong>{#company#}:</strong></td>
                                            <td class="right">{if $user.company}{$user.company}{/if}</td>
                                        </tr>
                                        </tbody>

                                        <tbody class="color-a">
                                        <tr>
                                            <td><strong>{#email#}:</strong></td>
                                            <td class="right"><a href="mailto:{$user.email}">{$user.email}</a></td>
                                        </tr>
                                        </tbody>

                                        <tbody class="color-b">
                                        <tr>
                                            <td><strong>{#url#}:</strong></td>
                                            <td class="right">{$user.url}</td>
                                        </tr>
                                        </tbody>

                                        <tbody class="color-a">
                                        <tr>
                                            <td><strong>{#phone#}:</strong></td>
                                            <td class="right">{$user.tel1}</td>
                                        </tr>
                                        </tbody>

                                        <tbody class="color-b">
                                        <tr>
                                            <td><strong>{#cellphone#}:</strong></td>
                                            <td class="right">{$user.tel2}</td>
                                        </tr>
                                        </tbody>

                                        <tbody class="color-a">
                                        <tr>
                                            <td><strong>{#address#}:</strong></td>
                                            <td class="right">{$user.adress}</td>
                                        </tr>
                                        </tbody>

                                        <tbody class="color-b">
                                        <tr>
                                            <td><strong>{#zip#} / {#city#}:</strong></td>
                                            <td class="right">{$user.zip}{if $user.zip && $user.adress2} {/if}{$user.adress2} </td>
                                        </tr>
                                        </tbody>

                                        <tbody class="color-a">
                                        <tr>
                                            {if $user.state == ""}
                                                <td><strong>{#country#}:</strong></td>
                                                <td class="right">{$user.country}</td>
                                            {elseif $user.country == ""}
                                                <td><strong>{#state#}:</strong></td>
                                                <td class="right">{$user.state}</td>
                                            {else}
                                                <td><strong>{#country#} ({#state#}):</strong></td>
                                                <td class="right">{$user.country} ({$user.state})</td>
                                            {/if}
                                        </tr>
                                        </tbody>
                                    </table>

                                </div> {*Block End*}
                            </div> {*Message End*}
                        </td>
                    </tr>
                </table>
            </div> {*UserWrapper End*}
        </div> {*User END*}
        <div class="content-spacer"></div>

        {include file="userProfileProjects.tpl"}



        {if $userpermissions.admin.add or $userid == $user.ID} {*timetracker start*}
            {include file="userProfileTimetracker.tpl"}
        {/if}
        <div class="content-spacer"></div>


    </div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}