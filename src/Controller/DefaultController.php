<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\PaymentTask;
use App\Form\PaymentTaskType;
use App\Model\PaymentScheduler;
use App\Repository\PaymentTaskRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $rep = $entityManager->getRepository(Client::class);
        $clients = $rep->findAll();

        return $this->render('payments/login.html.twig', [
            'users' => $clients,
        ]);
    }

    /**
     * @Route("/{client_id}/add-payment-task", name="add_payment_task", requirements={"client_id"="\d+"})
     */
    public function addPaymentTask(Request $request, LoggerInterface $logger, int $client_id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $rep = $entityManager->getRepository(Client::class);
        /** @var Client $client */
        $client = $rep->find($client_id);

        $task = (new PaymentTask())
            ->setFromClient($client)
            ->setCreatedAt(new \DateTime())
            ->setScheduledfor(new \DateTime());

        $form = $this->createForm(PaymentTaskType::class, $task, ['max_amount' => $client->getBalance()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            try {
                $entityManager->persist($task);
                $entityManager->flush();
                $this->addFlash('success', 'Платеж запланирован!');
                return $this->redirectToRoute('add_payment_task', ['client_id' => $client_id]);
            } catch (\Exception $e) {
                $logger->error('Ошибка при сохранении отложенного платежа!', [$e]);
                $this->addFlash('error', 'При сохранении платежа произошла ошибка!');
            }
        }

        return $this->render('payments/add-payment-task.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }

    /**
     * @Route("/schedule", name="schedule")
     */
    public function schedule(Request $request, LoggerInterface $logger)
    {
        $form = $this->createScheduleForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var \DateTime $date */
            $date = $data['schedule_date'];

            $entityManager = $this->getDoctrine()->getManager();
            /** @var PaymentTaskRepository $tasks */
            $rep = $entityManager->getRepository(PaymentTask::class);

            try {
                $tasks = $rep->findByScheduledFor($date);

                if (count($tasks) > 0) {
                    $scheduler = new PaymentScheduler();
                    $payments = $scheduler->schedule($tasks);

                    if (count($payments) > 0) {
                        foreach ($payments as $payment) {
                            $entityManager->persist($payment);
                        }
                        $entityManager->flush();

                        $this->addFlash('success', 'Платежи проведены успешно!');
                    } else {
                        $this->addFlash('warning', 'Некоторые платежи провести не удалось!');
                    }
                } else {
                    $this->addFlash('warning', 'Платежей на это время не запланировано!');
                }
            } catch (\Exception $e) {
                $logger->error('Ошибка при проведении платежаей!', [$e]);
                $this->addFlash('error', 'При проведении платежей произошла ошибка!');
            }
        }

        return $this->render('payments/schedule.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/clients", name="clients")
     */
    public function getClients(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $rep = $entityManager->getRepository(Client::class);

        /** @var Client[] $clients */
        $clients = $rep->findAll();

        return $this->render('payments/clients.html.twig', [
            'clients' => $clients,
        ]);
    }

    private function createScheduleForm(): FormInterface
    {
        return $this->createFormBuilder(['schedule_date' => new \DateTime()])
            ->add('schedule_date', DateTimeType::class, [
                'label' => 'Дата/время',
                'with_minutes' => false,
            ])
            ->add('run', SubmitType::class, [
                'label' => 'Запустить обработку',
            ])
            ->getForm();
    }
}
