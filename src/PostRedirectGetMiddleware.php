<?php
/**
 * @see       https://github.com/myzendframework/zend-expressive-prg for the canonical source repository
 * @copyright Copyright (c) 2019 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Prg;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Session\SessionMiddleware;

class PostRedirectGetMiddleware implements MiddlewareInterface
{
    public const PRG_ATTRIBUTE = 'PRG';

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * @var string
     */
    private $attributeKey;

    /**
     * @var callable
     */
    private $postRedirectGetFactory;

    /**
     * @var string
     */
    private $sessionKey;

    public function __construct(
        UrlHelper $urlHelper,
        string $postRedirectGetClass = PostRedirectGet::class,
        string $sessionKey = PostRedirectGetInterface::PRG_POST,
        string $attributeKey = self::PRG_ATTRIBUTE
    ) {

        $this->urlHelper = $urlHelper;
        $this->postRedirectGetFactory = [$postRedirectGetClass, 'createFromSession'];
        $this->sessionKey = $sessionKey;
        $this->attributeKey = $attributeKey;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE, false);
        if (! $session instanceof SessionInterface) {
            throw Exception\MissingSessionException::forMiddleware($this);
        }

        $postRedirectGet = ($this->postRedirectGetFactory)($request, $this->urlHelper, $session, $this->sessionKey);

        return $handler->handle($request->withAttribute($this->attributeKey, $postRedirectGet));
    }
}
