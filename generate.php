<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$urlToScrape = '';

if(PHP_SAPI === 'cli') {
    if ($argc > 1) {
        $urlToScrape = $argv[1];
    }
} else {
    if(array_key_exists('url', $_POST)) {
        $urlToScrape = $_POST['url'];
    }
}

if(!$urlToScrape) {
    die('URL must be specified!<br/><a href="/">Back</a>');
}


$urlHash = md5($urlToScrape);
$tempFile = $urlHash.'.html';
if(!file_exists($tempFile)) {
    file_put_contents($tempFile, file_get_contents($urlToScrape));
}

$dom = new \PHPHtmlParser\Dom;
$dom->loadFromFile($tempFile);

$nodeEstate = $dom->find('div.estate')->offsetGet(0);

$estateId = $nodeEstate->find('#ertszammeres_ingatlanreferenciaazonosito_id')->getAttribute('value');

$estateTitle = $nodeEstate->find('h1[itemprop=name]')->offsetGet(0)->innerHTML;

$estateImages = [];
foreach($nodeEstate->find('div[data-current-id] img') as $node) {
    $src = $node->getAttribute('src');
    $estateImages[] = $src;
}

$estateAttributes = [];
$estateAttributesTitle = [
    'price' => 'Ár',
    'size' => 'Méret',
    'room' => 'Szoba',
    'floor' => 'Szint',
];
$estateAttributesValue = [
    'price' => 0,
    'size' => 0,
    'room' => 0,
    'floor' => 0,
];
foreach($estateAttributesValue as $k => $v) {
    foreach($nodeEstate->find('div.short_details li.' . $k) as $nodeDetail) {
        if($k === 'price') {
            $v = number_format(floatval($nodeDetail->find('[itemprop=price]')->offsetGet(0)->getAttribute('content')), 0, '', ' ');
            $v.= ' ' . $nodeDetail->find('[itemprop=priceCurrency]')->offsetGet(0)->getAttribute('content');
        } else {
            $v = trim($nodeDetail->find('.value')->innerText);
        }
        $estateAttributes[$estateAttributesTitle[$k]] = $v;
    }
}

$estateData = [];
foreach($nodeEstate->find('.estate_datas .table-list-style') as $nodeData) {
    $divs = $nodeData->find('div');
    $estateData[trim($divs->offsetGet(0)->innerText)] = trim($divs->offsetGet(1)->innerText);
}

$estateDescription = $nodeEstate->find('#estate_description div.paragraphWithoutSpace')->offsetGet(0)->innerHTML;

$estateAgent = [
    'name' => trim($nodeEstate->find('.name_box [itemprop=name]')->offsetGet(0)->innerText),
    'phone' => trim($nodeEstate->find('.name_box [itemprop=telephone]')->offsetGet(0)->getAttribute('content')),
    'email' => trim($nodeEstate->find('.name_box [itemprop=email]')->offsetGet(0)->getAttribute('content')),
];

ob_start();
ob_implicit_flush(false);
include __DIR__ . '/template.php';
$html = ob_get_clean();

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 0,
    'margin_right' => 0,
    'margin_bottom' => 0,
    'margin_left' => 0,
    'margin_footer' => 0,
]);
$mpdf->WriteHTML($html);

if(PHP_SAPI === 'cli') {
    $mpdf->Output($estateId . '.pdf');
} else {
    $mpdf->Output();
}
