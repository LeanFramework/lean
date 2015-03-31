<?hh // strict

namespace Lean;

class Response
{
    public function parse(RawResponse $res): void
    {
    }

    public function finalize(): void
    {
        $this->finalise();
    }

    public function finalise(): void
    {
    }
}
