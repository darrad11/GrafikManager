<?php

namespace App\Controller;

use App\Entity\Schedule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EmployeeRepository;
use App\Repository\ScheduleRepository;

#[Route('/schedule', name: 'schedule_')]
class ScheduleController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function redirectToCurrentWeek(): Response
    {
        $currentWeek = (int) date('W');
        return $this->redirectToRoute('schedule_edit', ['week' => $currentWeek]);
    }

    #[Route('/week/{week<\d+>}', name: 'view')]
    public function view(int $week = null, ScheduleRepository $scheduleRepo): Response
    {
        $week ??= (int) date('W');
        $schedules = $scheduleRepo->findBy(['weekNumber' => $week]);

        return $this->render('schedule/view.html.twig', [
            'week' => $week,
            'schedules' => $schedules,
        ]);
    }

    #[Route('/week/{week<\d+>}/edit', name: 'edit')]
    public function edit(int $week, Request $request, EmployeeRepository $employeeRepo, ScheduleRepository $scheduleRepo): Response
    {
        $employees = $employeeRepo->findAll();
        $scheduleData = [];
        return $this->render('schedule/edit.html.twig', [
            'week' => $week,
            'employees' => $employees,
            'schedule' => $scheduleData,
        ]);
    }

    #[Route('/week/{week<\d+>}/print', name: 'print')]
    public function print(int $week, ScheduleRepository $scheduleRepo): Response
    {
        $schedules = $scheduleRepo->findBy(['weekNumber' => $week]);

        return $this->render('schedule/print.html.twig', [
            'week' => $week,
            'schedules' => $schedules,
        ]);
    }
}
