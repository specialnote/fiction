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
            'fiction_detail' => [//单篇小说列表
                'wanyuzhiwang' => [
                    'fiction_name' => '万域之王',
                    'fiction_key' => 'wanyuzhiwang',
                    'fiction_author' => '逆苍天',
                    'fiction_introduction' => '太古时代，有擎天巨灵，身如星辰，翱翔宙宇。有身怀异血的各族大尊，破灭虚空，再造天地，有古炼气士，远渡星河，教化众生。不知因何原因，一个时代悄然终结，万域隔绝，太古巨擎一一销声匿迹。时隔多年，少年聂天，通过一滴鲜血，重回太古。',
                    'fiction_caption_url' => 'http://www.biquge.la/book/17514/',
                    'fiction_caption_list_rule' => '#list dl dd a',
                    'fiction_caption_list_example' => 'href="7002428.html"',//小说章节列表url示例
                    'fiction_caption_list_type' => 'current',//表示列表用a链接做的，链接的地址是相对当前地址，需要拼接
                    'fiction_detail_rule' => '#content',
                    'fiction_caption_list_status' => true,
                    'fiction_caption_detail_status' => true,
                ],
            ],
            'category_list' => [
                'xuanhuan' => [
                    'category_key' => 'xuanhuan',
                    'category_name' => '玄幻类小说',
                    'category_url' => [
                        'http://www.biquge.la/xiaoshuodaquan/',
                    ],//每个分类可以有多个url，防止分页
                    'category_list_rule' => '#main .novellist',//分类位置在页面中的选择器
                    'category_list_num' => 0,//表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a',//链接相对于分类的选择器
                    'category_list_link_type' => 'home',//表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'youxi' => [
                    'category_key' => 'youxi',
                    'category_name' => '游戏类小说',
                    'category_url' => [
                        'http://www.biquge.la/xiaoshuodaquan/',
                    ],//每个分类可以有多个url，防止分页
                    'category_list_rule' => '#main .novellist',//分类位置在页面中的选择器
                    'category_list_num' => 0,//表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a',//链接相对于分类的选择器
                    'category_list_link_type' => 'home',//表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'xiuzhen' => [
                    'category_key' => 'xiuzhen',
                    'category_name' => '修真类小说',
                    'category_url' => [
                        'http://www.biquge.la/xiaoshuodaquan/',
                    ],//每个分类可以有多个url，防止分页
                    'category_list_rule' => '#main .novellist',//分类位置在页面中的选择器
                    'category_list_num' => 0,//表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a',//链接相对于分类的选择器
                    'category_list_link_type' => 'home',//表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'doushi' => [
                    'category_key' => 'doushi',
                    'category_name' => '都市类小说',
                    'category_url' => [
                        'http://www.biquge.la/xiaoshuodaquan/',
                    ],//每个分类可以有多个url，防止分页
                    'category_list_rule' => '#main .novellist',//分类位置在页面中的选择器
                    'category_list_num' => 0,//表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a',//链接相对于分类的选择器
                    'category_list_link_type' => 'home',//表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'lishi' => [
                    'category_key' => 'lishi',
                    'category_name' => '历史类小说',
                    'category_url' => [
                        'http://www.biquge.la/xiaoshuodaquan/',
                    ],//每个分类可以有多个url，防止分页
                    'category_list_rule' => '#main .novellist',//分类位置在页面中的选择器
                    'category_list_num' => 0,//表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a',//链接相对于分类的选择器
                    'category_list_link_type' => 'home',//表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'kehuan' => [
                    'category_key' => 'kehuan',
                    'category_name' => '科幻类小说',
                    'category_url' => [
                        'http://www.biquge.la/xiaoshuodaquan/',
                    ],//每个分类可以有多个url，防止分页
                    'category_list_rule' => '#main .novellist',//分类位置在页面中的选择器
                    'category_list_num' => 0,//表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a',//链接相对于分类的选择器
                    'category_list_link_type' => 'home',//表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
            ]
        ]
    ],
];