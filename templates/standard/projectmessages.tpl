{include file="header.tpl"  jsload = "ajax"  jsload1="tinymce" jsload3 = "lightbox"}
{include file="tabsmenue-project.tpl" msgstab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="msgs">
            <div class="infowin_left display-none"
                 id="messageSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
                 data-text-added="{#messagewasadded#}"
                 data-text-edited="{#messagewasedited#}"
                 data-text-deleted="{#messagewasdeleted#}"
                 data-text-replied="{#replywasadded#}"
                    >
            </div>
            <h1>{$projectname|truncate:45:"...":true}<span>/ {#messages#}</span></h1>
        </div>
        <!-- container for the blockAccordeon-->
        <div id="projectMessagesContainer">
        {include file="projectPublicMessages.tpl"}
        {include file="projectPrivateMessages.tpl"}
        </div>
    </div>
    <!-- content-left-in END-->
</div> <!-- content-left END -->
    <script type="text/javascript" src="include/js/accordion.js"></script>
    <script type="text/javascript" src="include/js/views/projectMessages.min.js"></script>
{literal}
<script type="text/javascript">
    pagination.itemsPerPage = 20;
    projectMessages.url = projectMessages.url + "&id=" + {/literal}{$project.ID}{literal};
    projectMessagesView = createView(projectMessages);

    userMessages.url = userMessages.url + "&id=" + {/literal}{$project.ID}{literal};
    userMessagesView = createView(userMessages);

    //bind submit handler for the create message form and create the blockaccordeon
    projectMessagesView.afterLoad(function () {
        addMessageForm = document.getElementById("addmessageform");
        formView = projectMessagesView;
        formView.doUpdate = true;
        addMessageForm.addEventListener("submit", submitForm.bind(formView));

    });
    //initialize blocks accordeon
    //this creates the object on which methods are called later
    accordMessages = new accordion2("projectMessagesContainer", {
        classNames: {
            toggle: 'win_none',
            toggleActive: 'win_block',
            content: 'blockaccordion_content'
        }
    });
    window.addEventListener("load",initializeBlockaccordeon);

    var accord_messages;
    var accord_user_messages;
    //after each update create the inner accordeon
    projectMessagesView.afterUpdate(function(){
        accord_messages = new accordion2('publicMessages');
        //update user messages when a message was added
        updateView(userMessagesView);
    });

    userMessagesView.afterUpdate(function(){
        accord_user_messages = new accordion2('privateMessages');
    });
</script>
{/literal}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}