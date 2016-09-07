<?php

return [
    'company_name' => '果趣网', //网站名称
    'frontend_host' => 'f.specialnote.cn', //前台域名
    //指定分类的小说列表缓存 key ：'ditch_' . $dk . '_category_' . $ck . '_list'
    'category_fiction_list_cache_expire_time' => 60 * 60 * 24 * 3, //分类的小说缓存时间
    //指定小说的信息 key ： 'ditch_'.$dk.'_fiction_'.$fiction_key.'_config',
    'fiction_configure_cache_expire_time' => 60 * 60 * 24 * 7, //每个小说配置信息（小说名、作者、url、简介）缓存时间
    //指定小说的章节列表 key ：'ditch_' . $dk . '_fiction_detail' . $fiction_key . '_fiction_list'
    'fiction_chapter_list_cache_expire_time' => 60 * 60 * 24 * 7, //每个小说章节的缓存时间
    //指定小说的内容 key : 'ditch_'.$dk.'_fiction_'.$fk.'_detail'
    'fiction_chapter_detail' => 60 * 60 * 24 * 7, //小说详情缓存时间
];
