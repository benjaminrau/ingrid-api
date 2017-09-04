<?php

namespace AppBundle\Action;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserAction
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var Container
     */
    private $container;

	public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage, Container $container) {
		$this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->container = $container;
	}

	/**
     * @param User $data
	 * @return Response
	 *
	 * @Route(
	 *     name="user_register",
	 *     path="/api/public/register",
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="register"}
	 * )
	 * @Method("POST")
	 */
	public function __invoke($data)
    {
        if (!$userRegistrationSecret = $this->validateAndGetUserRegistrationSecret($data))
        {
            throw new AccessDeniedHttpException('Not allowed to register!');
        }

        if ($this->entityManager->getRepository(User::class)->findBy(["username" => $data->getUsername()])) {
            throw new AccessDeniedException('Username is already taken!');
        }

        $data->setRolesByRegistrationSecret($userRegistrationSecret);

        return $data;
    }

    /**
     * @param User $user
     * @return array|bool
     */
	private function validateAndGetUserRegistrationSecret(User $user)
    {
        if (null === $user->getRegistrationSecret()) {
            return false;
        }

        $registrationSecrets = $this->container->getParameter("app.registration_secrets");
        $userRegistrationSecret = array_filter($registrationSecrets, function($registrationSecret) use ($user) {
            return $user->getRegistrationSecret() === $registrationSecret["secret"];
        });

        return empty($userRegistrationSecret) ? false : current($userRegistrationSecret);
    }
}
