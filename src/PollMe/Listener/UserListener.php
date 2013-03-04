<?php

namespace PollMe\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Rock\Http\Event\GetResponseEvent;
use Rock\Http\KernelEvents;

use PollMe\Entity\UserRepository;


class UserListener implements EventSubscriberInterface
{
    protected $repository;


    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 30)),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has('_user')) {
            // the user has already been created
            return;
        }

        if ($id = $request->getSession()->get('id')) {
            $user = $this->repository->findById($request->getSession()->get('id'));
            if ($user !== null) {
                $request->attributes->set('_user', $user);
            }
        }
    }
}
