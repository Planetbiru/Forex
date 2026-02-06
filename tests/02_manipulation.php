<?php

use Planetbiru\Forex\Forex;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$fx = new Forex();
$fx->loadDaily(); // Load data awal agar kita punya base data untuk dimanipulasi
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test 2: Manual Manipulation</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; padding: 20px; max-width: 900px; margin: 0 auto; background-color: #f9f9f9; }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        h2 { color: #2980b9; margin-top: 30px; border-left: 5px solid #3498db; padding-left: 10px; background: #fff; padding: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card { background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 15px; }
        .code { font-family: Consolas, Monaco, 'Andale Mono', monospace; background: #eee; padding: 2px 5px; border-radius: 3px; color: #d63031; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #3498db; font-weight: bold; }
    </style>
</head>
<body>

<div class="nav">
    <a href="currency.php">&larr; Back to Menu</a>
</div>

<h1>2. Manual Manipulation</h1>

<h2>Manual Rate Manipulation (set)</h2>
<div class="card">
    <?php
    $fx->set('TEST', 500.00);
    echo "<div>Set custom currency <span class='code'>TEST</span> to 500.00</div>";
    echo "<div>Get <span class='code'>TEST</span>: <strong>" . $fx->get('TEST') . "</strong></div>";
    
    // Override existing
    try {
        $originalUsd = $fx->get('USD');
        $fx->set('USD', 1.5);
        echo "<div>Override <span class='code'>USD</span> to 1.5 (was $originalUsd)</div>";
        echo "<div>New 100 USD to IDR (using USD=1.5): <strong>" . number_format($fx->convert(100, 'USD', 'IDR'), 2) . "</strong></div>";
        
        // Restore
        $fx->set('USD', $originalUsd);
    } catch (Exception $e) {
        echo "<div>Error accessing USD: " . $e->getMessage() . "</div>";
    }
    ?>
</div>

<h2>Get All Rates</h2>
<div class="card">
    <?php
    $allRates = $fx->getAllRates();
    echo "<div>Total currencies available: <strong>" . count($allRates) . "</strong></div>";
    echo "<div style='max-height: 200px; overflow-y: auto; background: #eee; padding: 10px; margin-top: 10px; font-family: monospace;'>";
    foreach($allRates as $curr => $rate) {
        echo "$curr: $rate<br>";
    }
    echo "</div>";
    ?>
</div>

</body>
</html>