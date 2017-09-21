<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAction;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserActionSubscriber counts api usage for users
 */
final class UserActionSubscriber implements EventSubscriberInterface
{
	/**
	 * @var TokenStorage
	 */
	protected $tokenStorage;

    /**
     * @var EntityManager
     */
    private $entityManager;

	public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager)
	{
		$this->tokenStorage = $tokenStorage;
		$this->entityManager = $entityManager;
	}

	public static function getSubscribedEvents()
	{
		return array(
			KernelEvents::VIEW => array(
				array('checkUserActionQuota', 80),
				array('createUserAction', 66),
			)
		);
	}

    /**
     * @param $subject
     * @return boolean
     */
	public static function appliesToEntity($subject)
    {
        return ($subject instanceof Product);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * @return mixed
     */
    protected function determineSubject(GetResponseForControllerResultEvent $event)
    {
        $subject = $event->getControllerResult();

        if ($subject === null || ($subject instanceof Paginator && $subject->count() === 0) || (is_array($subject) && count($subject) === 0)) {
            return null;
        }

        if ($subject instanceof Paginator) {
            $subject = $subject->getIterator()->current();
        } elseif (true === is_array($subject)) {
            $subject = current($subject);
        }

        return $subject;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * @return void
     */
    public function checkUserActionQuota(GetResponseForControllerResultEvent $event)
    {
        if (!$this->tokenStorage->getToken() || !$this::appliesToEntity($this->determineSubject($event))) {
            return;
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (true === $user->isUserActionQuotaExceeded($event->getRequest())) {
            throw new AccessDeniedException(sprintf("Quota of %s UserActions within %s exceeded.", $user->getUserActionQuota(), $user->getUserActionQuotaDateInterval()));
        }
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * @return void
     */
	public function createUserAction(GetResponseForControllerResultEvent $event)
	{
        if (!$this->tokenStorage->getToken() || !$this::appliesToEntity($this->determineSubject($event))) {
            return;
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $userAction = new UserAction($event->getRequest(), $user);
        $this->entityManager->persist($userAction);
        $this->entityManager->flush($userAction);

        $user->addPerformedUserAction($userAction);
    }
}
