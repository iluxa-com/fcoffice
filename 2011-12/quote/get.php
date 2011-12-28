<?php
    $url = 'http://www.izz.cc/rss';
    $rss = file_get_contents($url);
    $rss = str_replace(array('<![CDATA[','[...]]]>'),'',$rss);
    $xml = new SimpleXMLElement($rss);
    $channel = $xml->channel;
    $token =array();
    $token[0] = '欢迎关注 微语录官方腾讯微博';
    $token[1] = '推荐您阅读与本文相关的微语录';
    $file = file_get_contents('data.xml');
    $xml2 = new SimpleXMLElement($file);
    foreach($channel->item as $item) {
        $title = $item->title;
        if(false !== strpos($file,"<title>{$title}</title>")) continue; //不写入重复数据
        foreach($token as $t) {
            $pos = strpos($item->description,$t);
            if(false !== $pos) break;
        }
        $pos = ($pos===false) ? strlen($item->description) : $pos;
        $description = substr($item->description,0,$pos);
        $item2 = $xml2->addChild('item');
        $item2->addChild('title',$title);
        $item2->addChild('description',$description);
    }
    file_put_contents('data.xml',$xml2->asXML());
