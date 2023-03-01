<?php
global $estateTitle, $estateImages, $estateAttributes, $estateData, $estateDescription, $estateAgent;
$imgSize = 460;
?><!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?= $estateTitle ?></title>
    <style>
        .page {
            padding: 12mm;
            font-size: 12px;
        }

        .description {
            font-size: 12px;
        }

        .data {

        }

        .data .data-item {
            display: inline-block;
            padding: 0 24px 6px 0;
        }

        .data .data-item .name {
            font-weight: bold;
        }

        .data .data-item .value {

        }

        .tear {
            font-size: 16px;
            padding: 3mm;
            position: fixed;
            right: 0;
            bottom: 0;
            rotate: -90;
        }
        .tear-item {
            margin-top: 2mm;
            padding-top: 2mm;
            border-top: 1px dashed #000;
        }

    </style>
</head>
<body>

<div class="page">
    <h2 class="title"><?= $estateTitle ?></h2>

    <div class="attributes">
        <?php foreach ($estateAttributes as $k => $v) : ?>
            <span class="attribute"><strong><?= $k ?></strong> <?= $v ?> &nbsp; &nbsp;</span>
        <?php endforeach; ?>
    </div>

    <table class="images">
        <tbody>
        <tr>
            <td rowspan="2"><img src="<?= $estateImages[0] ?>" alt="<?= $estateTitle ?>" width="<?= $imgSize ?>"/></td>
            <td><img src="<?= $estateImages[1] ?>" alt="<?= $estateTitle ?>" width="<?= $imgSize / 2 ?>"/></td>
        </tr>
        <tr>
            <td><img src="<?= $estateImages[1] ?>" alt="<?= $estateTitle ?>" width="<?= $imgSize / 2 ?>"/></td>
        </tr>
        </tbody>
    </table>

    <div class="data">
        <?php foreach ($estateData as $k => $v): ?>
            <span class="data-item">
            <span class="name"><strong><?= $k ?></strong></span>
            <span class="value"><?= $v ?></span>
            &nbsp;&nbsp;&nbsp;
        </span>
        <?php endforeach; ?>
    </div>

    <p class="description"><?= $estateDescription ?></p>
</div>

<div class="tear">
    <?php for ($i = 0; $i < 13; $i++): ?>
        <div class="tear-item"><?= $estateAgent['name'] ?><br/><?= $estateAgent['phone'] ?></div>
    <?php endfor; ?>
</div>

</body>
</html>
