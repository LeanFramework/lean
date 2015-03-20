<?hh //strict
/**
 * Lean\Request
 *
 * This is the internal Request object that is built on construction of
 * Lean\Lean -- it's not to be used directly.
 *
 * TODO: Need to bring in the route matched parameters to the $params variable
 */
namespace Lean;

class Request
{
    public string $method;
    public string $uri;
    public Map<string, string> $params;
    public Map<string, string> $headers;

    public function __construct(
        private array<string, string> $server,
        private RequestVars $reqVars,
    ): void
    {
        $this->method = $server['REQUEST_METHOD'];

//        if ($this->server['REQUEST_URI'] !== null) {
            $this->uri = $server['REQUEST_URI'];
//        } else {
//            $this->uri = '/';
//        }

        $this->params = Map {};
        $this->headers = Map {};

        // time to build the params
        $paramString = file_get_contents('php://input');

        switch ($this->method) {
            case 'GET':
                $this->params = $this->reqVars['GET'];
                break;
            case 'POST':
                $this->params = $this->reqVars['POST'];
                break;
            case 'PUT':
            case 'DELETE':
                parse_str($paramString, $this->params);
                break;
            default:
                throw new \Exception('Wrong method type');
                break;
        }
    }

    public function isGet(): bool
    {
        if ($this->method === 'GET') {
            return true;
        }

        return false;
    }

    public function isPost(): bool
    {
         if ($this->method === 'POST') {
            return true;
        }

        return false;
    }

    public function isPut(): bool
    {
        if ($this->method === 'PUT') {
            return true;
        }

        return false;
    }

    public function isDelete(): bool
    {
        if ($this->method === 'DELETE') {
            return true;
        }

        return false;
    }

    public function isAjax(): bool
    {
        if ($this->params('isajax')) {
            return true;
        } elseif (array_key_exists('X_REQUESTED_WITH', $this->headers) && $this->headers['X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return true;
        }

        return false;
    }

    public function isXhr(): bool
    {
        return $this->isAjax();
    }

    /**
     * Request paramter functions
     */
    public function params(
        ?string $key = null,
        ?string $default = null,
    ): mixed
    {

    }

    // this assumes query params are already parsed
    public function get(
        ?string $key = null,
        ?string $default = null,
    ): mixed
    {
        return $this->getParam($key, $default);
    }

    public function post(
        ?string $key = null,
        ?string $default = null,
    ): mixed
    {
        return $this->getParam($key, $default);
    }

    public function put(
        ?string $key = null,
        ?string $default = null,
    ): mixed
    {
        return $this->getParam($key, $default);
    }

    public function delete(
        ?string $key = null,
        ?string $default = null,
    ): mixed
    {
        return $this->getParam($key, $default);
    }

    protected function getParam(
        ?string $key = null,
        ?string $default = null,
    ): mixed
    {
        if ($key !== null) {
            return $this->getSingleParam($key, $default);
        }

        return $this->getAllParams();
    }

    protected function getSingleParam(
        string $key,
        ?string $default = null,
    ): ?string
    {
        if ($this->params->containsKey($key)) {
            return $this->params[$key];
        } elseif ($default !== null) {
            return $default;
        }

        return null;
    }

    protected function getAllParams(): Map<string, string>
    {
        return $this->params;
    }
}
