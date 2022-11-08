<?php

namespace Mia\Datafactory\Service;

class MiaDatafactoryService
{
    /**
     * URL de la API
     */
    const BASE_URL = 'https://feed.datafactory.la/';
    /**
     *
     * @var string
     */
    protected $pass = '';

    public function __construct($pass = '')
    {
        $this->pass = $pass;
    }

    protected function convertXmlToJson($xmlString)
    {
        //$xml= file_get_contents($url);
        $xmlString = str_replace(array("\n", "\r", "\t"), '', $xmlString);
        $xmlString = trim(str_replace('"', "'", $xmlString));
        $simpleXml = simplexml_load_string($xmlString);
        return json_encode($simpleXml);
    }
}