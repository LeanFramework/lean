<?hh 

require_once __DIR__ . '/../vendor/autoload.php';

/*
$router = new Lean\Router();

$router->get('/', function(Map<string, mixed> $items) use ($router) {
    var_dump($router->request->method);
});

$router->get('/hello/', function(Map<string, mixed> $items) {
    print "Yep";
});

$router->post('/hello/:name', function(Map<string, mixed> $items) use ($router) {
    $router->setStatus(400);
    $router->setStatus(500);
    print 'Hello, ' . $items['name'];
});

$router->get('/hello/:name/:test/:another/hello', function(Map<string, mixed> $items) {
    print 'Hello, ' . $items['name'] . ' ' . $items['test'];
});

$router->put('/post/:hello', function(Map<string, mixed> $items) use ($router) {
    $router->setStatus(418);
    print $items['hello'];
});

$router->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
 */

$app = new Lean\Lean();

$app->get('/hello', function(Map<string, mixed> $items) use ($app) {
    var_dump($app->request->isPost());
});

$app->run();
