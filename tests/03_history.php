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
    <title>Test 3: Last 90 Days</title>
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

<h1>3. Last 90 Days</h1>

<div class="card">
    <?php
    try {
        $fx->loadLast90Days();
        echo "<div class='success'>Last 90 days data loaded.</div>";
        echo "<div>Latest Date in file: <strong>" . $fx->getDate() . "</strong></div>";
    } catch (Exception $e) {
        echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
    }
    ?>
</div>



</body>
</html>