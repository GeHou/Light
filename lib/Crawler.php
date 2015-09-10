<?php

class Crawler {

    private $html = null;
    private $dom = null;
    private $domxpath = null;

    public static function init()
    {
        return new Crawler();
    }

    public function getContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FILE, fopen('php://stdout', 'w'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $this->html = curl_exec($ch);
        curl_close($ch);
        return $this;
    }

    public function addHtmlContent($content, $charset = 'UTF-8')
    {
        $this->dom = new DOMDocument();
        $this->dom->validateOnParse = true;
        if (function_exists('mb_convert_encoding')) {
            $hasError = false;
            set_error_handler(function () use (&$hasError) {
                $hasError = true;
            });
            $tmpContent = @mb_convert_encoding($content, 'HTML-ENTITIES', $charset);

            restore_error_handler();

            if (!$hasError) {
                $content = $tmpContent;
            }
        }

        if ('' !== trim($content)) {
            @$this->dom->loadHTML($content);
        }
        return $this;
    }

    public function createDOMXPath()
    {
        $this->addHtmlContent($this->html);
        $this->domxpath = new DOMXPath($this->dom);
        return $this;
    }

    public function filterXPath($xpath)
    {
        $elements = $this->domxpath->query($xpath);
        foreach ($elements as $i => $e) {
            $nodeName = $elements->item($i)->nodeName;
            $content = trim($this->dom->saveHtml($e));
            $elements->item($i)->html = $content;
            $elements->item($i)->innerHtml = preg_replace(array("#^<{$nodeName}.*>#isU", "#</{$nodeName}>$#isU"), array('', ''), $content);
        }
        return $elements;
    }

}