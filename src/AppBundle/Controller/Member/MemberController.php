<?php

namespace AppBundle\Controller\Member;

use AppBundle\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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

        $query = "SELECT * FROM person WHERE nic_number NOT IN (SELECT DISTINCT nic_number FROM members)";

        $statement = $connection->prepare($query);
        $statement->execute();
        $person = $statement->fetchAll();

        $query = "SELECT person.full_name, person.nic_number, members.starting_date, members.membership_number FROM members LEFT OUTER JOIN person ON (members.nic_number=person.nic_number)";

        $statement = $connection->prepare($query);
        $statement->execute();
        $members = $statement->fetchAll();

        return $this->render('members/all.html.twig', [
            'persons' => $person, 'members' => $members,
        ]);
    }

    /**
     * @Route("/member/new/member/{nic_number}", defaults={"nic_number"=0}, name="person_to_member")
     */
    public function personToMemberAction(Request $request, $nic_number){

        $connection = $this->getDoctrine()->getManager()->getConnection();

        $query = "SELECT * FROM person WHERE nic_number='" . $nic_number . "'";

        $statement = $connection->prepare($query);
        $statement->execute();
        $person = $statement->fetchAll();

        $form = $this->createFormBuilder()
            ->add('membership_number', NumberType::class)
            ->add('starting_date', DateType::class)
            ->add('submit', SubmitType::class, ['label' => 'Add member'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $connection = $this->getDoctrine()->getManager()->getConnection();

            $query = "INSERT INTO members (membership_number, nic_number, starting_date, state) VALUES ";
            $query .= "(" . $data['membership_number'] . ", '" . $nic_number . "', '" . $data['starting_date']->format('Y-m-d') . "', " . 0 . ")";

            $statement = $connection->prepare($query);
            $statement->execute();

            return $this->redirectToRoute('member_all');
        }

        return $this->render('members/new_member.html.twig', [
            'form' => $form->createView(), 'person' => $person[0],
        ]);
    }


}
