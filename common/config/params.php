<?php
return [
    'company_name' => '万花筒',//网站名称
    'frontend_host' => 'fiction.com/',//前台域名
    'fiction_configure_cache_expire_time' => 60*60*24,//每个小说配置信息（小说名、作者、url、简介）缓存时间
    'fiction_chapter_list_cache_expire_time' => 60*60*24,//每个小说章节的缓存时间
    'category_fiction_list_cache_expire_time' => 60*60*24*7,//分类中小说列表的缓存时间
];
