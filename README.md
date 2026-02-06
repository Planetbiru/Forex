# Forex

Foreign Exchange

A PHP library to fetch and parse currency exchange rates from the European Central Bank (ECB). This library provides methods to convert currencies based on the retrieved exchange rates.

## Important Performance Note

Fetching historical data (especially via `loadHistory` or `loadByDate`) involves processing large XML datasets. This operation should **only** be performed by back-end processes (e.g., cron jobs, background workers) and **must not** be triggered synchronously by front-end user actions. To ensure optimal performance, always prefer fetching the minimal dataset required (e.g., `loadDaily` or `loadLast90Days`) over the full history.

## Table of Contents

* [Initialization](#initialization)
* [Loading Data](#loading-data)

  * [loadDaily](#loaddaily)
  * [loadLast90Days](#loadlast90days)
  * [loadHistory](#loadhistory)
  * [loadByDate](#loadbydate)
  * [load](#load)
* [Data Access & Manipulation](#data-access--manipulation)

  * [get](#get)
  * [set](#set)
  * [convert](#convert)
  * [getAllRates](#getallrates)
  * [getDate](#getdate)

---

## Initialization

Ensure that the class file is included or that an autoloader is being used.

```php
use Planetbiru\Forex\Forex;

// Example 1: Initialization without loading initial data
$forex = new Forex();
$forex->loadDaily(); // Manually load data afterwards

// Example 2: Direct initialization with daily data
$forex = new Forex(Forex::ECB_DAILY);

// Example 3: Direct initialization with last 90 days data
$forex = new Forex(Forex::ECB_90DAYS);
```

---

## Loading Data

### loadDaily

Loads the latest daily reference exchange rates.

**Example 1: Basic usage**

```php
$forex = new Forex();
$forex->loadDaily();
echo "Daily data loaded. Date: " . $forex->getDate();
```

**Example 2: Reload daily data**

```php
$forex = new Forex();
// ... perform other operations ...
$forex->loadDaily(); // Refresh to the latest daily data
```

**Example 3: Check currency availability after loading**

```php
$forex = new Forex();
$forex->loadDaily();
if ($forex->getDate() == date('Y-m-d')) {
    echo "Today's data is already available.";
}
```

---

### loadLast90Days

Loads exchange rate references from the last 90 days file (using the most recent date available in that file).

**Example 1: Basic usage**

```php
$forex = new Forex();
$forex->loadLast90Days();
```

**Example 2: Using class constants**

```php
$forex = new Forex();
$forex->load(Forex::ECB_90DAYS); // Equivalent to loadLast90Days()
```

**Example 3: Validation after loading**

```php
$forex = new Forex();
try {
    $forex->loadLast90Days();
    echo "Successfully loaded 90-day data.";
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
```

---

### loadHistory

Loads exchange rate references from the full historical file (since 1999). By default, it retrieves the most recent available date.

**Example 1: Basic usage**

```php
$forex = new Forex();
$forex->loadHistory();
```

**Example 2: Preparation for loadByDate**

```php
// Typically, loadHistory is not called directly when searching for a specific date,
// but this file is used internally by loadByDate.
$forex = new Forex();
$forex->loadHistory(); // Loads the most recent data from the large history file
```

**Example 3: Handling large files**

```php
$forex = new Forex();
// Caution: this loads a relatively large XML file
$forex->loadHistory();
echo "Number of available currencies: " . count($forex->getAllRates());
```

---

### loadByDate

Loads exchange rates for a specific date (YYYY-MM-DD) from the full historical dataset.

**Example 1: Load a valid date**

```php
$forex = new Forex();
$forex->loadByDate('2023-01-01'); // Ensure the date is not a holiday/weekend
echo "USD rate on 2023-01-01: " . $forex->get('USD');
```

**Example 2: Handle a date that is not found**

```php
$forex = new Forex();
try {
    $forex->loadByDate('2025-01-01'); // Future date
} catch (Exception $e) {
    echo "Data not found: " . $e->getMessage();
}
```

**Example 3: Searching dates in a loop**

```php
$forex = new Forex();
$dates = ['2023-05-01', '2023-05-02', '2023-05-03'];
foreach ($dates as $date) {
    try {
        $forex->loadByDate($date);
        echo "EUR to USD rate on $date: " . $forex->get('USD') . "\n";
    } catch (Exception $e) {
        echo "No data available for $date\n";
    }
}
```

---

### load

Loads data from a custom XML URL.

**Example 1: Load from a custom URL**

```php
$forex = new Forex();
$forex->load('https://example.com/custom-ecb-mirror.xml');
```

**Example 2: Load from a local file**

```php
$forex = new Forex();
$forex->load('file:///path/to/local/eurofxref-daily.xml');
```

**Example 3: Reload with a different source**

```php
$forex = new Forex();
$forex->load(Forex::ECB_DAILY);
// Switch source
$forex->load(Forex::ECB_90DAYS);
```

---

## Data Access & Manipulation

### get

Retrieves the exchange rate for a specific currency.

**Example 1: Get USD rate**

```php
$rate = $forex->get('USD');
echo "1 EUR = $rate USD";
```

**Example 2: Get IDR (Indonesian Rupiah) rate**

```php
$rate = $forex->get('IDR');
echo "1 EUR = $rate IDR";
```

**Example 3: Case insensitive**

```php
echo $forex->get('usd'); // Same as get('USD')
```

---

### set

Manually sets an exchange rate.

**Example 1: Override an existing rate**

```php
$forex->set('USD', 1.12); // Force USD rate to 1.12
```

**Example 2: Add a new currency**

```php
$forex->set('BTC', 0.000035); // Add Bitcoin
echo $forex->get('BTC');
```

**Example 3: Update IDR rate**

```php
$forex->set('IDR', 16500);
echo "IDR rate manually updated.";
```

---

### convert

Converts an amount from one currency to another.

**Example 1: Convert USD to IDR**

```php
$usd = 100;
$idr = $forex->convert($usd, 'USD', 'IDR');
echo "$usd USD = $idr IDR";
```

**Example 2: Convert EUR to GBP**

```php
$eur = 50;
$gbp = $forex->convert($eur, 'EUR', 'GBP');
echo "$eur EUR = $gbp GBP";
```

**Example 3: Convert JPY to USD**

```php
$jpy = 10000;
$usd = $forex->convert($jpy, 'JPY', 'USD');
echo "$jpy JPY = $usd USD";
```

---

### getAllRates

Returns all available exchange rates as an array.

**Example 1: Dump all data**

```php
$rates = $forex->getAllRates();
print_r($rates);
```

**Example 2: Count available currencies**

```php
$count = count($forex->getAllRates());
echo "$count currencies available.";
```

**Example 3: Loop through all rates**

```php
$rates = $forex->getAllRates();
foreach ($rates as $code => $rate) {
    echo "1 EUR = $rate $code\n";
}
```

---

### getDate

Retrieves the date of the currently loaded exchange rate data.

**Example 1: Display the date**

```php
echo "Exchange rate date: " . $forex->getDate();
```

**Example 2: Format the date**

```php
$date = date('d F Y', strtotime($forex->getDate()));
echo "Last updated: $date";
```

**Example 3: Date validation**

```php
if ($forex->getDate() === null) {
    echo "Data has not been loaded yet.";
} else {
    echo "Data is ready.";
}
```


