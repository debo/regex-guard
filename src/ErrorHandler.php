<?php

namespace RegexGuard;

use RegexGuard\Interfaces\ErrorHandlerInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    protected $enabled = false;

    public function enable($throwOnError = true, $severity = E_WARNING)
    {
        if (! $this->enabled) {
            $this->enabled = true;
            return set_error_handler(function ($errno, $errstr) use ($severity, $throwOnError) {
                $this->disable();
                if($throwOnError) throw new RegexException(str_replace('preg_', '', $errstr));
                return true;
            }, $severity);
        }

        return false;
    }

    public function disable()
    {
        if ($this->enabled) {
            $this->enabled = false;
            return restore_error_handler(); // cleans temporary error handler
        }

        return false;
    }
}