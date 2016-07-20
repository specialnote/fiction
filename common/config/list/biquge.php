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
            'ditch_home_url' => 'http://www.biquge.la',
            'ditch_rule' => [
                'title_rule' => '#maininfo h1', //小说名成规则
                'title_rule_num' => 0, //小说名dom顺序，表示第几个相应的rule
                'author_rule' => '#info p', //小说作者规则
                'author_rule_num' => 0, //作者dom顺序
                'description_rule' => '#intro', //小说描述规则
                'description_rule_num' => 0, //小说描述dom顺序
                'chapter_list_rule' => '#list dl dd a', //小说章节列表规则
                'chapter_list_example' => 'href="7002428.html"', //小说章节列表url示例
                'chapter_list_type' => 'current', //表示列表用a链接做的，链接的地址是相对当前地址，需要拼接
                'fiction_detail_rule' => '#content', //销售详情内容
            ], //指定来源的采集规则
            'category_list' => [
                'xuanhuan' => [
                    'category_key' => 'xuanhuan',
                    'category_name' => '玄幻类小说',
                    'category_url' => 'http://www.biquge.la/xiaoshuodaquan/',
                    'category_list_rule' => '#main .novellist', //分类位置在页面中的选择器
                    'category_list_num' => 0, //表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a', //链接相对于分类的选择器
                    'category_list_link_type' => 'home', //表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'xiuzhen' => [
                    'category_key' => 'xiuzhen',
                    'category_name' => '修真类小说',
                    'category_url' => 'http://www.biquge.la/xiaoshuodaquan/',
                    'category_list_rule' => '#main .novellist', //分类位置在页面中的选择器
                    'category_list_num' => 1, //表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a', //链接相对于分类的选择器
                    'category_list_link_type' => 'home', //表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'doushi' => [
                    'category_key' => 'doushi',
                    'category_name' => '都市类小说',
                    'category_url' => 'http://www.biquge.la/xiaoshuodaquan/',
                    'category_list_rule' => '#main .novellist', //分类位置在页面中的选择器
                    'category_list_num' => 2, //表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a', //链接相对于分类的选择器
                    'category_list_link_type' => 'home', //表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'lishi' => [
                    'category_key' => 'lishi',
                    'category_name' => '历史类小说',
                    'category_url' => 'http://www.biquge.la/xiaoshuodaquan/',
                    'category_list_rule' => '#main .novellist', //分类位置在页面中的选择器
                    'category_list_num' => 3, //表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a', //链接相对于分类的选择器
                    'category_list_link_type' => 'home', //表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'youxi' => [
                    'category_key' => 'youxi',
                    'category_name' => '游戏类小说',
                    'category_url' => 'http://www.biquge.la/xiaoshuodaquan/',
                    'category_list_rule' => '#main .novellist', //分类位置在页面中的选择器
                    'category_list_num' => 4, //表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a', //链接相对于分类的选择器
                    'category_list_link_type' => 'home', //表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
                'kehuan' => [
                    'category_key' => 'kehuan',
                    'category_name' => '科幻类小说',
                    'category_url' => 'http://www.biquge.la/xiaoshuodaquan/',
                    'category_list_rule' => '#main .novellist', //分类位置在页面中的选择器
                    'category_list_num' => 5, //表示该分类是在页面中指定选择器的第一个（0表示第一个）
                    'list_link_rule' => 'ul li a', //链接相对于分类的选择器
                    'category_list_link_type' => 'home', //表示列表用a链接做的，链接的地址是相对域名，需要拼接
                ],
            ],
        ],
    ],
];
