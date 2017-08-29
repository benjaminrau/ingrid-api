<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Ma27\ApiKeyAuthenticationBundle\Annotation as Auth;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 * 	   collectionOperations={
 *          "register"={"route_name"="user_register"},
 *     },
 * 	   itemOperations={}
 * )
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    const ROLE_DEFAULT = "ROLE_DEFAULT";
    const ROLE_INGRID_API = "ROLE_INGRID_API";
    const ROLE_INGRID_APPUSER = "ROLE_INGRID_APPUSER";
    const ROLE_INGRID_AGENCY = "ROLE_INGRID_AGENCY";

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Auth\Login
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Auth\Password
     */
    private $password;

    /**
     * @ORM\Column(name="apiKey", type="string", unique=true, nullable=true)
     * @Auth\ApiKey
     */
    private $apiKey;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $roles;

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    public function eraseCredentials()
    {
    }
}
