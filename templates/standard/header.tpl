{config_load file='lng.conf' section = "strings" scope="global" }
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>{$title} @ {$settings.name}</title>
    <link rel="shortcut icon" href="templates/{$settings.template}/theme/{$settings.theme}/images/favicon.ico"
          type="image/x-icon"/>
    {if $treeView == "treeView" and $loggedin}
        <script type="text/javascript" src="include/js/dtree.min.js"></script>
    {/if}
    {if $jsload|default == "ajax"}
    {literal}
        <script type="text/javascript" src="include/js/velocity.min.js"></script>
        <script type="text/javascript" src="include/js/vue.min.js"></script>
        <script type="text/javascript" src="include/js/ajax.min.js"></script>
        <script type="text/javascript" src="include/js/viewManager.js"></script>
    {/literal}
        <script type="text/javascript" src="include/js/components/paginationComponent.min.js"></script>
        <script type="text/javascript" src="include/js/components/progressComponent.min.js"></script>
        <!--conferenceScripts-->
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
        <script type="text/javascript" src="include/js/lytebox.js"></script>
    {/if}
    <link rel="stylesheet" type="text/css"
          href="templates/{$settings.template}/theme/{$settings.theme}/css/{$settings.theme}.css"/>

    {if $jsload1 == "tinymce"}
    {literal}
        <script type="text/javascript" src="include/js/tinymce/js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            //	theme_advanced_statusbar_location : "bottom",
            tinyMCE = tinymce;
            function initTinyMce() {
                tinymce.init({
                    selector: "textarea",
                    theme: "modern",
                    language: "{/literal}{$locale}{literal}",
                    width: "500px",
                    height: "250px",
                    menubar: false,
                    toolbar: ["bold italic underline | fontsizeselect forecolor | alignleft aligncenter | bullist numlist | link image",
                        ""],
                    plugins: 'autolink link image lists code textcolor colorpicker',
                    branding: false,
                    cleanup: true,
                    cleanup_on_startup: true,
                    force_p_newlines: false,
                    force_br_newlines: true,
                    convert_newlines_to_brs: false,
                    forced_root_block: false,
                    setup: function (editor) {
                        editor.on("keyDown", function () {
                            editor.save();
                            var textarea = document.getElementsByName(editor.id)[0];
                            textarea.value = editor.getContent();
                            console.log(textarea.value);
                        });
                    }

                });
            }
            window.addEventListener("load", initTinyMce);
        </script>
    {/literal}
    {/if}
</head>
<body>

{if $showheader != "no"}
    {include file="header_main.tpl"}
{/if}
