<?php

namespace App\Controller;

use App\Domain\Security\NotificationStatus;
use App\Domain\User\Entity\User;
use App\Domain\User\Form\LoginType;
use App\Domain\User\Form\SigninType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationController extends AbstractController
{
    private TranslatorInterface $translator;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        TranslatorInterface $translator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->translator = $translator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('product_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class, null, ['last_username' => $lastUsername]);
        $form->handleRequest($request);

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/signin", name="signin")
     */
    public function signin(Request $request) {

        $form = $this->createForm(SigninType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(NotificationStatus::SUCCESS, $this->translator->trans('signin.success.you.can.now.login'));
            return $this->redirectToRoute('app_login');
        }

        $form_signin = $form->createView();
        return $this->render('security/signin.html.twig', [
            'form' => $form_signin
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
