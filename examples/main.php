<?php

declare(strict_types=1);

use WebFu\Prometheus\Component;
use WebFu\Prometheus\Model;
use WebFu\Prometheus\Prometheus;

require __DIR__ . '/../vendor/autoload.php';

$component = new Component('#my-component');
$component
    ->template(<<<HTML
Input: <input :model="value" type="text"><br/>
Output:<br/>
<input :model="value" type="text" readonly><br/>
<select :model="value">
    <option>- No value -</option>
    <option>1</option>
    <option>2</option>
    <option>3</option>
</select><br/>
<select :model="value">
    <option>- No value -</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select><br/>
<input :model="value" type="radio" value="1">1
<input :model="value" type="radio" value="2">2
<input :model="value" type="radio" value="3">3<br/>
<input :model="value" type="checkbox" value="1">1
<input :model="value" type="checkbox" value="2">2
<input :model="value" type="checkbox" value="3">3<br/>
<div>{{ value }}</div>
HTML
    )
    ->models([
        'value' => new Model([
            'type' => Model::INT
        ]),
    ]);

$prometheus = new Prometheus('#app');
$prometheus->components([
    $component,
]);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <script src="../script/prometheus.js"></script>
</head>
<body id="app">
<div id="my-component"></div>
<?= $prometheus->script(); ?>
</body>
</html>
