$(function() {
    $("#msg").after('<a href="javascript:void(0)" onclick="open_ifarme()" style="color:#004eff"> [SEO_内部链接]</a>');
});

if (zbblog.UEditor) {
    var edit = [];
    edit['open'] = 'background: rgba(0, 0, 0, 0) url("' + bloghost + 'zb_users/plugin/SEO_IL/images/btn.bmp") no-repeat center / 16px 16px !important;';
    UE.registerUI('open', function(editor, uiname) {
        var btn = new UE.ui.Button({
            name: uiname,
            title: 'SEO_内部链接',
            cssRules: edit[uiname],
            onclick: function() {
                open_ifarme();
            }
        });
        return btn;
    });
    window.UEDITOR_CONFIG['toolbars'][0].push('open');
} else if (zbblog.iceEditor) {
    content.plugin({
        menu: 'SEO_内部链接',
        name: 'push_edit',
        click: function() {
            open_ifarme();
        }
    });
    content.create();
}

function open_ifarme() {
    layer.open({
        type: 2,
        title: 'SEO_内部链接',
        closeBtn: 0,
        shadeClose: true,
        area: ['800px', '600px'],
        fixed: true, //不固定
        content: [bloghost + 'zb_users/plugin/SEO_IL/pages/main.html', 'no'],
        success: function(layero, index) {
            //layer.iframeAuto(index);
        }
    });
}

function push_edit(val) {
    if (zbblog.UEditor || zbblog.Neditor) {
        UE.getEditor('editor_content').execCommand('inserthtml', val);
    } else if (zbblog.iceEditor) {
        content.addValue(val);
    } else if (zbblog.tinymce) {
        tinyMCE.editors[0].selection.setContent(val);
    } else if (zbblog.KindEditor) {
        editor_api.editor.content.obj.insertHtml(val);
    } else {
        layer.alert('暂不支持的编辑器');
    }
}

function set() {
    layer.open({
        type: 2,
        title: '设置  (需刷新生效)',
        closeBtn: 0,
        shadeClose: true,
        area: ['550px', 'auto'],
        fixed: false, //不固定
        id: 'setForm',
        content: [bloghost + 'zb_users/plugin/SEO_IL/pages/setting.html', 'no'],
        success: function(layero, index) {
            layer.iframeAuto(index);
        }
    });
}