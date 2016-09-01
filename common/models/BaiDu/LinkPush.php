<?php

namespace common\models\BaiDu;


class LinkPush
{
    const BAI_DU_LINK_PUSH_API = 'http://data.zz.baidu.com/urls?site=f.specialnote.cn&token=uj6iUKAR98oK9axi';

    public static function push(array $urls)
    {
        $ch = curl_init();
        $options =  array(
            CURLOPT_URL => self::BAI_DU_LINK_PUSH_API,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        return $result;
    }
}