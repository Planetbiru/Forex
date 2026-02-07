<?php

use Planetbiru\Forex\Forex;

require_once dirname(__DIR__) . "/vendor/autoload.php";

// Increase execution time and memory limit to handle large XML downloads and parsing
set_time_limit(0);
ini_set('memory_limit', '512M');

$fx = new Forex();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test 5: History By Date</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; padding: 20px; max-width: 900px; margin: 0 auto; background-color: #f9f9f9; }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        h2 { color: #2980b9; margin-top: 30px; border-left: 5px solid #3498db; padding-left: 10px; background: #fff; padding: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card { background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 15px; }
        .code { font-family: Consolas, Monaco, 'Andale Mono', monospace; background: #eee; padding: 2px 5px; border-radius: 3px; color: #d63031; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #c0392b; font-weight: bold; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #3498db; font-weight: bold; }
    </style>
</head>
<body>

<div class="nav">
    <a href="currency.php">&larr; Back to Menu</a>
</div>

<h1>5. History By Date</h1>

<h2>Load By Specific Date (History)</h2>
<div class="card">
    <?php
    $searchDate = '2024-01-03';
    echo "<div>Attempting to load data for: <span class='code'>$searchDate</span> (Downloads full history)</div>";
    try {
        $fx->loadByDate($searchDate);
        echo "<div class='success'>Data found and loaded.</div>";
        echo "<div>Date: <strong>" . $fx->getDate() . "</strong></div>";
        echo "<div>USD Rate on $searchDate: <strong>" . $fx->get('USD') . "</strong></div>";
    } catch (Exception $e) {
        echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
    }
    ?>
</div>

</body>
</html>