<?hh //strict
/**
 * The main router class that defines routes and sets the up with callbacks
 *
 * @package Lean
 * @author Josh Girvin <josh@jgirvin.com>
 */
namespace Lean;

type BuiltRoute = shape('regex' => string, 'vars' => Vector<string>);

type Route = shape(
    'name' => string,
    'regex' => string,
    'vars' => Vector<string>,
    'callback' => (function(Map<string, mixed>): void),
);

type RouteCollection = shape(
    'GET' => Vector<Route>,
    'POST' => Vector<Route>,
    'PUT' => Vector<Route>,
    'DELETE' => Vector<Route>,
);

class Router
{
    /**
     * The routes Map, keyed to method types and with route names and callbacks
     */
    //private Map<string, Map<string, (function(Vector<mixed>): void)>> $routes;

    private RouteCollection $routes;

    // Taken from Slim framework!
    private Map<int, string> $messages = Map {
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => '418 I\'m a teapot',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    };


    public function __construct()
    {
        // Initialise the RoutesCollection $routes shape
        $this->routes = shape(
            'GET' => Vector {},
            'POST' => Vector {},
            'PUT' => Vector {},
            'DELETE' => Vector {},
        );
    }

    public function get(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): void
    {
        $this->defineRoute('GET', $routeName, $callback);
    }

    public function post(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): void
    {
        $this->defineRoute('POST', $routeName, $callback);
    }

    public function put(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): void
    {
        $this->defineRoute('PUT', $routeName, $callback);
    }

    public function delete(
        string $routeName,
        (function(Map<string, mixed>): void) $callback,
    ): void
    {
        $this->defineRoute('DELETE', $routeName, $callback);
    }

    private function defineRoute(
        string $method,
        string $routeName,
        (function(Map<string, mixed>): void) $callback
    ): void
    {
        // Build the regex
        $built = $this->buildRegex($routeName);

        // Define the Route shape
        $route = shape(
            'name' => $routeName,
            'regex' => $built['regex'],
            'vars' => $built['vars'],
            'callback' => $callback,
        );

        // Assign the route to the $routes collection
        switch ($method) {
            case 'GET':
                $this->routes['GET'][] = $route;
                break;
            case 'POST':
                $this->routes['POST'][] = $route;
                break;
            case 'PUT':
                $this->routes['PUT'][] = $route;
                break;
            case 'DELETE':
                $this->routes['DELETE'][] = $route;
                break;
            default:
                throw new \Exception('Incorrect method');
        }
    }

    public function run(string $req, string $method): void
    {
        $is404 = true;

        switch ($method) {
            case 'GET':
                foreach ($this->routes['GET'] as $route) {
                    $matches = $this->testRoute(
                        $req,
                        $route['regex'],
                        $route['vars'],
                    );

                    if ($matches !== null) {
                        $is404 = false;
                        call_user_func($route['callback'], $matches);
                        return;
                    }
                }
                break;
            case 'POST':
                foreach ($this->routes['POST'] as $route) {
                    $matches = $this->testRoute(
                        $req,
                        $route['regex'],
                        $route['vars'],
                    );

                    if ($matches !== null) {
                        $is404 = false;
                        call_user_func($route['callback'], $matches);
                        return;
                    }
                }
                break;
            case 'PUT':
                foreach ($this->routes['PUT'] as $route) {
                    $matches = $this->testRoute(
                        $req,
                        $route['regex'],
                        $route['vars'],
                    );

                    if ($matches !== null) {
                        $is404 = false;
                        call_user_func($route['callback'], $matches);
                        return;
                    }
                }
                break;
            case 'DELETE':
                foreach ($this->routes['DELETE'] as $route) {
                    $matches = $this->testRoute(
                        $req,
                        $route['regex'],
                        $route['vars'],
                    );

                    if ($matches !== null) {
                        $is404 = false;
                        call_user_func($route['callback'], $matches);
                        return;
                    }
                }
                break;
            default:
                break;
        }

        if ($is404 === true) {
            $this->setStatus(404);
            print "404!";
        }
    }

    /**
     * Builds a regex route match and set of request params in the URI from a given route definition
     */
    private function buildRegex(string $url): BuiltRoute
    {
        // Handle the special case of a top level "/" route
        if ($url === '/') {
            $result = shape('regex' => '/^\/$/', 'vars' => Vector {});
            return $result;
        }

        $split = explode('/', $url);
        $regex = '/^\/';
        $vars = Vector {};

        // Now iterate over the exploded items, check if they're req vars, and set up the results
        foreach ($split as $item) {
            $item = (string) $item;
            if ($item === '') {
                continue;
            }

            $varName = $this->isRequestVariable($item);

            if ($varName !== null) {
                $vars[] = $varName;
                $regex .= '([\w+-]+)';
            } else {
                $regex .= $item;
            }
            $regex .= '\/';
        }
        $regex .= '?$/';

        return shape('regex' => $regex, 'vars' => $vars);
    }

    /**
     * Tests if a given item is a request variable (eg. ":name") and returns it without the colon
     * if it is. Returns null if not
     */
    private function isRequestVariable(string $item): ?string
    {
        if (preg_match('/^\:\w+$/', $item)) {
            // remove colon for var name
            return substr($item, 1);
        }

        return null;
    }

    private function testRoute(
        string $requestUri,
        string $routeRegex,
        Vector<string> $routeVars
    ): ?Map<string, mixed>
    {
        $matches = Vector {};
        $result = preg_match($routeRegex, $requestUri, $matches);

        if (count($matches) > 0) {
            $data = array_slice($matches, 1);
            $capture = array_combine($routeVars, $data);

            if ($result) {
                //$capMap = Map {};
                //foreach ($capture as $key => $val) {
                //    $capMap[(string) $key] = (string) $val;
                //}

                return new Map($capture);
            }
        }

        return null;
    }

    public function setStatus(int $status): void
    {
        $statusMessage = $this->messages[$status];
        header('HTTP/1.0 ' . $statusMessage);
        //http_response_code($status);
    }
}

