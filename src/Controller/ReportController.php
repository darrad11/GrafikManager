<?php

namespace App\Controller;

use App\Entity\Employee;
use DateTime;
use DateInterval;
use App\Repository\EmployeeRepository;
use App\Repository\ScheduleRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/report', name: 'report_')]
class ReportController extends AbstractController
{
    #[Route('/week/{week}', name: 'week')]
    public function weeklyReport(int $week, Request $request, EmployeeRepository $employeeRepo, ScheduleRepository $scheduleRepo): Response
    {
        $isPdf = $request->query->get('pdf') === 'true';

        $year = (int) date('Y');
        $firstDayOfWeek = new DateTime();
        $firstDayOfWeek->setISODate($year, $week);

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = clone $firstDayOfWeek->add(new DateInterval('P1D'));
        }

        $employees = $employeeRepo->findAll();
        $schedules = $scheduleRepo->findBy(['week' => $firstDayOfWeek]);

        $scheduleMap = [];
        foreach ($schedules as $schedule) {
            $weekNumber = (int) $schedule->getWeek()->format("W");
            if ($weekNumber === $week) {
                $employee = $schedule->getEmployee();
                $scheduleMap[$employee->getId()][$schedule->getWeek()->format("Y-m-d")] = $schedule->getShifts();
            }
        }

        if ($isPdf) {
            return $this->generatePdf(
                $this->renderView('report/week.html.twig', [
                    'week' => $week,
                    'year' => $year,
                    'employees' => $employees,
                    'schedule' => $scheduleMap,
                    'isPdf' => true,
                    'dates' => $dates,
                ])
                , 'landscape'
            );
        }

        return $this->render('report/week.html.twig', [
            'week' => $week,
            'year' => $year,
            'employees' => $employees,
            'schedule' => $scheduleMap,
            'isPdf' => false,
            'dates' => $dates,
        ]);
    }

    #[Route('/employee/{id}', name: 'employee_report')]
    public function employeeReport(Employee $employee, ScheduleRepository $scheduleRepo, Request $request): Response
    {
        $isPdf = $request->query->get('pdf') === 'true';

        $lastReport = $scheduleRepo->findLastReportByEmployee($employee);

        if ($isPdf) {
            return $this->generateEmployeePdfReport($employee);
        }

        return $this->render('report/employee.html.twig', [
            'employee' => $employee,
            'lastReport' => $lastReport,
        ]);
    }

    private function generatePdfReport(int $week, ScheduleRepository $scheduleRepo): Response
    {
        $schedules = $scheduleRepo->findBy(['week' => $week]);

        $scheduleMap = [];

        foreach ($schedules as $schedule) {
            $employeeId = $schedule->getEmployee()->getId();
            foreach ($schedule->getShifts() as $shift) {
                $startTime = $shift->getStartTime();
                $endTime = $shift->getEndTime();

                // Obliczanie casu pracy
                $interval = $startTime->diff($endTime);
                $shiftDuration = $interval->format('%h:%i');

                $scheduleMap[$employeeId][$shift->getDate()] = $shiftDuration;
            }
        }

        $html = $this->renderView('report/week.html.twig', [
            'week' => $week,
            'schedule' => $scheduleMap,
        ]);

        return $this->generatePdf($html);
    }

    private function generateEmployeePdfReport(Employee $employee,): Response
    {
        $html = $this->renderView('report/employee.html.twig', [
            'employee' => $employee,
        ]);

        return $this->generatePdf($html);
    }

    private function generatePdf(string $html, string $orientation = 'portrait'): Response
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', $orientation);
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="report.pdf"',
            ]
        );
    }
}
