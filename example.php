<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Printer</title>
    </head>

    <body>

<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Add "library" folder to include path
set_include_path(get_include_path() . PATH_SEPARATOR . 'library');

require_once 'Kohut/SNMP/Printer.php';

// IP address of printer in network
$ip = '192.168.0.2';

try {
    $printer = new Kohut_SNMP_Printer($ip);
?>

        <h1>IP address: <?php echo $printer; ?></h1>

        <h3>Type: <?php if ($printer->isColorPrinter()) echo 'color printer'; elseif ($printer->isMonoPrinter()) echo 'mono printer'; ?></h3>

        <h3>Factory name: <?php echo $printer->getFactoryId(); ?></h3>

        <h3>Vendor: <?php echo $printer->getVendorName(); ?></h3>

        <h3>Serial Number: <?php echo $printer->getSerialNumber(); ?></h3>

        <span style="background: black; color: white;">Black Toner:</span> <?php echo round($printer->getBlackTonerLevel(), 2); ?> %<br />
        
        <?php if ($printer->isColorPrinter()): ?>
            <span style="background: cyan; color: black;">Cyan Toner:</span> <?php echo round($printer->getCyanTonerLevel(), 2); ?> %<br />
            <span style="background: magenta; color: white;">Magenta Toner:</span> <?php echo round($printer->getMagentaTonerLevel(), 2); ?> %<br />
            <span style="background: yellow; color: black;">Yellow Toner:</span> <?php echo round($printer->getYellowTonerLevel(), 2); ?> %<br />
        <?php endif; ?>
        
        <br />
        <span>Drum level: <?php echo $printer->getDrumLevel(); ?> %</span><br />
        <span>Count of printed papers: <?php echo $printer->getNumberOfPrintedPapers(); ?></span><br />
        
        <h3>Cartridges:</h3>

        <span><?php echo $printer->getBlackCatridgeType(); ?></span><br />
        <span><?php echo $printer->getCyanCatridgeType(); ?></span><br />
        <span><?php echo $printer->getMagentaCatridgeType(); ?></span><br />
        <span><?php echo $printer->getYellowCatridgeType(); ?></span><br />

<?php
} catch(Kohut_SNMP_Exception $e) {
    echo 'SNMP Error: ' . $e->getMessage();
}
?>
        

    </body>
</html>