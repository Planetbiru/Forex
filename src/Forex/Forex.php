<?php

namespace Planetbiru\Forex;

class Forex
{
    /**
     * Currency rates
     *
     * @var array
     */
    private $rates = array();

    /**
     * Daily rate URL
     *
     * @var string
     */
    private $rateDaily = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
    
    /**
     * Constructor
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        if(!isset($url))
        {
            $url = $this->rateDaily;
        }
        $this->rates = array();
        if($url != null)
        {
            $this->load($url);
        }
    }

    /**
     * Load currency rate
     *
     * @param string $url
     * @return self
     */
    public function load($url)
    {
        $content = file_get_contents($url);
        $xml =  simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA );     
        foreach($xml->Cube->Cube->Cube as $rate)
        {
            $key = (string) $rate['currency'];
            $value = floatval($rate['rate']);        
            $this->set($key, $value);
        } 
        return $this;
    }

    /**
     * Cet currency rate
     *
     * @param string $currency
     * @param float $rate
     * @return self
     */
    public function set($currency, $rate)
    {
        $this->rates[$currency] = $rate;
        return $this;
    }

    /**
     * Convert currency value
     *
     * @param string $from
     * @param string $to
     * @return float
     */
    public function convert($from, $to)
    {
        $to = str_replace('.', '.', $this->rates[$to]) * 1;
        $from = str_replace('.', '.', $this->rates[$from]) * 1;
        return $to/$from;
    }



    /**
     * Get currency rates
     *
     * @return  array
     */ 
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Set currency rates
     *
     * @param  array  $rates  Currency rates
     *
     * @return  self
     */ 
    public function setRates($rates)
    {
        $this->rates = $rates;

        return $this;
    }
}
