<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forex ECB Test Suite</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; padding: 20px; max-width: 900px; margin: 0 auto; background-color: #f9f9f9; }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .card { background: #fff; padding: 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 15px; }
        ul { list-style-type: none; padding: 0; }
        li { margin-bottom: 10px; }
        a { text-decoration: none; color: #3498db; font-weight: bold; font-size: 1.2em; display: block; padding: 10px; background: #f0f8ff; border-radius: 4px; border-left: 5px solid #3498db; }
        a:hover { background: #e1f0fa; }
    </style>
</head>
<body>

    <h1>Forex ECB Test Suite</h1>
    <div class="card">
        <p>Select a test to run:</p>
        <ul>
            <li><a href="01_daily.php">1. Daily Rates & Conversion</a></li>
            <li><a href="02_manipulation.php">2. Manual Manipulation</a></li>
            <li><a href="03_history.php">3. Last 90 Days</a></li>
            <li><a href="04_history.php">4. Full History (Heavy)</a></li>
            <li><a href="05_history.php">5. History By Date</a></li>
        </ul>
    </div>

</body>
</html>
