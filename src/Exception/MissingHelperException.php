<?php
/**
 * @see       https://github.com/myzendframework/zend-expressive-prg for the canonical source repository
 * @copyright Copyright (c) 2019 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Prg\Exception;

use DomainException;
use Psr\Container\ContainerExceptionInterface;

class MissingHelperException extends DomainException implements
    ContainerExceptionInterface,
    ExceptionInterface
{
}
