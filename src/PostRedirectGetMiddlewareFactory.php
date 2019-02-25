<?php
/**
 * @see       https://github.com/myzendframework/zend-expressive-prg for the canonical source repository
 * @copyright Copyright (c) 2019 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Prg;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

class PostRedirectGetMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): PostRedirectGetMiddleware
    {
        if (! $container->has(UrlHelper::class)) {
            throw new Exception\MissingHelperException(sprintf(
                '%s requires a %s service at instantiation; none found',
                PostRedirectGetMiddleware::class,
                UrlHelper::class
            ));
        }

        return new PostRedirectGetMiddleware(
            $container->get(UrlHelper::class)
        );
    }
}
