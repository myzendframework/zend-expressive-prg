<?php
/**
 * @see       https://github.com/myzendframework/zend-expressive-prg for the canonical source repository
 * @copyright Copyright (c) 2019 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Prg\Exception;

use Psr\Http\Server\MiddlewareInterface;
use RuntimeException;
use function get_class;
use function sprintf;

class MissingSessionException extends RuntimeException implements ExceptionInterface
{
    public static function forMiddleware(MiddlewareInterface $middleware)
    {
        return new self(sprintf(
            'Unable to create prg in %s; missing session attribute',
            get_class($middleware)
        ));
    }
}
