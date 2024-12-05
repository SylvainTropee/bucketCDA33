<?php

namespace App\Controller;

use App\Form\Model\SearchEvent;
use App\Form\SearchEventType;
use App\Services\EventApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/events', name: 'event_list')]
    public function list(
        EventApiService $eventApiService,
        Request $request
    ): Response
    {
        $searchEvent = new SearchEvent();
        $searchEventForm = $this->createForm(SearchEventType::class, $searchEvent);

        $searchEventForm->handleRequest($request);


        $events = $eventApiService->search($searchEvent);

        return $this->render('event/list.html.twig', [
            'events' => $events['records'],
            'searchEventForm' => $searchEventForm
        ]);
    }
}
