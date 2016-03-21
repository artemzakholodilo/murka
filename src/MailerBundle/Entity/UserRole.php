<?php

namespace MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserRole
 * @package MurkaBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="user_roles")
 */
class UserRole
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userRoles", cascade={"all"})
     */
    private $userId;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Role", cascade={"all"})
     */
    private $roleId;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }


}