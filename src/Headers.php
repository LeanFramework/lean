<?hh // strict
namespace Lean;

type Status = shape('code' => int, 'message' => string);

class Headers
{
    private Vector<string> $headers = Vector {};
    private Status $status;
    private bool $statusInserted = false;

    public function __construct()
    {
        // Defaults the status to 200 OK
        $this->status = shape( 
            'code' => 200,
            'message' => '200 OK'
        );
    }

    /**
     * For backwards compatibility with regular PHP headers. Splits the string 
     * into it's key-value pair and stores it in the header map.
     *
     * @param string $header Header string
     * @return void
     */
    public function header(
       string $header,
    ): void 
    {
        $this->headers[] = $header;
    }

    public function output(
    ): void
    {
        $this->insertStatus();

        foreach ($this->headers as $header) {
            header($header);
        }
    }

    public function getHeaders(
    ): Vector<string>
    {
        $copy = $this->headers->toVector();
        $copy->add('HTTP/1.1 ' . $this->status['message']);
        
        return $copy;
    }

    public function setStatus(
        int $code,
        string $message,
    ): void
    {
        $this->status = shape(
            'code' => $code,
            'message' => $message,
        );
    }

    private function insertStatus(): void
    {
        // Inserting the Location header at the beginning
        if ($this->statusInserted === false) {
            $statusHeader = 'HTTP/1.1 ' . $this->status['message'];
            $this->headers->reverse();
            $this->headers->add($statusHeader);
            $this->headers->reverse();
            $this->statusInserted = true;
        }
    }
}
