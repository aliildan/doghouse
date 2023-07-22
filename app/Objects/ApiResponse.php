<?php

namespace App\Objects;
/**
 * Class ApiResponse
 * @package App\Objects
 */
class ApiResponse implements \JsonSerializable
{

    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    private ?array $data;

    private string $message;

    private string $status;

    /**
     * @param array|null $data
     * @param string $message
     */
    public function __construct(?array $data, string $message,string $status = self::STATUS_SUCCESS)
    {
        $this->data = $data;
        $this->message = $message;
        $this->status = $status;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'data' => $this->data,
            'status' => $this->status,
            'message' => $this->message
        ];
    }
}