<?php

/**
 *http://tinywebdb.appinventor.space
 *蜗牛(2020)
 *免费微型数据库
 *示例
 *include ('TinyWebDB.php');
 *$db = new TinyWebDB("share","everyone");
 *$r = $db->count();
 *var_dump($r);
 */
class TinyWebDB
{
    private $user;
    private $secret;
    // 用户名（user） 密钥（secret）
    public function __construct($uesr, $secret)
    {
        $this->user   = $uesr;
        $this->secret = $secret;
    }

    //计数（count）
    //无其他参数，返回保存变量的个数
    public function count()
    {
        $d = array(
            'action' => 'count',
            'tag'    => 'aaa',
        );
        return $this->post($d);
    }

    //可选参数：no=起始编号、count=变量个数、tag=变量名包含的字符、
    //type=tag/value/both；no默认为1，count默认为1，tag默认为空，
    //type默认为both，表示返回tag和value，最多返回100条数据
    public function search($tag = '', $count = 1, $no = 1, $type = 'both')
    {
        $d = array(
            'action' => 'search',
            'count'  => $count,
            'no'     => $no,
            'tag'    => $tag,
            'type'   => $type,
        );
        return $this->post($d);
    }

    //更新（update）
    //必填参数：tag=变量名、value=变量值
    public function update($tag, $value)
    {
        $d = array(
            'action' => 'update',
            'tag'    => $tag,
            'value'  => $value,
        );
        return $this->post($d);
    }

    //读取（get）
    //必填参数：tag=变量名
    public function get($tag)
    {
        $d = array(
            'action' => 'get',
            'tag'    => $tag,
        );
        return $this->post($d);
    }
    //删除（delete）
    //必填参数：tag=变量名
    public function delete($tag)
    {
        $d = array(
            'action' => 'delete',
            'tag'    => $tag,
        );
        return $this->post($d);
    }

    //请求类型POST
    public function post($data)
    {
        $url = 'http://tinywebdb.appinventor.space/api';

        $data["user"]   = $this->user;
        $data["secret"] = $this->secret;
        $curl           = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output);
    }
}
