<?php
require "../../../../zb_system/function/c_system_base.php";
$zbp->load();
if (!$zbp->CheckRights("root")) {
    die("禁止访问");
}
$type = trim(GetVars("type", "GET"));
switch ($type) {
    case "search":
        getArticles();
        break;
    default:
        echo json_encode([
            "code" => 0,
            "msg" => "参数错误",
            "count" => 0,
            "data" => [],
        ]);
        break;
}

function getArticles()
{
    global $zbp;
    $mode = GetVars("mode", "GET");
    $limit = GetVars("limit", "GET") ? (int) GetVars("limit", "GET") : 10;
    $cid = (int) GetVars("cid", "GET");

    $c = new ZBlog_Article();

    if ($mode == "on") {
        $keyword = trim(GetVars("keyword", "GET"));

        if ($keyword == "") {
            echo json_encode([
                "code" => 0,
                "msg" => "请输入搜索词",
                "count" => 0,
                "data" => [],
            ]);
        }

        $on = (int) GetVars("w", "GET");

        if ($cid != 0) {
            $c->w[] = ["=", "log_CateID", $cid];
        }

        switch ($on) {
            case 1:
                $c->w[] = ["search", "log_Title", $keyword];
                break;
            case 2:
                $c->w[] = ["search", "log_Content", $keyword];
                break;
            default:
                $c->w[] = [
                    "search",
                    "log_Title",
                    "log_Content",
                    "log_Intro",
                    $keyword,
                ];
                break;
        }

        $c->w[] = ["=", "log_Status", 0];
        $c->o = ["log_PostTime" => "DESC"];
    } else {
        $ff = GetVars("ff", "GET") ? (int) GetVars("ff", "GET") : 2;

        if ($cid != 0) {
            $c->w[] = ["=", "log_CateID", $cid];
        }

        switch ($ff) {
            case 1:
                $c->o = ["log_PostTime" => "DESC"];
                break;
            case 3:
                $c->o = ["log_CommNums" => "DESC"];
                break;
            case 4:
                $c->o = [
                    mt_rand(0, 1) ? "log_ViewNums" : "log_PostTime" => mt_rand(
                        0,
                        1
                    )
                        ? "DESC"
                        : "ASC",
                ];
                // $articles = $c->GetList(1000);
                // shuffle($articles);
                // $posts = count($articles) > limit ? array_chunk($articles, limit)[0] : $articles;
                // foreach ($posts as $k => $post) {
                //     $artArr[] = array(
                //         'id' => $post->ID,
                //         'title' => $post->Title,
                //         'url' => $post->Url
                //     );
                // }
                // echo json_encode(array('code' => 0, 'msg' => '', 'count' => count($artArr), 'data' => $artArr));
                // die();
                break;
            default:
                $c->o = ["log_ViewNums" => "DESC"];
                break;
        }
    }

    $list = $c->GetList($limit);
    $data = [];
    foreach ($list as $k => $arr) {
        $data[] = [
            "id" => $arr->ID,
            "title" => $arr->Title,
            "cate" => $arr->Category->Name,
            "url" => $arr->Url,
        ];
    }
    echo json_encode([
        "code" => 0,
        "msg" => "",
        "count" => $c->count,
        "data" => $data,
    ]);
}

class ZBlog_Article
{
    public $p;
    public $count;
    public $w = [];
    public $o = [];

    public function GetList($limit = 10)
    {
        global $zbp;
        $page = GetVars("page", "GET") ?: 1;
        $p = new Pagebar("", false);
        $p->PageCount = $limit ?: $zbp->option["ZC_DISPLAY_COUNT"];
        $p->PageNow = (int) $page;
        $p->PageBarCount = $zbp->pagebarcount;
        foreach ($_REQUEST as $key => $value) {
            if (!is_array($value)) {
                $p->UrlRule->Rules["{%$value%}"] = $value;
            }
        }
        $this->p = $p;
        $sql = $zbp->db->sql->Select(
            $zbp->table["Post"],
            "*",
            $this->w,
            $this->o,
            [($p->PageNow - 1) * $p->PageCount, $p->PageCount],
            ["pagebar" => $p]
        );
        $res = $zbp->GetListType("Post", $sql);
        $this->count = $p->Count;
        return $res;
    }
}
