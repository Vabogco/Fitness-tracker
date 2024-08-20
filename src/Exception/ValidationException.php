<?php
declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends RuntimeException
{
    private ConstraintViolationListInterface $violationList;

    public function __construct(ConstraintViolationListInterface $violationList)
    {
        $this->violationList = $violationList;
    }

    public function formatErrors(): array
    {
        $errors = [];

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
        foreach ($this->violationList as $violation) {
            $errors[] = [
                'tag'   => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return $errors;
    }
}
