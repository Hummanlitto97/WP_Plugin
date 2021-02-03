<?php declare(strict_types=1);

namespace Tests;

use Exception;
use Tests\Framework;
use Tests\TestIOTypes;

class TestIO
{
    const IO_ID = TestIO::class;

    public function __construct(private $Stream)
    {
        $this->Stream_Prepare();
        register_shutdown_function(function()
        {
            global $e; $e && die(1);
        });
    }
    protected function Stream_Prepare()
    {
        if(!is_resource($this->Stream) && is_writable(stream_get_meta_data($this->Stream)['uri']))
        {
            throw new Exception("Bad Stream given");
        }
    }
    public function Stream_Write(string $text) : bool
    {
        return fwrite($this->Stream,$text) ? true : false;
    }
    public function Stream_Close()
    {
        return fclose($this->Stream);
    }
    public function Stream_Change($Stream)
    {
        $this->Stream_Close();
        if(!is_resource($Stream))
        {
            throw new Exception("Bad Resource given when changing");
        }
        $this->Stream = $Stream;
    }
    public function Stream_Clean() : bool
    {
        return ftruncate($this->Stream,0);
    }
    public function Format_Evaluation_Response($message,$trace,$result)
    {
        return $message." (".TestCodes::Convert_To($result).") ".($result ? "" : " -> {$trace['file']} #{$trace['line']}");
    }
}