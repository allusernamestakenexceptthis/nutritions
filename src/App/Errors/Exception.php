<?php

/**
 * Exception handler
 * 例外ハンドラ
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    App\Errors\Exception
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

declare(strict_types=1);

namespace App\Errors;

class Exception extends \Exception
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        $ret = __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        $ret .= "Stack trace:\n" . $this->getTraceAsString() . "\n";
        return $ret;
    }
}
