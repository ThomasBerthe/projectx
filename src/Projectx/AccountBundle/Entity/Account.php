<?php

namespace Projectx\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Projectx\AccountBundle\Entity\Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="Projectx\AccountBundle\Entity\AccountRepository")
 * @UniqueEntity(fields="name")
 */
class Account
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
	 * @ORM\Column(type="string", length="255")
     * @var string $name
	 * @Assert\MaxLength(255)
     */
    private $name;

    /**
	 * @ORM\Column(type="string", length="255")
     * @var string $url
	 * @Assert\Url
     */
    private $url;

    /**
	 * @ORM\Column(type="string", length="255")
     * @var string $login
	 * @Assert\MinLength(3)
	 * @Assert\MaxLength(255)
     */
    private $login;

    /**
	 * @ORM\Column(type="string", length="255")
     * @var string $password
	 * @Assert\MaxLength(255)
     */
    private $password;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Projectx\AccountBundle\Entity\AccountGroup", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 * @Assert\Valid()
	 */
	private $group;
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set login
     *
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
	

    /**
     * Set group
     *
     * @param Projectx\AccountBundle\Entity\AccountGroup $group
     */
    public function setGroup(\Projectx\AccountBundle\Entity\AccountGroup $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return Projectx\AccountBundle\Entity\AccountGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }
}