<?php

namespace AppBundle\Security;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\PaginatorInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class CollectionOperationVoter allows or denies collection operations based on user privileges
 */
final class CollectionOperationVoter implements CollectionDataProviderInterface
{
	/**
	 * @var TokenStorage
	 */
	protected $tokenStorage;


	/**
	 * @param TokenStorage $tokenStorage
	 */
	public function __construct(TokenStorage $tokenStorage)
	{
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * This collection data provider is a fake to check if user is permitted to get a collection
	 * It will throw ResourceClassNotSupportedException whenever the get collection is allowed
	 * Then we expect another (default) collection data provider to take over
	 * see \AppBundle\EventSubscriber\GenericEntityVoter for permission checks on single entitiy
	 *
	 * @param string $resourceClass
	 * @param string|null $operationName
	 *
	 * @throws ResourceClassNotSupportedException
	 *
	 * @return array|PaginatorInterface|\Traversable
	 */
	public function getCollection(string $resourceClass, string $operationName = null) {
		if (
            $this->tokenStorage->getToken()->getUser() instanceof User &&
            method_exists($resourceClass, 'canUserViewCollection') &&
            $resourceClass::canUserViewCollection($this->tokenStorage->getToken(), $operationName)
		) {
			throw new ResourceClassNotSupportedException();
		}

		throw new AccessDeniedException("Access denied to get collection of: " . $resourceClass);
	}
}
