<?php

namespace Planetbiru\Forex;

/**
 * Class Forex
 *
 * Handles fetching and parsing currency exchange rates from the European Central Bank (ECB).
 * Provides methods to convert currencies based on the fetched rates.
 *
 * @package Planetbiru\Forex
 */
class Forex
{
    /**
     * @var array Associative array storing currency codes and their rates relative to EUR.
     */
    protected $currencyRates = [];

    /**
     * @var string The base currency code (always 'EUR' for ECB data).
     */
    protected $baseCurrency = 'EUR';

    /**
     * @var string|null The date of the currency rates (YYYY-MM-DD).
     */
    protected $date = null;

    /**
     * @var string|null Cache for the history XML content.
     */
    protected $historyContent = null;

    // ECB Endpoints
    /**
     * URL for the daily reference rates.
     */
    const ECB_DAILY   = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
    /**
     * URL for the reference rates of the last 90 days.
     */
    const ECB_90DAYS  = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist-90d.xml";
    /**
     * URL for the historical reference rates (since 1999).
     */
    const ECB_HISTORY= "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml";

    /**
     * Forex constructor.
     *
     * @param string|null $url Optional URL to load data from immediately upon instantiation.
     * @throws \Exception If loading fails.
     */
    public function __construct($url = null)
    {
        if ($url !== null) {
            $this->load($url);
        }
    }

    /* =====================================================
     * CORE LOADER
     * ===================================================== */

    /**
     * Loads currency rates from a given ECB XML URL.
     *
     * @param string $url The URL of the XML file.
     * @throws \Exception If the content cannot be loaded or the XML is invalid.
     * @return void
     */
    public function load($url)
    {
        $content = $this->fetchUrl($url);

        if ($content === false) {
            throw new \Exception("Cannot load forex data");
        }

        $this->parseContent($content);
    }

    /**
     * Fetches content from a URL using cURL.
     *
     * @param string $url The URL to fetch.
     * @return string|false The content or false on failure.
     */
    protected function fetchUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $content = curl_exec($ch);

        return $content;
    }

    /**
     * Parses XML content and sets currency rates.
     *
     * @param string $content XML content.
     * @throws \Exception If XML is invalid.
     */
    protected function parseContent($content)
    {
        $xml = simplexml_load_string($content);

        if ($xml === false) {
            throw new \Exception("Invalid XML format");
        }

        // reset data
        $this->currencyRates = [];
        $this->currencyRates[$this->baseCurrency] = 1;

        $cube = $xml->Cube->Cube;
        $this->date = (string)$cube['time'];

        foreach ($cube->Cube as $rate) {
            $this->set(
                (string)$rate['currency'],
                (float)$rate['rate']
            );
        }
    }

    /* =====================================================
     * OPTIMIZED ECB METHODS
     * ===================================================== */

    /**
     * Loads historical data into memory, using a local cache file if available and valid (24 hours).
     *
     * @return void
     * @throws \Exception
     */
    protected function loadHistoryData()
    {
        if ($this->historyContent !== null) {
            return;
        }

        $cacheFile = sys_get_temp_dir() . '/eurofxref-hist.xml';
        $cacheDuration = 24 * 3600; // 24 hours

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheDuration)) {
            $this->historyContent = file_get_contents($cacheFile);
        }

        if (!$this->historyContent) {
            $this->historyContent = $this->fetchUrl(self::ECB_HISTORY);
            if ($this->historyContent === false) {
                throw new \Exception("Cannot load historical data");
            }
            file_put_contents($cacheFile, $this->historyContent);
        }
    }

    /**
     * Loads the latest daily reference rates.
     *
     * @return void
     * @throws \Exception
     */
    public function loadDaily()
    {
        return $this->load(self::ECB_DAILY);
    }

    /**
     * Loads reference rates from the last 90 days file.
     * Note: Uses the standard load method which parses the most recent date in the file.
     *
     * @return void
     * @throws \Exception
     */
    public function loadLast90Days()
    {
        return $this->load(self::ECB_90DAYS);
    }

    /**
     * Loads reference rates from the full history file.
     * Note: Uses the standard load method which parses the most recent date in the file.
     *
     * @return void
     * @throws \Exception
     */
    public function loadHistory()
    {
        $this->loadHistoryData();
        
        $this->parseContent($this->historyContent);
    }

    /**
     * Loads currency rates for a specific date from the full history.
     *
     * @param string $date The date in YYYY-MM-DD format.
     * @return bool True if the date was found and loaded.
     * @throws \Exception If historical data cannot be loaded or the date is not found.
     */
    public function loadByDate($date)
    {
        $xmlContent = null;
        if (time() - strtotime($date) <= 90 * 24 * 3600) {
            $xmlContent = $this->fetchUrl(self::ECB_90DAYS);
            if ($xmlContent === false) {
                throw new \Exception("Cannot load 90 days data");
            }
        } else {
            $this->loadHistoryData();
            $xmlContent = $this->historyContent;
        }

        $xml = simplexml_load_string($xmlContent);

        foreach ($xml->Cube->Cube as $day) {
            if ((string)$day['time'] === $date) {

                $this->currencyRates = [];
                $this->currencyRates[$this->baseCurrency] = 1;
                $this->date = $date;

                foreach ($day->Cube as $rate) {
                    $this->set(
                        (string)$rate['currency'],
                        (float)$rate['rate']
                    );
                }
                return true;
            }
        }

        throw new \Exception("Date not found in ECB history");
        
    }

    /* =====================================================
     * RATE ACCESS
     * ===================================================== */

    /**
     * Sets the exchange rate for a specific currency.
     *
     * @param string $currency The currency code (e.g., 'USD').
     * @param float $rate The exchange rate relative to the base currency.
     * @return void
     */
    public function set($currency, $rate)
    {
        $this->currencyRates[strtoupper($currency)] = (float)$rate;
    }

    /**
     * Retrieves the exchange rate for a specific currency.
     *
     * @param string $currency The currency code.
     * @return float The exchange rate.
     * @throws \Exception If the currency is not available.
     */
    public function get($currency)
    {
        $currency = strtoupper($currency);

        if (!isset($this->currencyRates[$currency])) {
            throw new \Exception("Currency not available: $currency");
        }

        return $this->currencyRates[$currency];
    }

    /**
     * Converts an amount from one currency to another.
     *
     * @param float $amount The amount to convert.
     * @param string $from The source currency code.
     * @param string $to The target currency code.
     * @return float The converted amount.
     * @throws \Exception If a currency rate is missing.
     */
    public function convert($amount, $from, $to)
    {
        $fromRate = $this->get($from);
        $toRate   = $this->get($to);

        // via EUR
        return ($amount / $fromRate) * $toRate;
    }

    /**
     * Gets all available currency rates.
     *
     * @return array Associative array of rates.
     */
    public function getAllRates()
    {
        return $this->currencyRates;
    }

    /**
     * Gets the date of the currently loaded rates.
     *
     * @return string|null The date in YYYY-MM-DD format, or null if not loaded.
     */
    public function getDate()
    {
        return $this->date;
    }
}
