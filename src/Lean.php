<?hh

namespace Lean;

class Lean
{
    protected Router $router;
    public Request $request;
    public Response $response;

    // TODO: Create a proper options array shape
    public function __construct(
        private array<string, string> $config = [],
    )
    {
        $this->router = new Router();
        $this->request = new Request($_SERVER);
        $this->response = new Response();
    }

    public function run()
    {
    }

    public function get(): this
    {

        return $this;
    }

    public function post(): this
    {

        return $this;
    }

    public function put(): this
    {

        return $this;
    }

    public function delete(): this
    {

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
}
