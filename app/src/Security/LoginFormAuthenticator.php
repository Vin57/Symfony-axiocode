<?php

namespace App\Security;

use App\Form\LoginData;
use App\Form\LoginType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $login = $password = $token = '';
        /** @var LoginData $data */
        if ($data = $request->request->get('login')) {
            $login = $data['login'];
            $password = $data['password'];
            $token = $data['_csrf_token'];
        }
        $request->getSession()->set(Security::LAST_USERNAME, $login);
        return new Passport(
            new UserBadge($login),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $token),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->urlGenerator->generate('product_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
