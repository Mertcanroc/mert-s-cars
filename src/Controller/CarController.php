<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\TestDrive;
use App\Form\CarType;
use App\Form\TestDriveType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/car')]
class CarController extends AbstractController
{
    #[Route('/', name: 'app_car_index', methods: ['GET'])]
    public function index(Request $request, CarRepository $carRepository, EntityManagerInterface $entityManager): Response
    {
        // get the value of a query parameter (category)
        $filter = $request->query->get('category');

        if ($filter) {
            $cars = $entityManager->createQuery(
                'SELECT c FROM App\Entity\Car c
             JOIN c.categories cat
             WHERE cat.name = :categoryName'
            )->setParameter('categoryName', $filter)
                ->getResult();
        } else {
            $cars = $carRepository->findAll();
        }
        return $this->render('car/index.html.twig', [
            'cars' => $cars,
            'activeFilter' => $filter,
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if needed (log it or show a message)
                }

                $car->setImage($newFilename);
            }

            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_show', methods: ['GET'])]
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle upload error
                }

                $car->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index');
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/car/{id}/test-drive', name: 'car_test_drive')]  // changed from /proefrit
    public function testDrive(
        Request $request,
        Car $car,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $testDrive = new TestDrive();
        $testDrive->setCar($car);
        $testDrive->setUser($this->getUser());
        $testDrive->setStatus('pending');

        $form = $this->createForm(TestDriveType::class, $testDrive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($testDrive);
            $em->flush();

            // flash message translated
            $this->addFlash('success', 'Test drive requested!');
            return $this->redirectToRoute('app_car_index');
        }

        return $this->render('car/test_drive.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }
}
