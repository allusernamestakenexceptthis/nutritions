<?php
declare(strict_types=1);

namespace Gomilkyway\Nutrition\Errors;

/**
 * Exception handler
 *
 * @copyright  Copyright (C) Gomilkyway (https://gomilkyway.com)
 * @package    Gomilkyway\Nutrition\Errors\Exception
 * @author     Adari ARi
 * @version    0.1.0
 * @license    MIT License (https://opensource.org/licenses/mit-license.php)
 */

 class Exception extends \Exception
 {
     public function __construct(string $message, int $code = 0, \Throwable $previous = null)
     {
         parent::__construct($message, $code, $previous);
     }
 }
