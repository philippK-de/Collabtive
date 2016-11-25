{include file="header.tpl" jsload = "ajax"}
{include file="tabsmenue-user.tpl" usertab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div id="userProfile">
            <div class="user" id="userDetails">
                <div class="headline">
                    <a href="javascript:void(0);" id="userDetails_toggle" class="win_none" onclick=""></a>

                    <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user.png" alt=""/>{$user.name}</h2>

                    <div class="wintools">
                        <div class="export-main">
                            <a class="export"><span>{#export#}</span></a>

                            <div class="export-in" style="width:64px;left: -32px;"> {*at vcard items*}
                                <a class="vcardmale" href="manageuser.php?action=vcard&amp;id={$user.ID}"><span>{#vcardexport#}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="userwrapper width-100 blockaccordion_content overflow-hidden display-none">
                    <table class="width-100" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="avatarcell width-20" valign="top">
                                {if $user.avatar != ""}
                                    <a href="#avatarbig" id="ausloeser">
                                        <div class="avatar-profile text-align-center">
                                            <img src="thumb.php?pic=files/{$cl_config}/avatar/{$user.avatar}&amp;width=150;" alt=""/>
                                        </div>
                                    </a>
                                {else}
                                    <div class="avatar-profile">
                                        <img src="thumb.php?pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg&amp;width=122;"
                                             alt=""/>
                                    </div>
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
                                                <td class="right">{$zipcity} </td>
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
            <div class="padding-bottom-two-px clear_both"></div>
            {if $userpermissions.admin.add or $userid == $user.ID} {*timetracker start*}
                {include file="userProfileTimetracker.tpl"}
                {include file="userProfileProjects.tpl"}
            {/if}
            <div class="padding-bottom-two-px"></div>
        </div>

    </div> {*content-left-in END*}
</div> {*content-left END*}

<script type="text/javascript" src="include/js/accordion.min.js"></script>
<script type="text/javascript" src="include/js/views/userProfileView.min.js"></script>
<script type="text/javascript">
    {if $userpermissions.admin.add or $userid == $user.ID}
    //create views
    userProfileTimetracker.url = userProfileTimetracker.url + "&id=" + {$user.ID};
    var userProfileTimetrackerView = createView(userProfileTimetracker);

    userProfileProjects.url = userProfileProjects.url + "&id=" + {$user.ID};
    var userProfileProjectsView = createView(userProfileProjects);

    var accord_tracker;
    userProfileTimetrackerView.afterUpdate(function() {
        accord_tracker = new accordion2('userTimetrackerAccordeon');
    });

    var accord_projects;
    userProfileProjectsView.afterUpdate(function(){
        accord_projects = new accordion2('userProjectsAccordeon');
    });
   {/if}

    //create accordeons
    accordUserprofile = new accordion2("userProfile", {
        classNames: {
            toggle: 'win_none',
            toggleActive: 'win_block',
            content: 'blockaccordion_content'
        }
    });
    window.addEventListener("load",initializeBlockaccordeon);
</script>
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}