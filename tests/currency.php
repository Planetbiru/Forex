<?php

use Planetbiru\Forex\Forex;

require_once dirname(__DIR__)."/vendor/autoload.php";

$forex = new Forex();

?>
<style>
table{
    border-collapse: collapse;
}
td{
    padding: 4px 8px;
}
</style>
<table border="1" style="border-collapse:collapse">
    <thead>
        <td>From</td>
        <td>To</td>
        </tr>
    </thead>
<tbody>
<?php
foreach($forex->getRates() as $to=>$rate)
{
    ?>
            <tr>
            <td>1 <?php echo $to;?></td>
            <td><?php echo number_format($forex->convert($to, 'IDR'), 2, ".", "");?> IDR</td>
            </tr>
    <?php
}
?>
</tbody>
</table>
<?php

