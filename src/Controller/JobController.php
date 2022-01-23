<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\FilterType;
use App\Repository\JobRepository;
use App\Services\UpdateDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    #[Route('/', name: 'job_index', methods: ['GET', 'POST'])]
    public function index(JobRepository $jobRepository, Request $request, UpdateDatabase $updateDatabase): Response
    {
        $form = $this->createForm(FilterType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cities = [];
            $contractType = [];
            $contractNature = [];
            $t = $request->request->all();
            foreach ($t["filter"] as $key => $value) {
                if ($key == 'Paris' || $key == 'Bordeaux' || $key == 'Rennes') {
                    $cities[] = $value;
                    if ($key == 'Paris') {
                        $cities = [
                            ...$cities,
                            "75", 
                            "75 - Paris (Dept.)", 
                            "75 - PARIS 01",
                            "75 - PARIS 02",
                            "75 - PARIS 03",
                            "75 - PARIS 04",
                            "75 - PARIS 05",
                            "75 - PARIS 06",
                            "75 - PARIS 07",
                            "75 - PARIS 08",
                            "75 - PARIS 09",
                            "75 - PARIS 10",
                            "75 - PARIS 11",
                            "75 - PARIS 12",
                            "75 - PARIS 13",
                            "75 - PARIS 14",
                            "75 - PARIS 15",
                            "75 - PARIS 16",
                            "75 - PARIS 17",
                            "75 - PARIS 18",
                            "75 - PARIS 19",
                            "75 - PARIS 20" 
                        ];                                              
                    }
                }

                if ($key == 'CDI' || $key == 'CDD' || $key == 'interim' || $key == 'saisonnier') {
                    $contractType[] = $value;
                }

                if ($key == 'professionnalisation' || $key == 'travail') {
                    $contractNature[] = $value;
                }

                if ($key == 'update') {
                    $updateDatabase->refreshJobTable();
                }
            }
            $jobs = $jobRepository->findWithFilter($cities, $contractType, $contractNature);

            return $this->render('job/index.html.twig', [
                'jobs' => $jobs,
                'form' => $form->createView()
            ]);
        }

        if ($jobs = $jobRepository->findBy([],['updateDate' => 'desc'])) {
            return $this->render('job/index.html.twig', [
                'jobs' => $jobs,
                'form' => $form->createView()
            ]);
        }

        $updateDatabase->refreshJobTable();

        return $this->render('job/index.html.twig', [
            'jobs' => $jobRepository->findBy([],['updateDate' => 'desc']),
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'job_show', methods: ['GET'])]
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }
}
