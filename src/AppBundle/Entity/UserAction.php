<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;

/**
 * UserAction
 *
 * @ORM\Entity
 * @ORM\Table(name="user_actions")
 */
class UserAction
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="request_parameters", type="json_array", nullable=false)
     */
    private $requestParameters;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actionDate", type="datetime", nullable=false)
     */
    private $actionDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="performedUserActions")
     * @ORM\JoinColumn(name="performing_user_id", referencedColumnName="id", nullable=false)
     */
    private $performingUser;

    /**
     * @param Request $request
     * @param User $user
     */
    public function __construct(Request $request, User $performingUser)
    {
        $requestParameters = $request->attributes->all();
        unset($requestParameters['data']);
        $requestParameters['requestUrl'] =  preg_replace('/\?.*/', '', $request->getRequestUri());
        $requestParameters['queryString'] = $request->getQueryString();

        $this->requestParameters = $requestParameters;
        $this->performingUser = $performingUser;
        $this->actionDate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getRequestParameters(): array
    {
        return $this->requestParameters;
    }

    /**
     * @return \DateTime
     */
    public function getActionDate(): \DateTime
    {
        return $this->actionDate;
    }

    /**
     * @return User
     */
    public function getPerformingUser(): User
    {
        return $this->performingUser;
    }
}
