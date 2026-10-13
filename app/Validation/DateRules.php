<?php

namespace App\Validation;

class DateRules
{
    /** Birthdate must be a real past/today date, not before 1900. */
    public function not_future($value, ?string &$error = null): bool
    {
        if (! is_string($value) || $value > date('Y-m-d') || $value < '1900-01-01') {
            $error = 'Enter a real birthdate (not in the future).';

            return false;
        }

        return true;
    }
}
