<?php

class MfwController extends BaseController {

    public function action_test()
    {
        $xPath = Crawler::init()->getContent('http://www.mafengwo.cn/poi/14795.html')->createDOMXPath();
        $elements = $xPath->filterXPath('//*[@class="intro"]/dt/p');
        foreach ($elements as $e) {
            echo($e->innerHtml);
        }
    }

    public function action_mdd()
    {
        $xPath = Crawler::init()->getContent('http://www.mafengwo.cn/mdd/')->createDOMXPath();
        // mdd
        $mddName = $xPath->filterXPath('//*[@class="bd bd-china"]/dl/dd/ul/li/a');
        $mddMfwId = $xPath->filterXPath('//*[@class="bd bd-china"]/dl/dd/ul/li/a/@href');
        $mddArr = array();
        foreach ($mddName as $i => $e) {
            $mddArr[$i]['mfw_id'] = str_replace(array('/travel-scenic-spot/mafengwo/', '.html'), array('', ''), trim($mddMfwId->item($i)->nodeValue));
            $mddArr[$i]['name'] = trim($e->nodeValue);
            DB::query('INSERT INTO mdd(mfw_id) VALUES (?) ON DUPLICATE KEY UPDATE name=?', array($mddArr[$i]['mfw_id'], $mddArr[$i]['name']));
        }
        // DB::table('mdd')->insert($mddArr);
    }

    public function action_mddinfo()
    {
        $xPath = Crawler::init()->getContent('http://www.mafengwo.cn/baike/info-10189.html')->createDOMXPath();
        $title = $xPath->filterXPath('//*[@class="m-subTit"]/h2');
        $txt = $xPath->filterXPath('//*[@class="m-txt"]');
        $content = $txt->item(0)->innerHtml;
        echo($content);
    }

}