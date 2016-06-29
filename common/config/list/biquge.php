<?php
/*
 * 笔趣阁小说站 http://www.biquge.la
 *
 */
return [
    'ditch' => [
        'biquge' => [
            'ditch_name' => '笔趣阁',
            'ditch_key' => 'biquge',
            'ditch_domain' => 'http://www.biquge.la',
            'fiction_list' => [
                'wanyuzhiwang' => [
                    'fiction_name' => '万域之王',
                    'fiction_key' => 'wanyuzhiwang',
                    'fiction_author' => '逆苍天',
                    'fiction_introduction' => '太古时代，有擎天巨灵，身如星辰，翱翔宙宇。有身怀异血的各族大尊，破灭虚空，再造天地，有古炼气士，远渡星河，教化众生。不知因何原因，一个时代悄然终结，万域隔绝，太古巨擎一一销声匿迹。时隔多年，少年聂天，通过一滴鲜血，重回太古。',
                    'fiction_url' => 'http://www.biquge.la/book/17514/',
                    'fiction_list_rule' => '#list dl dd a',
                    'fiction_list_example' => 'href="7002428.html"',
                    'fiction_list_type' => 'current',//表示列表用a链接做的，链接的地址是相对当前地址，需要拼接
                    'fiction_detail_rule' => '#content',
                    'fiction_list_status' => true,
                    'fiction_detail_status' => true,
                ],
            ]
        ]
    ],
];