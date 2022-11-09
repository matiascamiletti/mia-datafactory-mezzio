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
    /**
     * Determina si solo trae los canales de deportes
     * @var boolean
     */
    protected $onlySports = true;

    public function __construct($pass = '')
    {
        $this->pass = $pass;
    }
    /**
     * Devuelve listado de canales actualizados desde la fecha indicada
     *
     * @param string $fromDate YYYYMMDD
     * @param string $fromHour HHMMSS
     * @return object
     */
    public function getUpdatedChannels($fromDate = '', $fromHour = '')
    {
        return $this->call('desde=' . $fromDate . '&hora=' . $fromHour);
    }
    /**
     * Obtiene el contenido del canal
     *
     * @param string $channel
     * @return object
     */
    public function getChannel($channel)
    {
        return $this->call('canal=' . $channel);
    }
    /**
     * Recibe parametros en la query y devuelva la respuesta
     *
     * @param string $params
     * @return object
     */
    protected function call($params)
    {
        if($this->onlySports){
            $xmlString = file_get_contents(self::BASE_URL . '?ppaass=' . $this->pass . '&solo1=deportes&' . $params);
        } else {
            $xmlString = file_get_contents(self::BASE_URL . '?ppaass=' . $this->pass . '&' . $params);
        }
        
        return $this->convertXmlToJson($xmlString);
    }
    /**
     * Recibe el string de un XML y lo convierte a Objeto
     * @param string $xmlString
     * @return object
     */
    protected function convertXmlToJson($xmlString)
    {
        //$xml= file_get_contents($url);
        $xmlString = $this->removeContentInNode($xmlString, 'local');
        $xmlString = $this->removeContentInNode($xmlString, 'visitante');

        $xmlString = str_replace(array("\n", "\r", "\t"), '', $xmlString);
        $xmlString = trim(str_replace('"', "'", $xmlString));
        $simpleXml = simplexml_load_string($xmlString);
        $jsonString = json_encode($simpleXml);
        return json_decode($jsonString);
    }

    protected function removeContentInNode($xmlString, $nodeName)
    {
        $totalNodes = str_word_count('<' . $nodeName);
        $lastIndex = 0;
        for ($i=0; $i < $totalNodes; $i++) { 
            $indexStart = stripos($xmlString, '<' . $nodeName, $lastIndex);
            $indexStartContent = stripos($xmlString, '>', $indexStart);
            $indexEnd = stripos($xmlString, '</' . $nodeName, $indexStartContent);
            $xmlString = substr($xmlString, 0, $indexStartContent+1) . substr($xmlString, $indexEnd);
            $lastIndex = $indexEnd;
        }

        return $xmlString;
    }
}