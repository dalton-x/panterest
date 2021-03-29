<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $pinRepository): Response
        {  //                                 Trie par ordre descendant
            $pins = $pinRepository->findby([],['createdAt' => 'DESC']);
            return $this->render('pins/index.html.twig',compact('pins'));
        }

    
        /**
     * @Route("/pins/create", name="app_pins_create", methods="GET|POST")
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo): Response
        {
            // initilisateion de l'objet Pin
            $pin = new Pin;
            // Création du formulaire
            $form = $this->createForm(PinType::class, $pin);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $pin->setUser($this->getUser());
                $em->persist($pin);
                $em->flush();

                $this->addFlash('success', 'Epingle créée avec success !');

                return $this->redirectToRoute('app_home');
            }

            // Convertion en vue pour twig
            $form = $form->createView();

            // Envoie vers la vue twig
            return $this->render('pins/create.html.twig', compact('form'));
        }

    
        /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     */
    public function show(Pin $pin): Response
        {
            return $this->render('pins/show.html.twig', compact('pin'));
        }

    
        /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods="GET|PUT")
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $em): Response
        {
            // Création du formulaire + Ajout de la methode PUT
            $form = $this->createForm(PinType::class, $pin, [
                'method'=> 'PUT'
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Epingle éditée avec success !');

                return $this->redirectToRoute('app_home');
            }

            // Convertion en vue pour twig
            $form = $form->createView();

            return $this->render('pins/edit.html.twig', compact('pin','form'));
        }

    
        /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods="DELETE")
     */
    public function delete( Request $request, Pin $pin, EntityManagerInterface $em): Response
        {
            $submittedToken = $request->request->get('token');
            if ($this->isCsrfTokenValid('delete-pin', $submittedToken)) {
                $em->remove($pin);
                $em->flush();

                $this->addFlash('info', 'Epingle supprimée avec success !');
            }
            return $this->redirectToRoute('app_home');
        }
}
