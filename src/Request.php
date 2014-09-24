<?hh //strict

namespace Lean;

class Request
{
    public string $method;
    public array<string, string> $params;
    public array<string, string> $headers;

    public function __construct(
        private array<string, string> $server,
    )
    {
        $this->method = $this->server['HTTP_METHOD'];
        $this->params = [];
        $this->headers = [];

        // time to build the params
        switch ($this->method) {
            case 'GET':
                $params = \filter_input_array(\INPUT_GET);
                break;
            case 'POST':
                break;
            case 'PUT':
            case 'DELETE':
                break;
            default:
                break;
        }

        $paramString = file_get_contents('php://input');
        parse_str($paramString, $this->params);
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

    public function params(
        ?string $key = null,
        ?string $default = null,
    ): mixed
    {

    }
}
