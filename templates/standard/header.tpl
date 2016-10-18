{config_load file='lng.conf' section = "strings" scope="global" }
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>{$title} @ {$settings.name}</title>
    <link rel="shortcut icon" href="templates/{$settings.template}/theme/{$settings.theme}/images/favicon.ico" type="image/x-icon"/>
    {if $stage == "project" and $loggedin}
        <link rel="stylesheet" href="templates/{$settings.template}/theme/{$settings.theme}/css/dtree.css" type="text/css"/>
        <script type="text/javascript" src="include/js/dtree.min.js"></script>
    {/if}
    {if $jsload|default == "ajax"}
    {literal}
        <script type="text/javascript" src="include/js/velocity.min.js"></script>
        <script type="text/javascript" src="include/js/vue.min.js"></script>
        <script type="text/javascript" src="include/js/ajax.min.js"></script>

        <script type="text/javascript" src="include/js/viewManager.min.js"></script>
        {/literal}
        <!--conferenceScripts-->
        <script type="text/javascript" src="include/js/components/paginationComponent.min.js"></script>
        <script type="text/javascript" src="include/js/components/progressComponent.min.js"></script>
        <!--taskCommentsScripts-->
        <!--autoTimetrackerScripts-->

        {literal}

        <script type="text/javascript" src="include/js/systemMessage.min.js"></script>
        <script type="text/javascript" src="include/js/jsval.min.js"></script>
        <script type="text/javascript">
            function _jsVal_Language() {
                this.err_enter = "{/literal}{#wrongfield#}{literal}";
                this.err_form = "{/literal}{#wrongfields#}{literal}";
                this.err_select = "{/literal}{#wrongselect#}{literal}";
            }
        </script>
        <script type="text/javascript" src="include/js/mycalendar.min.js"></script>
    {/literal}
    {/if}

    {if $jsload3 == "lightbox"}
        <link rel="stylesheet" href="templates/{$settings.template}/theme/{$settings.theme}/css/lytebox.css" type="text/css"/>
        <script type="text/javascript" src="include/js/lytebox.js"></script>
    {/if}
    <link rel="stylesheet" type="text/css" href="templates/{$settings.template}/theme/{$settings.theme}/css/style_main.css"/>
    <link rel="stylesheet" type="text/css" href="templates/{$settings.template}/theme/{$settings.theme}/css/style_helpers.css"/>


    {if $jsload1 == "tinymce"}
    {literal}
        <script type="text/javascript" src="include/js/tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript">
            //	theme_advanced_statusbar_location : "bottom",
            function initTinyMce() {
                tinyMCE.init({
                    mode: "textareas",
                    theme: "advanced",
                    language: "{/literal}{$locale}{literal}",
                    width: "450px",
                    height: "250px",
                    plugins: "inlinepopups,style,advimage,advlink,xhtmlxtras,safari,template",
                    theme_advanced_buttons1: "bold,italic,underline,|,fontsizeselect,forecolor,|,bullist,numlist,|,link,unlink,image",
                    theme_advanced_buttons2: "",
                    theme_advanced_buttons3: "",
                    theme_advanced_toolbar_location: "top",
                    theme_advanced_toolbar_align: "left",
                    theme_advanced_path: false,
                    extended_valid_elements: "a[name|href|target|title],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name],font[face|size|color|style],span[class|align|style]",
                    theme_advanced_statusbar_location: "bottom",
                    theme_advanced_resizing: true,
                    theme_advanced_resizing_use_cookie: false,
                    theme_advanced_resizing_min_width: "450px",
                    theme_advanced_resizing_max_width: "600px",
                    theme_advanced_resize_horizontal: false,
                    force_br_newlines: true,
                    cleanup: true,
                    cleanup_on_startup: true,
                    force_p_newlines: false,
                    convert_newlines_to_brs: false,
                    forced_root_block: false,
                    external_image_list_url: 'manageajax.php?action=jsonfiles&id={/literal}{$project.ID}{literal}',
                    setup: function (editor) {
                        editor.onChange.add(function () {
                            tinyMCE.triggerSave();
                        });
                    }

                });
            }
            window.addEventListener("load",initTinyMce);
        </script>
    {/literal}
    {/if}
</head>
<body>

{if $showheader != "no"}
    {include file="header_main.tpl"}
{/if}
