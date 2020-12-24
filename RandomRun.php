<?php

include 'TinyWebDB.php';
date_default_timezone_set("Asia/Shanghai");
class RandomRun extends TinyWebDB
{
    private $StartH; //开始时间 小时
    private $EndH; //截止时间 小时
    private $Tag; //字段名
    private $SCkey; //酱紫key
    public function __construct($conf)
    {
        $this->StartH = $conf['StartH'];
        $this->EndH   = $conf['EndH'];
        $this->Tag    = $conf['DBtag'];
        $this->SCkey  = $conf['SCkey'];
        parent::__construct($conf['DBuesr'], $conf['DBsecret']);
    }

    public function isRun()
    {
        $tag = $this->Tag;
        $r   = $this->get($tag);
        if ($r->$tag) {
            $runTime = intval($r->$tag);
            if (time() >= $runTime) {
                $nextTime = $this->GetNextTime();
                $r        = $this->update($tag, $nextTime);
                if ($r->status == 'success') {
                    $t = date('Y-m-d H:m:s', $nextTime);
                    echo "开始运行,随机下次运行时间:{$t} \n";
                    $i = rand(1, 10);
                    sleep($i);
                    return  true;
                } else {
                    echo "数据库写入失败,请检查用户名和秘钥是否正确 \n";
                }
            } else {
                $t = date('Y-m-d H:m:s', $runTime);
                echo "未到运行时间,下次运行时间:{$t} \n";
            }
        } else {
            echo "数据库读取失败,请检查用户名和秘钥是否正确 \n";
        }
        return false;
    }

    public function GetNextTime()
    {
        $i          = strtotime("+1 day");
        $y          = date("Y", $i);
        $m          = date("m", $i);
        $d          = date("d", $i);
        $start_time = mktime($this->StartH, 0, 0, $m, $d, $y);
        $end_time   = mktime($this->EndH, 0, 0, $m, $d, $y);
        return rand($start_time, $end_time);
    }

    //$key = 你的酱key 用于微信消息推送
    public function SCsend($desp,$text)
    {
        if (strlen($this->SCkey) < 10) {
            echo "未设置酱key,跳过信息推送服务 \n";
            return false;
        }
        $postdata = http_build_query(array('text' => $text, 'desp' => $desp));

        $opts = array('http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata,
        ),
        );
        $context       = stream_context_create($opts);
        return $result = file_get_contents('https://sc.ftqq.com/' . $this->SCkey . '.send', false, $context);

    }

}
