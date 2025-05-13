<?php

namespace App\Controller;

use App\Entity\ShiftTemplate;
use App\Repository\ShiftRepository;
use App\Repository\ShiftTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shift/template', name: 'shift_template_')]
class ShiftTemplateController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ShiftTemplateRepository $templateRepo): Response
    {
        return $this->render('shift_template/index.html.twig', [
            'templates' => $templateRepo->findAll(),
        ]);
    }

    #[Route('/copy-from-week/{week}', name: 'copy_from_week')]
    public function copyFromWeek(
        int $week,
        ShiftRepository $shiftRepo,
        EntityManagerInterface $em
    ): Response {
        $shifts = $shiftRepo->findByWeek($week);
        foreach ($shifts as $shift) {
            $template = new ShiftTemplate();
            $template->setEmployee($shift->getEmployee());
            $template->setStartTime($shift->getStartTime());
            $template->setEndTime($shift->getEndTime());
            $template->setDayOfWeek((int)$shift->getStartTime()->format('N'));

            $em->persist($template);
        }

        $em->flush();

        $this->addFlash('success', "Skopiowano grafik z tygodnia $week do szablonu.");
        return $this->redirectToRoute('shift_template_index');
    }
}
