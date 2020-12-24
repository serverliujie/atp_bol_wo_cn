<?php

// 新建函数 配置方案推荐
// 函数名称 : atp_bol_wo_cn
// 运行环境 : php5.6
// 内存     : 128M
// 超时时间 : 100 - 900  //根据号多少来设置最低值 N * 30

// 创建触发器 配置推荐
// 定时任务名称 ：  随意
// 触发周期     :   自定义触发周期
// Cron表达式   :   0 */3 9-13 * * * *
// 其中 时 9-13 与下面的配置保持一致即可

//配置文件
$Config = array(
    //------------------脚本每天运行的时间范围
    //建议和触发器设置同步
    //例如 9  13  每天的 9点到13点 随机一个时间运行一次
    //触发器则这样设置自定义触发周期  0 */3 9-22 * * * *
    'StartH'   => 9, //运行开始时间 0-24小时
    'EndH'     => 13, //截止开始时间 0-24小时

    //------------微型数据库设置---------------------
    //建议自己注册一个 默认用公共账号
    //注册地址 http://tinywebdb.appinventor.space
    'DBuesr'   => 'share', // 用户名（user）
    'DBsecret' => 'everyone', //密钥（secret）
    'DBtag'    => 'hfg01', //字段名 必须唯一不能重复 ，不可用默认名 必须更改

    //------------酱紫key---------------------
    //用于推送微信消息 为空则不推送信息
    //注册获取key地址http://sc.ftqq.com/?c=code
    'SCkey' => '',

    //------------cookies---------------------
    //要执行的cookie信息一行一个
    //微信 小程序 联通话费购 首页抽奖 cookie
    'Cookies' => array(
        //别名 ：一个能代表当前cookie名称 不一定要手机号 唯一性
        // '别名' => 'cookie'
        '155xxxxxxx' => 'atpAuthToken=xxxxxxx',
        '156xxxxxxx' => 'atpAuthToken=xxxxxxx',

    )
);

return $Config;