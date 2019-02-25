<?php
/**
 * @see       https://github.com/myzendframework/zend-expressive-prg for the canonical source repository
 * @copyright Copyright (c) 2019 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-flash/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Prg;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Session\SessionInterface;
use Zend\Stdlib\ResponseInterface;

interface PostRedirectGetInterface
{
    /**
     * POST values
     */
    public const PRG_POST = self::class . '::PRG_POST';

    /**
     * Create an instance from a session container.
     *
     * POST data will be retrieved from and persisted to the session via
     * the `$sessionKey`.
     *
     * @param ServerRequestInterface $request
     * @param UrlHelper $urlHelper
     * @param SessionInterface $session
     * @param string $sessionKey
     * @return PostRedirectGetInterface
     */
    public static function createFromSession(
        ServerRequestInterface $request,
        UrlHelper $urlHelper,
        SessionInterface $session,
        string $sessionKey = self::PRG_POST
    ): PostRedirectGetInterface;

    /**
     * Perform PRG logic
     *
     * If a null value is present for the $redirect, the current route is
     * retrieved and use to generate the URL for redirect.
     *
     * If the request method is POST, POST values are stored in session and
     * made available for the next hop. It then redirects to the specified
     * URL using a status 303.
     *
     * If the request method is GET, checks to see if we have values in the
     * session, and, if so, return them; otherwise, it returns a boolean false.
     *
     * @param string|NULL $redirect
     * @param bool $redirectToUrl
     * @return bool|mixed|ResponseInterface
     */
    public function prg(string $redirect = null, bool $redirectToUrl = false);
}
