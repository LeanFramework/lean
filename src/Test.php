<?php

$app = new Lean\Router();

$app->get('/:id/:name', function([$id, $name]) use ($app) {

});

$app->run();
