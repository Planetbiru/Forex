<?php

use Planetbiru\Forex\Forex;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$fx = new Forex();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test 1: Daily Rates</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; padding: 20px; max-width: 900px; margin: 0 auto; background-color: #f9f9f9; }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        h2 { color: #2980b9; margin-top: 30px; border-left: 5px solid #3498db; padding-left: 10px; background: #fff; padding: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card { background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; color: #333; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #c0392b; font-weight: bold; }
        ul { list-style-type: none; padding: 0; }
        li { padding: 5px 0; border-bottom: 1px dashed #eee; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #3498db; font-weight: bold; }
    </style>
</head>
<body>

<div class="nav">
    <a href="currency.php">&larr; Back to Menu</a>
</div>

<h1>1. Daily Rates & Conversion</h1>

<h2>Load Daily Rates</h2>
<div class="card">
    <?php
    try {
        $fx->loadDaily();
        echo "<div class='success'>Daily rates loaded successfully.</div>";
        echo "<div>Date: <strong>" . $fx->getDate() . "</strong></div>";
    } catch (Exception $e) {
        echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
    }
    ?>
</div>

<h2>Get Rates & Conversion</h2>
<div class="card">
    <h3>Sample Rates (Base EUR)</h3>
    <table>
        <tr><th>Currency</th><th>Rate</th></tr>
        <tr><td>USD</td><td><?php echo $fx->get('USD'); ?></td></tr>
        <tr><td>IDR</td><td><?php echo $fx->get('IDR'); ?></td></tr>
        <tr><td>GBP</td><td><?php echo $fx->get('GBP'); ?></td></tr>
    </table>

    <h3>Conversions</h3>
    <ul>
        <li>100 USD to IDR: <strong><?php echo number_format($fx->convert(100, 'USD', 'IDR'), 2); ?></strong></li>
        <li>1 EUR to USD: <strong><?php echo number_format($fx->convert(1, 'EUR', 'USD'), 4); ?></strong></li>
        <li>1000 JPY to GBP: <strong><?php echo number_format($fx->convert(1000, 'JPY', 'GBP'), 2); ?></strong></li>
    </ul>
</div>

</body>
</html>