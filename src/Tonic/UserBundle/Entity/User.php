<?php

namespace Tonic\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tonic_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your first name.", groups={"Registration", "Profile"})
     * @Assert\MinLength(limit="3", message="The first name is too short.", groups={"Registration", "Profile"})
     * @Assert\MaxLength(limit="255", message="The first name is too long.", groups={"Registration", "Profile"})
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your last name.", groups={"Registration", "Profile"})
     * @Assert\MinLength(limit="3", message="The last name is too short.", groups={"Registration", "Profile"})
     * @Assert\MaxLength(limit="255", message="The last name is too long.", groups={"Registration", "Profile"})
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     * @ORM\Column(length=255, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"refId"})
     * @ORM\Column(length=6, unique=true)
     */
    protected $refId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitedUsers")
     * @ORM\JoinColumn(name="referral_id", referencedColumnName="id")
     */
    protected $referralUser;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="referralUser")
     */
    protected $invitedUsers;

    /**
     * @var string
     *
     * @ORM\Column(name="referral_date", type="date", length=255, nullable=true)
     */
    protected $referralDate;

    /**
     * @var string
     *
     * @ORM\Column(name="referral_link", type="string", length=255, nullable=true)
     */
    protected $referralLink;

    /**
     * @var string
     *
     * @ORM\Column(name="referral_ip", type="string", length=255, nullable=true)
     */
    protected $referralIp;


    public function __construct()
    {
        parent::__construct();

        $this->invitedUsers = new ArrayCollection();
        $this->setRefId(substr(md5(microtime()), 0, 6));
    }

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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set refId
     *
     * @param string $refId
     * @return User
     */
    public function setRefId($refId)
    {
        $this->refId = $refId;

        return $this;
    }

    /**
     * Get refId
     *
     * @return string
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * Set referralUser
     *
     * @param \Tonic\UserBundle\Entity\User $referralUser
     * @return User
     */
    public function setReferralUser(\Tonic\UserBundle\Entity\User $referralUser = null)
    {
        $this->referralUser = $referralUser;

        return $this;
    }

    /**
     * Get referralUser
     *
     * @return \Tonic\UserBundle\Entity\User
     */
    public function getReferralUser()
    {
        return $this->referralUser;
    }

    /**
     * Add invitedUsers
     *
     * @param \Tonic\UserBundle\Entity\User $invitedUsers
     * @return User
     */
    public function addInvitedUser(\Tonic\UserBundle\Entity\User $invitedUsers)
    {
        $this->invitedUsers[] = $invitedUsers;

        return $this;
    }

    /**
     * Remove invitedUsers
     *
     * @param \Tonic\UserBundle\Entity\User $invitedUsers
     */
    public function removeInvitedUser(\Tonic\UserBundle\Entity\User $invitedUsers)
    {
        $this->invitedUsers->removeElement($invitedUsers);
    }

    /**
     * Get invitedUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvitedUsers()
    {
        return $this->invitedUsers;
    }

    /**
     * Set referralDate
     *
     * @param \DateTime $referralDate
     * @return User
     */
    public function setReferralDate($referralDate)
    {
        $this->referralDate = $referralDate;

        return $this;
    }

    /**
     * Get referralDate
     *
     * @return \DateTime
     */
    public function getReferralDate()
    {
        return $this->referralDate;
    }

    /**
     * Set referralLink
     *
     * @param string $referralLink
     * @return User
     */
    public function setReferralLink($referralLink)
    {
        $this->referralLink = $referralLink;

        return $this;
    }

    /**
     * Get referralLink
     *
     * @return string
     */
    public function getReferralLink()
    {
        return $this->referralLink;
    }

    /**
     * Set referralIp
     *
     * @param string $referralIp
     * @return User
     */
    public function setReferralIp($referralIp)
    {
        $this->referralIp = $referralIp;

        return $this;
    }

    /**
     * Get referralIp
     *
     * @return string
     */
    public function getReferralIp()
    {
        return $this->referralIp;
    }
}
