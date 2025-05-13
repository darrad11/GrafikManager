<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employee', name: 'employee_')]
class EmployeeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $employees = $em->getRepository(Employee::class)->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $employee = new Employee();

        $form = $this->createFormBuilder($employee)
            ->add('name')
            ->add('surname')
            ->add('contractType')
            ->add('monthlyHours')
            ->add('active')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($employee);
            $em->flush();

            return $this->redirectToRoute('employee_index');
        }

        return $this->render('employee/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Employee $employee, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($employee)
            ->add('name')
            ->add('surname')
            ->add('contractType')
            ->add('monthlyHours')
            ->add('active')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('employee_index');
        }

        return $this->render('employee/edit.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Employee $employee, EntityManagerInterface $em): Response
    {
        $em->remove($employee);
        $em->flush();
        return $this->redirectToRoute('employee_index');
    }
}
