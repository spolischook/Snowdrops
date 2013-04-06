<?php

namespace Tonic\UserBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TonicUserRequestListener
{
    protected $redirectController;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        /** @var Request $request  */
        $request = $event->getRequest();
        /** @var Session $session  */
        $session = $request->getSession();

        if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
            return;
        }
        elseif (!$request->query->get('ref')) {
            return;
        }

        $this->setReferralInfoToUserSession($request, $session);
        $this->setResponseRedirectWithoutParameter($request, $event);
    }

    private function setResponseRedirectWithoutParameter(Request $request, GetResponseEvent $event)
    {
        $url = $this->router->generate($request->get('_route'));
        $event->setResponse(new RedirectResponse($url));
    }

    private function setReferralInfoToUserSession(Request $request, Session $session)
    {
        $session->set('visit_from_ref_link', true);
        $session->set('client_ip', $request->getClientIp());
        $session->set('referral_ref_id', $request->query->get('ref'));
        $session->set('referer', $request->headers->get('referer'));
        $session->set('ref_date', new \DateTime());
    }
}