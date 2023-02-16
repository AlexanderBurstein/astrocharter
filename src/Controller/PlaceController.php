<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\Type\PlaceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/place", name="place")
     */
    public function new(Request $request)
    {
        $empty_latitude = "N45.00.00";
        $place = new Place();
        $place->setPlacename("");
        $place->setLatitude($empty_latitude);
        $place->setLongitude($empty_latitude);
        $isStored = false;

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $place = $entityManager->merge($form->getData());
            if (!(empty($place->getId()) && $this->getDoctrine()->getRepository(Place::class)->isAlreadyStored($place->getPlacename())) &&
                ($place->getLongitude() != $empty_latitude && $place->getLatitude() != $empty_latitude)) {

                $entityManager->persist($place);
                $entityManager->flush();

                $session = $request->getSession();
                $lastPerson = $session->get('person');
                $lastPersonId = 0;
                if (!empty($lastPerson)) {
                    $lastPerson->setPlace($place);
                    $session->set('person', $lastPerson);
                    $lastPersonId = $lastPerson->getId();
                }
                if ($lastPersonId > 0)
                    return $this->redirectToRoute("person_show", ['id' => $lastPersonId]);
                else
                    return $this->redirectToRoute("person");
            }
            else
                $isStored = true;
        }
        
        return $this->render('place/index.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
            'used_place' => $isStored
        ]);
    }
    /**
    * @Route("/place/{id}", name="place_show")
    */
    public function show($id, Request $request)
    {
        $place = $this->getDoctrine()
            ->getRepository(Place::class)
            ->find($id);

        if (!$place) {
            throw $this->createNotFoundException(
                'No place found for id '.$id
            );
        }

        $isStored = false;
        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $place = $entityManager->merge($form->getData());

            if (!($this->getDoctrine()->getRepository(Place::class)->isAlreadyStored($place->getPlacename(), $id))) {
                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($place);
                $entityManager->flush();

                $session = $request->getSession();
                $lastPerson = $session->get('person');
                $lastPersonId = 0;
                if (!empty($lastPerson)) {
                    $lastPerson->setPlaceId($place->getId());
                    $session->set('person', $lastPerson);
                    $lastPersonId = $lastPerson->getId();
                }
                if ($lastPersonId > 0)
                    return $this->redirectToRoute("person_show", ['id' => $lastPersonId]);
                else
                    return $this->redirectToRoute("person"); }
            else
                $isStored = true;
        }
        return $this->render('place/index.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
            'used_place' => $isStored
        ]);
    }
}
