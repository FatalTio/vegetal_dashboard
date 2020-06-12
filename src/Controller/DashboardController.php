<?php

namespace App\Controller;

use App\Entity\Dashboard;
use App\Form\DashboardType;
use App\Repository\DashboardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_index", methods={"GET"})
     */
    public function index(DashboardRepository $dashboardRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'dashboards' => $dashboardRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dashboard_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dashboard = new Dashboard();
        $form = $this->createForm(DashboardType::class, $dashboard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dashboard);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_index');
        }

        return $this->render('dashboard/new.html.twig', [
            'dashboard' => $dashboard,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dashboard_show", methods={"GET"})
     */
    public function show(Dashboard $dashboard): Response
    {
        return $this->render('dashboard/show.html.twig', [
            'dashboard' => $dashboard,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dashboard_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Dashboard $dashboard): Response
    {
        $form = $this->createForm(DashboardType::class, $dashboard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dashboard_index');
        }

        return $this->render('dashboard/edit.html.twig', [
            'dashboard' => $dashboard,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dashboard_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Dashboard $dashboard): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dashboard->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dashboard);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_index');
    }

}