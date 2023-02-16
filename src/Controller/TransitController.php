<?php

namespace App\Controller;

use App\DataFixtures\PlaceFixture;
use App\Entity\Place;
use App\Form\Type\TransitType;
use App\Entity\Person;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;

class TransitController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/transit/{id}", name="transit_show")
     */
    public function show($id, Request $request)
    {
        $person = $this->getDoctrine()
            ->getRepository(Person::class)
            ->find($id);

        if (!$person) {
            return $this->redirectToRoute("person");
        }

        $user = $this->security->getUser();

        if(empty($user) || $person->getUser()->getId() != $user->getId())
        {
            return $this->redirectToRoute("person");
        }
        return $this->commonSubmitRenderPart($id, $person, $request, $user);

    }
    public function commonSubmitRenderPart($id, Person $person, Request $request, ?User $user) {
        $form = $this->prepareAndCreateTransitForm($person, $transit);
        $entityManager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $person = $form->getData();
            $person->setUser($user);
            $person->setPlace($entityManager->merge($form->get('place')->getData()));
            $request->getSession()->set('person', $person);

            if ((($newName = $person->getFullname()) != "") &&
                !$this->getDoctrine()->getRepository(Person::class)->isAlreadyStored($newName, $id)) {

                $entityManager->persist($person);
                $entityManager->flush();

                return $this->redirectToRoute("transit_show", ['id' => $person->getId()]);
            }
        }

        $others = $this->getOthers($id);
        $form->get('place')->setData($entityManager->merge($person->getPlace()));
        $username = "";
        if (!empty($user))
            $username = $user->getName();

        return $this->render('transit/index.html.twig', [
            'person' => $person,
            'people' => $others,
            'form' => $form->createView(),
            'username' => $username,
            'lastId' => $id,
            'root_prefix' => $id > 0 ? "../" : "",
            'natal_wheel_path' => $transit->transit_wheel_path,
        ]);
      
    }
    public function prepareAndCreateTransitForm(Person $person, &$transit)
    {
        $transit = new Transit;
        $transit->draw($person);
        return $this->createForm(TransitType::class, $person);
    }
    private function getOthers($id)
    {
        $user = $this->security->getUser();
        if (empty($user))
            return array();
        $people = $user->getPeople();
        $urls = array();
        foreach($people as $other)
        {
            if($other->getId() != $id)
            {
                $urls[$other->getId()] = $other->getFullname();
            }
        }
        return $urls;
    }
}
