<?php declare(strict_types=1);

namespace Tests;

use Exception;
use \Tests\TestIO;
use \Tests\TestCodes;
/**
 * Stream for HTML file
 */
class TestIOHTML extends TestIO
{
    protected string $body = "";
    protected string $head = "";
    /**
     * Creates Stream HTML file
     * @param Resource $handler Stream to use for outputting
     * @param array $template HTML file configuration
     */
    public function __construct(private $Stream,
    protected array $template=array())
    {
        TestIO::__construct($Stream);
        empty($template) && $this->template = array(
            "CSS" => "
            .".TestCodes::Convert_To(TestCodes::SUCCESS)."
            {
                color:#00AF00;
            }
            .".TestCodes::Convert_To(TestCodes::ERROR)."
            {
                color:#FF0000;
            }
            .".TestCodes::Convert_To(TestCodes::WARNING)."
            {
                color:#F0A000;
            }
            ",
            "HTML" => array(
                "Format_Evaluation_Tag" => "h4",
                "Symbol" => array(
                    TestCodes::ERROR => "&#10007;",
                    TestCodes::SUCCESS => "&#10004",
                    TestCodes::WARNING => "!"
                )
            )
        );
        $this->Prepare_Stream();
    }
    protected function Prepare_Stream() : void
    {
        if($meta = stream_get_meta_data ($this->Stream))
        {
            if(pathinfo($meta["uri"],PATHINFO_EXTENSION) === "html")
            {
                $this->head = 
                '<head>
                    <Title>Test Results</Title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0">
                    <style>'.$this->template["CSS"].'</style>
                </head>';
            }
            else
            {
                throw new Exception("Error: Not html file");
            }
        }
    }
    public function Stream_Write($text) : bool
    {
        return ($this->body .= $text) ? true : false;
    }
    public function Stream_Close()
    {
        fwrite($this->Stream,
        '
        <!doctype html>
        <html lang="en">
        '
        .$this->head
        .'<body>'.$this->body.'</body>'
        .'</html>');
        fclose($this->Stream);
    }
    public function Format_Evaluation_Response($message,$trace,$result)
    {
        $convertedCode = TestCodes::Convert_To($result);
        return '<'.$this->template['HTML']['Format_Evaluation_Tag'].' class="'.$convertedCode.'">'.$this->template["HTML"]["Symbol"][$result]." {$message} (".$convertedCode.")".($result ? "" : " -> {$trace['file']} #{$trace['line']}")."</".$this->template["HTML"]["Format_Evaluation_Tag"].">";
    }
}