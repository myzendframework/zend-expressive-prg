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
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Session\SessionInterface;

class PostRedirectGet implements PostRedirectGetInterface
{
    /**
     * @var null|array
     */
    private $post;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $sessionKey;

    private function __construct(
        ServerRequestInterface $request,
        UrlHelper $urlHelper,
        SessionInterface $session,
        string $sessionKey = PostRedirectGetInterface::PRG_POST
    ) {
        $this->request = $request;
        $this->urlHelper = $urlHelper;
        $this->session = $session;
        $this->sessionKey = $sessionKey;

        $this->preparePost($session, $sessionKey);
    }

    private function preparePost(SessionInterface $session, string $sessionKey)
    {
        if (! $session->has($sessionKey)) {
            return;
        }

        $this->post = $this->session->get($sessionKey);
        $session->unset($sessionKey);
    }

    public static function createFromSession(
        ServerRequestInterface $request,
        UrlHelper $urlHelper,
        SessionInterface $session,
        string $sessionKey = PostRedirectGet::PRG_POST
    ): PostRedirectGetInterface {
        return new self($request, $urlHelper, $session, $sessionKey);
    }

    /**
     * @param string|NULL $redirect
     * @param bool $redirectToUrl
     * @return array|bool|ResponseInterface
     */
    public function __invoke(string $redirect = null, bool $redirectToUrl = false)
    {
        if ('POST' === $this->request->getMethod()) {
            $this->session->set($this->sessionKey, $this->request->getParsedBody());
            return $this->redirect($redirect, $redirectToUrl);
        }

        if (null !== $this->post) {
            $post = $this->post;
            $this->post = null;
            return $post;
        }

        return false;
    }

    private function redirect(string $redirect = null, bool $redirectToUrl = false): ResponseInterface
    {
        if (null === $redirect) {
            $response = new RedirectResponse($this->urlHelper->generate(), 303);
            return $response;
        }

        if (false === $redirectToUrl) {
            $response = new RedirectResponse($this->urlHelper->generate($redirect), 303);
            return $response;
        }

        $response = new RedirectResponse($redirect, 303);

        return $response;
    }

    /**
     * @inheritdoc
     * @see PostRedirectGet::__invoke()
     */
    public function prg(string $redirect = null, bool $redirectToUrl = false)
    {
        return $this($redirect, $redirectToUrl);
    }
}
