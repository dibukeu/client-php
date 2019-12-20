<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 22.08.2017
 * Time: 12:52
 */

namespace DibukEu\Exceptions;

use Exception;
use Throwable;

class ExceededLimitException extends Exception
{
    /**
     * @var string|null
     */
    public $nextAttemptAvailable = null;

    /**
     * ExceededLimitException constructor.
     * @param array $options
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($options = [], $code = 0, Throwable $previous = null)
    {
        $message = $options['message'];
        $this->nextAttemptAvailable = $options['nextAttemptAvailable'];
        parent::__construct($message, $code, $previous);
    }
}
