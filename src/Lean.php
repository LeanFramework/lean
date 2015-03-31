<?hh // partial
namespace Lean;

type RequestVars = shape(
    'GET' => Map<string, string>,
    'POST' => Map<string, string>,
    'COOKIES' => Map<string, string>,
);

class Lean
{
    protected Router $router;
    public Request $request;
    public Response $response;
    public Headers $headers;

    // TODO: Create a proper options array shape
    public function __construct(
        private array<string, array<string, string>> $config = [],
    )
    {
        $this->headers = new Headers();
        $this->router = new Router($this->headers);
        $this->request = new Request($this->getServerVars(), $this->getRequestVars());
        $this->response = new Response();
    }

    public function run()
    {
        $this->response->parse(
            $this->router->run(
                $this->request->uri, 
                $this->request->method
            )
        );

        $this->response->finalize();
    }

    public function get(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): this
    {
        $this->router->get($routeName, $callback);
        return $this;
    }

    public function post(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): this
    {
        $this->router->post($routeName, $callback);
        return $this;
    }

    public function put(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): this
    {
        $this->router->put($routeName, $callback);
        return $this;
    }

    public function delete(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): this
    {
        $this->router->delete($routeName, $callback);
        return $this;
    }

    /**
     * DEPRECATED: For backwards compatability
     */
    public function request(): Request
    {
        return $this->request;
    }

    /**
     * DEPRECATED: For backwards compatability
     */
    public function response(): Response
    {
        return $this->response;
    }

    private function getRequestVars(): RequestVars
    {
        // UNSAFE
        return shape(
            'GET' => $_GET,
            'POST' => $_POST,
            'COOKIES' => new Map($_COOKIE),
        );
    }

    private function getServerVars(): array<string, string>
    {
        if (array_key_exists('SERVER', $this->config)) {
            return $this->config['SERVER'];
        }

        // UNSAFE
        return $_SERVER;
    }
}
