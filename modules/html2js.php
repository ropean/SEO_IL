<?php
if (!defined("ZBP_PATH")) {
    exit("Access denied");
}

$zbp->Load();
?>
var zbblog = {
    UEditor: '<?php echo $zbp->CheckPlugin("UEditor"); ?>',
    Neditor: '<?php echo $zbp->CheckPlugin("Neditor"); ?>',
    iceEditor: '<?php echo $zbp->CheckPlugin("iceEditor"); ?>',
    tinymce: '<?php echo $zbp->CheckPlugin("my_tinymce"); ?>',
    KindEditor: '<?php echo $zbp->CheckPlugin("KindEditor"); ?>',
    set:{title:'<?php echo $zbp->Config("SEO_IL")->title; ?>'}
}