<?hh // partial
namespace Lean\Tests;

class HeaderStub extends \Lean\Headers
{
    public function output(): void
    {
        return;
    }
}

class RouterTest extends \PHPUnit_Framework_TestCase
{
   public function test404ForUnmatchedRoute()
    {
        $headerStub = new HeaderStub();

        $router = new \Lean\Router($headerStub);
        $router->get('/hello', function(Map<string, mixed> $items) {
            return;
        });

        $router->run('/notHello', 'GET');
        $headers = $router->headers->getHeaders();

        $this->assertTrue($headers->count() == 1);
        $this->assertTrue($headers->linearSearch('HTTP/1.1 404 Not Found') == 0);
    }
}

