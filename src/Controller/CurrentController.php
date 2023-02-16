<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;

class CurrentController extends AbstractController
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
     * @Route("/current", name="current_show")
     */
    public function show(Request $request)
    {
        return $this->commonSubmitRenderPart();

    }
    public function commonSubmitRenderPart() {
	$person = $this->getDoctrine()
            ->getRepository(Person::class)
            ->find(5);
	$birthdate = $person->getBirthdate();
	$person->setFullname("");
	$person->setBirthdate(new \DateTime(date("Y") . "-" . date("m") . "-" . date("d") 
		. "T" . $birthdate->format('G') . ":" . $birthdate->format('i')));
	$curr = new Natal;
	$curr->draw($person);
	 return $this->render('current/index.html.twig', [
            'current_wheel_path' => $curr->natal_wheel_path,
        ]);
      
    }
}
