<?php

declare(strict_types=1);

namespace App\Http;


use JsonSerializable;
use stdClass;

/**
 * Class Result
 * @package App\Http
 */
class Result implements JsonSerializable
{

    /**
     * @var int
     */
    static int $okCode = 200;


    /**
     * @var stdClass
     */
    private stdClass $result;

    private function __construct(int $code, string $message, $data = null)
    {
        $this->result = new stdClass();
        $this->result->code = $code;
        $this->result->message = $message;
        $this->result->data = $data;
    }

    /**
     * @param $data
     * @param string $message
     * @param int
     * @return static
     */
    public static function ok($data = null, string $message = "success"): Result
    {
        return new static(self::$okCode, $message, $data);
    }

    /**
     * @param int $code
     * @param string $message
     * @param null $data
     * @return static
     */
    public static function err(int $code = 600, string $message = "error", $data = null): Result
    {
        return new static($code, $message, $data);
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        // TODO: Implement jsonSerialize() method.
        return $this->result;
    }
}
