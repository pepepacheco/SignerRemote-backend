<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;


/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @var string $id
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Kiefernwald\DoctrineUuid\Doctrine\ORM\UuidGenerator")
     * @JMS\Groups({"notification"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LoginBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     *
     */
    private $employee;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @JMS\Groups({"notification"})
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @JMS\Groups({"notification"})
     */
    private $latitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="radius", type="integer")
     * @JMS\Groups({"notification"})
     */
    private $radius;

    /**
     * @var string
     *
     * @ORM\Column(name="addresss", type="string")
     * @JMS\Groups({"notification"})
     */
    private $address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendDate", type="datetime", nullable=false)
     * @JMS\Groups({"notification"})
     */
    private $sendDate;

    /**
     * @ORM\ManyToMany(targetEntity="TypeDaysRepeat")
     * @ORM\JoinTable(name="notification_day_repeat",
     *      joinColumns={@ORM\JoinColumn(name="notification_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="day_repeat_id", referencedColumnName="id")}
     *      )
     * @JMS\Groups({"notification"})
     */
    private $dayRepeat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    function __construct()
    {
        $this->dayRepeat = new ArrayCollection();

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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param mixed $employee
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Notification
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Notification
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return int
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param int $radius
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Set sendDate
     *
     * @param \DateTime $sendDate
     * @return Notification
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * Get sendDate
     *
     * @return \DateTime 
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @return mixed
     */
    public function getDayRepeat()
    {
        return $this->dayRepeat;
    }

    /**
     * @param mixed $dayRepeat
     */
    public function setDayRepeat($dayRepeat)
    {
        $this->dayRepeat = $dayRepeat;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}
