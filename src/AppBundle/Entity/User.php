<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Ma27\ApiKeyAuthenticationBundle\Annotation as Auth;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * 	   collectionOperations={
 *          "register"={
 *              "route_name"="user_register",
 *              "denormalization_context"={
 *                  "groups"={
 *                      "api_in_default",
 *                      "api_in_register"
 *                  },
 *              },
 *          }
 *     },
 * 	   itemOperations={
 *         "get"={"method"="GET"}
 *     },
 *     attributes={
 *         "denormalization_context"={
 *             "groups"={
 *                 "api_in_default",
 *             },
 *         },
 *         "normalization_context"={
 *             "groups"={
 *                 "api_out_default",
 *             },
 *         },
 *     }
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
     * @ORM\Column(type="string", length=128, unique=true)
     * @Auth\Login
     * @Groups({"api_out_default", "api_in_register"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Auth\Password
     * @Groups({"api_in_register"})
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
     * @Groups({"api_out_default"})
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Groups({"api_in_register"})
     */
    private $registrationSecret;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = substr($username, 0, 128);
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * @param $role
     */
    public function addRole($role)
    {
        if (false === array_search($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function setRolesByRegistrationSecret($registrationSecret)
    {
        $this->roles = [];

        if (!empty($registrationSecret["roles"])) {
            foreach ($registrationSecret["roles"] AS $role) {
                $this->roles[] = $role;
            }
        }
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getRegistrationSecret(): string
    {
        return $this->registrationSecret;
    }

    /**
     * @param string $registrationSecret
     */
    public function setRegistrationSecret(string $registrationSecret)
    {
        $this->registrationSecret = $registrationSecret;
    }
}
