<?php

namespace AppBundle\Controller\Account;

use AppBundle\Entity\AccountType;
use AppBundle\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AccountController extends Controller
{
    /**
     * @Route("/account", name="account_homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('accounts/base.html.twig', [

        ]);
    }

    /**
     * @Route("/account/new", name="account_new")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAccountAction(Request $request)
    {
        $accountType = new AccountType();

        $form = $this->createFormBuilder($accountType)
            ->add('name', TextType::class)
            ->add('member_type', ChoiceType::class, [
                'choices' => ['Members' => 0, 'Non members' => 1, 'Both' => 2],
                'choices_as_values' => true,
            ])
            ->add('account_type', ChoiceType::class, [
                'choices' => ['Deposit' => 0, 'Loan' => 1, 'Other' => 2],
                'choices_as_values' => true,
            ])
            ->add('interest_type', ChoiceType::class, [
                'choices' => ['1 month' => 0, '3 month' => 1, '4 month' => 2, 'Yearly' => 3],
                'choices_as_values' => true,
            ])
            ->add('interest', NumberType::class)
            ->add('submit', SubmitType::class, ['label' => 'Add new account'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $connection = $this->getDoctrine()->getManager()->getConnection();

            $query = "INSERT INTO account_type (name, member_type, account_type, interest_type, interest ) VALUES ";
            $query .= "('" . $accountType->getName() . "', " . $accountType->getMemberType() . ", " . $accountType->getAccountType() . ", ";
            $query .= $accountType->getInterestType() . ", " . $accountType->getInterest() . ")";

            $statement = $connection->prepare($query);
            $statement->execute();

            return $this->redirectToRoute('account_all');
        }

        return $this->render('accounts/new_account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/edit/{id}", defaults={"id"=0}, name="account_edit")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAccountAction(Request $request, $id)
    {
        $connection = $this->getDoctrine()->getManager()->getConnection();

        $query = "SELECT * FROM account_type WHERE id=" . $id;
        $statement = $connection->prepare($query);
        $statement->execute();
        $accountType_ = $statement->fetchAll();

        $accountType = new AccountType();
        $accountType->setId($accountType_[0]['id']);
        $accountType->setAccountType($accountType_[0]['account_type']);
        $accountType->setInterest($accountType_[0]['interest']);
        $accountType->setMemberType($accountType_[0]['member_type']);
        $accountType->setName($accountType_[0]['name']);

        $form = $this->createFormBuilder($accountType)
            ->add('name', TextType::class)
            ->add('member_type', ChoiceType::class, [
                'choices' => ['Members' => 0, 'Non members' => 1, 'Both' => 2],
                'choices_as_values' => true,
            ])
            ->add('account_type', ChoiceType::class, [
                'choices' => ['Deposit' => 0, 'Loan' => 1, 'Other' => 2],
                'choices_as_values' => true,
            ])
            ->add('interest_type', ChoiceType::class, [
                'choices' => ['1 month' => 0, '3 month' => 1, '4 month' => 2, 'Yearly' => 3],
                'choices_as_values' => true,
            ])
            ->add('interest', NumberType::class)
            ->add('submit', SubmitType::class, ['label' => 'Change account'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $query = "UPDATE account_type SET ";
            $query .= "name='" . $accountType->getName() . "', ";
            $query .= "member_type=" . $accountType->getMemberType() . ", ";
            $query .= "account_type=" . $accountType->getAccountType() . ", ";
            $query .= "interest_type=" . $accountType->getInterestType() . ", ";
            $query .= "interest=" . $accountType->getInterest() . " ";
            $query .= "WHERE id=" . $id;

            $statement = $connection->prepare($query);
            $statement->execute();

            return $this->redirectToRoute('account_all');
        }

        return $this->render('accounts/new_account.html.twig', [
            'form' => $form->createView(), 'edit' => true,
        ]);

    }

    /**
     * @Route("/account/all", name="account_all")
     */
    public function accountAllAction(Request $request)
    {
        $connection = $this->getDoctrine()->getManager()->getConnection();

        $query = "SELECT * FROM account_type";

        $statement = $connection->prepare($query);
        $statement->execute();
        $accounts = $statement->fetchAll();

        return $this->render('accounts/all.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * @Route("/account/view/{id}", defaults={"id"=0}, name="account_view")
     */
    public function accountViewAction(Request $request, $id){

        $connection = $this->getDoctrine()->getManager()->getConnection();

        $query = "SELECT * FROM account_type WHERE id=" . $id;

        $statement = $connection->prepare($query);
        $statement->execute();
        $account = $statement->fetchAll();

        return $this->render('accounts/view.html.twig', [
            'account' => $account[0],
        ]);
    }
}
