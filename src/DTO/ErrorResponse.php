<?php
declare(strict_types=1);

namespace App\DTO;

class ErrorResponse
{
    private string $message;
    private array $errors;

    public function __construct(string $message, array $errors) 
    {
        $this->message = $message;
        $this->errors = $errors;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getErrors(): array {
        return $this->errors;
    }
}
