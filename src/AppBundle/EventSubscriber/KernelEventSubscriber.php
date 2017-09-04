<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class KernelEventSubscriber allows or denies item operations based on user privileges
 */
final class KernelEventSubscriber implements EventSubscriberInterface
{
	/**
	 * @var ManagerRegistry
	 */
	private $managerRegistry;

	/**
	 * @var AuthorizationCheckerInterface
	 */
	private $authorizationChecker;

	/**
	 * @var TokenStorage
	 */
	protected $tokenStorage;

	/**
	 * @var Container
	 */
	protected $container;

	public function __construct(ManagerRegistry $managerRegistry, AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage, Container $container)
	{
		$this->managerRegistry = $managerRegistry;
		$this->authorizationChecker = $authorizationChecker;
		$this->tokenStorage = $tokenStorage;
		$this->container = $container;
	}

	public static function getSubscribedEvents()
	{
		return array(
			KernelEvents::VIEW => array(
				array('checkPermission', 80),
			)
		);
	}

    /**
     * @param GetResponseForControllerResultEvent $event
     * @return boolean
     */
	public function checkPermission(GetResponseForControllerResultEvent $event)
	{
		$subject = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();

		if ($subject === null) {
			return true;
		}

		if ($subject instanceof Paginator or is_array($subject)) {
			return true;
		}

		if (!$this->tokenStorage->getToken() && 0 !== strpos($event->getRequest()->getPathInfo(), "/api/" . $this->container->getParameter("app.api_public_path"))) {
			throw new AccessDeniedException("The api doesnt support unauthorized requests.");
		}

		if ($this->tokenStorage->getToken()) {
            if (Request::METHOD_GET === $method) {
                if (false === $this->authorizationChecker->isGranted('API_VIEW', $subject)) {
                    throw new AccessDeniedException("Access denied for API_VIEW on: " . get_class($subject));
                }
            }

            if (Request::METHOD_PUT === $method) {
                if (false === $this->authorizationChecker->isGranted('API_EDIT', $subject)) {
                    throw new AccessDeniedException("Access denied for API_EDIT on: " . get_class($subject));
                }
            }

            if (Request::METHOD_POST === $method) {
                if (false === $this->authorizationChecker->isGranted('API_CREATE', $subject)) {
                    throw new AccessDeniedException("Access denied for API_CREATE on: " . get_class($subject));
                }
            }

            if (Request::METHOD_DELETE === $method) {
                if (false === $this->authorizationChecker->isGranted('API_DELETE', $subject)) {
                    throw new AccessDeniedException("Access denied for API_DELETE on: " . get_class($subject));
                }
            }
        }

        return true;
    }
}
