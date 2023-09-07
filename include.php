<?php
#注册插件
RegisterPlugin("SEO_IL", "ActivePlugin_SEL_IL");

function ActivePlugin_SEL_IL()
{
    Add_Filter_Plugin("Filter_Plugin_Admin_Js_Add", "SEL_IL_Admin_Js_Add");
    Add_Filter_Plugin("Filter_Plugin_Edit_Begin", "SEL_IL_Edit_Begin");
    Add_Filter_Plugin("Filter_Plugin_Cmd_Ajax", "SEL_IL_Cmd_Ajax");
}
function SEL_IL_Edit_Begin()
{
    global $zbp;
    if ($zbp->CheckRights("root")) {
        echo '
      <script src="' .
            $zbp->host .
            'zb_users/plugin/SEO_IL/layui/layui.js" type="text/javascript"></script>
      <script src="' .
            $zbp->host .
            'zb_users/plugin/SEO_IL/scripts/editor.js" type="text/javascript"></script>';
    }
}
function SEL_IL_Admin_Js_Add()
{
    global $zbp;
    include ZBP_PATH . "zb_users/plugin/SEO_IL/modules/html2js.php";
}
function SEL_IL_Cmd_Ajax($src)
{
    global $zbp;
    if ($src == "setForm") {
        $zbp->Config("SEO_IL")->title = urldecode(GetVars("title", "POST"));
        $zbp->SaveConfig("SEO_IL");
        echo json_encode(["code" => 1]);
    }
}
function InstallPlugin_SEL_IL()
{
}
function UninstallPlugin_SEL_IL()
{
}
