<?php

namespace App\Controller;

use App\DataFixtures\PlaceFixture;
use App\Entity\Place;
use App\Form\Type\PersonType;
use App\Entity\Person;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;

class PersonController extends AbstractController
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
     * @Route("/person", name="person")
     */
    public function new(Request $request)
    {
        $session = $request->getSession();

       $places = $this->getDoctrine()
            ->getRepository(Place::class)
            ->getAll();

        if (empty($places))
        {
            $places = (new PlaceFixture)->load($this->getDoctrine()->getManager());
        }

        $user = $this->security->getUser();

        if (empty($session->get('person'))) {
            $person = new Person();
            $person->setFullname("");
            $person->setBirthdate(new \DateTime());
            $person->setTimezone('Europe/Moscow');
            $person->setUser($user);
            if (!empty($places))
                $person->setPlace($places[count($places) - 1]);
        }
        else{
            $person = $session->get('person');
        }

        return $this->commonSubmitRenderPart(0, $person, $request, $user);
    }
    /**
     * @Route("/person/{id}", name="person_show")
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
        $form = $this->prepareAndCreateNatalForm($person, $natal);
        $entityManager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $person = $form->getData();
            $person->setUser($user);
            $person->setPlace($entityManager->merge($form->get('place')->getData()));
            $request->getSession()->set('person', $person);

            if ($form->get('newplace')->isClicked())
            {
                return $this->redirectToRoute("place");
            }
            if ($id > 0 && ($buttonNewPerson = $form->get('newperson')) && $buttonNewPerson->isClicked())
            {
                $request->getSession()->remove('person');
                return $this->redirectToRoute("person");
            }
            if ((($newName = $person->getFullname()) != "") &&
                !$this->getDoctrine()->getRepository(Person::class)->isAlreadyStored($newName, $id)) {

                $entityManager->persist($person);
                $entityManager->flush();

                return $this->redirectToRoute("person_show", ['id' => $person->getId()]);
            }
        }

        $others = $this->getOthers($id);
        $form->get('place')->setData($entityManager->merge($person->getPlace()));
        $username = "";
        if (!empty($user))
            $username = $user->getName();

        return $this->render('person/index.html.twig', [
            'person' => $person,
            'people' => $others,
            'form' => $form->createView(),
            'username' => $username,
            'lastId' => $id,
            'root_prefix' => $id > 0 ? "../" : "",
            'natal_wheel_path' => $natal->natal_wheel_path,
            'natal_aspect_grid_path' => $natal->natal_aspect_grid_path,
            'natal_explanation' => $id > 0 ?? $natal->natal_explanation,
            'planet_explanation_htmls' => $natal->planet_explanations,
            'sign_explanation_htmls' => $natal->sign_explanations,
            'offset_x_sign_explanation_border' => $natal->offset_x_sign_explanation_border,
        ]);
	}
    public function prepareAndCreateNatalForm(Person $person, &$natal)
    {
        $natal = new Natal;
        $natal->draw($person);
	$name = $person->getFullname();
	$pers = $person;
	$birthdate = $pers->getBirthdate();
	$pers->setFullname("");
	$pers->setBirthdate(new \DateTime(date("Y") . "-" . date("m") . "-" . date("d") 
		. "T" . $birthdate->format('G') . ":" . $birthdate->format('i')));
	$pers->setTimezone('UTC');
	$curr = new Natal;
	$curr->draw($pers);
	$pers->setFullname($name);
        return $this->createForm(PersonType::class, $person);
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
