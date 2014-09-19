<?hh // partial

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Lean\Router();

$app->get('/', function(Map<string, mixed> $items) {
    print "Hello, World!";
});

$app->get('/hello/', function(Map<string, mixed> $items) {
    print "Yep";
});

$app->post('/hello/:name', function(Map<string, mixed> $items) use ($app) {
    $app->setStatus(400);
    $app->setStatus(500);
    print 'Hello, ' . $items['name'];
});

$app->get('/hello/:name/:test/:another/hello', function(Map<string, mixed> $items) {
    print 'Hello, ' . $items['name'] . ' ' . $items['test'];
});

$app->put('/post/:hello', function(Map<string, mixed> $items) use ($app) {
    $app->setStatus(418);
    print $items['hello'];
});

$app->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
