<?php

namespace App\Controller;

use App\Domain\Opinion\Entity\Opinion;
use App\Domain\Opinion\Factory\OpinionFactory;
use App\Domain\Product\Entity\Product;
use App\Form\OpinionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/opinion")
 * @IsGranted("ROLE_USER")
 */
class OpinionController extends AbstractController
{
    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    /**
     * @Route("/new/{product_id}", name="opinion_new", methods={"GET","POST"})
     * @ParamConverter("product", options={"id"="product_id"})
     */
    public function new(Product $product, Request $request): Response
    {
        $opinionData = OpinionFactory::build($this->getUser(), $product);
        $form = $this->createForm(OpinionType::class, $opinionData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($opinionData);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->renderForm('opinion/new.html.twig', [
            'opinion' => $opinionData,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="opinion_show", methods={"GET"})
     */
    public function show(Opinion $opinion): Response
    {
        return $this->render('opinion/show.html.twig', [
            'opinion' => $opinion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="opinion_edit", methods={"GET","POST"})
     * @IsGranted("POST_EDIT", subject="opinion")
     */
    public function edit(Request $request, Opinion $opinion): Response
    {
        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('opinion/edit.html.twig', [
            'opinion' => $opinion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="opinion_delete", methods={"POST"})
     * @IsGranted("POST_DELETE", subject="opinion")
     */
    public function delete(Request $request, Opinion $opinion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$opinion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($opinion);
            $entityManager->flush();
        }

        $request->headers->get('referer');

        return new RedirectResponse($request->headers->get('referer'));
    }
}
