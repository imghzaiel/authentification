<?php

namespace App\listener;

use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationListener implements EventSubscriberInterface {
    public static function getSubscribedEvents()
    {
        return array(
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        );
    }
    public function onAuthenticationFailure(AuthenticationFailureEvent $event) {
        
    }

    public function onAuthenticationSuccess(InteractiveLoginEvent $event) {
        // executes on successful login
    }

}
