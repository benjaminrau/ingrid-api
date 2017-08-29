<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class GenericEntityVoter allows / denies item operations for doctrine entities
 */
class GenericEntityVoter extends Voter
{
	const VIEW = 'API_VIEW';
	const EDIT = 'API_EDIT';
	const CREATE = 'API_CREATE';
	const DELETE = 'API_DELETE';

	/**
	 * @var AccessDecisionManagerInterface
	 */
	private $decisionManager;

	public function __construct(AccessDecisionManagerInterface $decisionManager)
	{
		$this->decisionManager = $decisionManager;
	}

	protected function supports($attribute, $subject)
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::DELETE))) {
			return false;
		}

		return true;
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token = null)
	{
		$user = $token->getUser();

		if (!$user instanceof User) {
			// the user must be logged in; if not, deny access
			return false;
		}

		switch ($attribute) {
			case self::VIEW:
				return $this->canView($subject, $token);
			case self::EDIT:
				return $this->canEdit($subject, $token);
			case self::CREATE:
				return $this->canCreate($subject, $token);
			case self::DELETE:
				return $this->canDelete($subject, $token);
		}

		return false;
	}

	private function canView($subject, TokenInterface $token)
	{
		// if they can edit, they can view
		if ($this->canEdit($subject, $token)) {
			return true;
		}

		return method_exists($subject, 'canUserView') && $subject->canUserView($token);
	}

	private function canCreate($subject, TokenInterface $token)
	{
		return method_exists($subject, 'canUserCreate') && $subject->canUserCreate($token);
	}

    private function canDelete($subject, TokenInterface $token)
    {
        return method_exists($subject, 'canUserDelete') && $subject->canUserDelete($token);
    }

	private function canEdit($subject, TokenInterface $token)
	{
		if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
			return true;
		}

		return method_exists($subject, 'canUserEdit') && $subject->canUserEdit($token);
	}
}
