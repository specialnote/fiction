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
            'fiction_rule' => [
                'fiction_caption_list_rule' => [
                    'fiction_title_rule' => '#maininfo h1',//小说名成规则
                    'fiction_title_rule_num' => 0,//小说名dom顺序，表示第几个相应的rule
                    'fiction_author_rule' => '#info p',//小说作者规则
                    'fiction_author_rule_num' => 0,//作者dom顺序
                    'fiction_description_rule' => '#intro',//小说描述规则
                    'fiction_description_rule_num' => 0,//小说描述dom顺序
                    'fiction_caption_list_rule' => '#list dl dd a',//小说章节列表规则
                    'fiction_caption_list_example' => 'href="7002428.html"',//小说章节列表url示例
                    'fiction_caption_list_type' => 'current',//表示列表用a链接做的，链接的地址是相对当前地址，需要拼接
                ],//指定来源的小说章节列表页面采集规则
                'fiction_detail_rule' => [
                    'fiction_detail_rule' => '#content',//销售详情内容
                ]//指定来源的小说详情页面采集规则
            ],//指定来源的采集规则
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