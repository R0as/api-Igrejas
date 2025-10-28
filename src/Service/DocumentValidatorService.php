<?php

namespace App\Service;

class DocumentValidatorService
{
    /**
     * Valida o tipo e o formato de um documento (CPF ou CNPJ).
     */
    public function validate(string $documentType, string $documentNumber): bool
    {
        if (!in_array($documentType, ['CPF', 'CNPJ'])) {
            return false;
        }

        $pattern = '';
        if ($documentType === 'CPF') {
            $pattern = '/^\d{3}\.\d{3}\.\d{3}-\d{2}$/';
        } elseif ($documentType === 'CNPJ') {
            $pattern = '/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/';
        }
        return (bool) preg_match($pattern, $documentNumber);
    }
}