<?hh
namespace Lean\Tests;

class LeanTest extends \PHPUnit_Framework_TestCase
{
    protected array<string, array<string, string>> $configData = [];

    public function setUp()
    {
    }

    public function testRequestAndResponseInstantiated()
    {
        $this->configData = [
            'SERVER' => [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/'
            ]
        ];

        $lean = new \Lean\Lean($this->configData);
        $this->assertInstanceOf('Lean\Request', $lean->request());
        $this->assertInstanceOf('Lean\Response', $lean->response());
    }
}
