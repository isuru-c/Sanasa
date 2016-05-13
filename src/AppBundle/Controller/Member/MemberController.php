<?php

namespace AppBundle\Controller\Member;

use AppBundle\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MemberController extends Controller
{
    /**
     * @Route("/member", name="member_homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('members/base.html.twig', [

        ]);
    }

    /**
     * @Route("/member/new/person", name="member_new_person")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addMemberAction(Request $request){

        $person = new Person();

        $form = $this->createFormBuilder($person)
            ->add('nic_number', TextType::class)
            ->add('full_name', TextType::class)
            ->add('starting_date', DateType::class)
            ->add('submit', SubmitType::class, ['label' => 'Add new person'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $connection = $this->getDoctrine()->getManager()->getConnection();

            $query = "INSERT INTO person (nic_number, full_name, starting_date ) VALUES ";
            $query .= "('" . $person->getNicNumber(). "', '" . $person->getFullName() . "', '" . $person->getStartingDate()->format('Y-m-d') . "')";

            $statement = $connection->prepare($query);
            $statement->execute();

            return $this->redirectToRoute('member_all');
        }

        return $this->render('members/new_person.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/member/all", name="member_all")
     */
    public function memberAllAction(Request $request)
    {
        $connection = $this->getDoctrine()->getManager()->getConnection();

        $query = "SELECT * FROM person";

        $statement = $connection->prepare($query);
        $statement->execute();
        $person = $statement->fetchAll();

        return $this->render('members/all.html.twig', [
            'persons' => $person,
        ]);
    }

    /**
     * @Route("/member/new/member/{nic}", defaults={"nic"=0}, name="person_to_member")
     */
    public function personToMemberAction(Request $request){

    }
}
