<?php

namespace AllPref\Entities;

/**
 * @Entity
 * @Table(name="userdetail")
 */
class UserDetail
{
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $name;

    /**
     * @Column(type="string", nullable=true)
     */
    private $phones;

    /**
     * @Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @Column(type="string", nullable=true)
     */
    private $neighborhood;

    /**
     * @Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @Column(type="string", nullable=true)
     */
    private $state;

    /**
     * @Column(type="string", nullable=true)
     */
    private $avatar;



    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    public function getPhones()
    {
        return $this->phones;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }
}
