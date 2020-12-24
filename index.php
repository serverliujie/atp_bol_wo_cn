<?php
/**
 * @Author: 联通话费购抽奖
 * @Date:   2020-12-22 11:45:50
 * @Last Modified time: 2020-12-22 14:38:01
 * 使用前请先修改 Config.php 配置文件
 * 该代码仅供学习研究用途，请勿非法使用该代码
 * 使用该代码产生的一切后果由使用者自行承担
 * 作者不承担任何责任
 */
include 'RandomRun.php';
include 'Config.php';

function main_handler($event, $context)
{
    global $Config;
    $db = new RandomRun($Config);
    if ($db->isRun()) {
        $url = 'https://atp.bol.wo.cn/atpapi/act/lottery/start/v1/actPath/ACT202009101956022770009xRb2UQ/0';
        foreach ($Config['Cookies'] as $k => $v) {
            $result = '';
            $do     = true;
            while ($do) {
                $do      = false;
                $content = get($url, $v);
                $r       = json_decode($content);
                if (isset($r->code)) {
                    if ($r->data) {
                        $do = true;
                        $result .= '|' . $r->data->prizeName;
                        echo "{$k} : {$r->data->prizeName} \n";
                    } else {
                        echo "{$k} : {$r->message} \n";
                    }
                } else {
                    $db->SCsend("cookie无效或已失效,请更新", '话费购抽奖:'.$k);
                    echo "{$k}:cookie无效或已失效,请更新 \n";
                }
                sleep(rand(3,10));
            }
            if (strlen($result) > 2) {
                $db->SCsend($result, '话费购抽奖'.$k);
            }
        }
        return 'ok';
    }else{
        return '未到运行时间';
    }

}

function get($url, $cookie)
{
    $head = array(
        'Host: atp.bol.wo.cn',
        'Accept-Encoding: gzip, deflate, br',
        'Accept: application/json, text/plain, */*',
        'Connection: keep-alive',
        'Cookie: ' . $cookie,
        'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 13_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 MicroMessenger/7.0.18(0x1700122d) NetType/WIFI Language/zh_CN miniProgram',
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_HTTPGET, 1);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $tmpInfo = curl_exec($curl);
    curl_close($curl);
    return $tmpInfo;
}
