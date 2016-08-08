<?php

namespace AllPref\Entities;

/**
 * @Entity
 * @Table(name="usercredentials")
 */
class UserCredentials
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
    private $email;

    /**
     * @Column(type="string")
     */
    private $password;

    /**
     * @OneToOne(targetEntity="UserDetail")
     * @JoinColumn(name="id_userdetail", referencedColumnName="id")
     */
    private $userdetail;


    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUserdetail(\AllPref\Entities\UserDetail $userdetail = null)
    {
        $this->userdetail = $userdetail;

        return $this;
    }

    public function getUserdetail()
    {
        return $this->userdetail;
    }

}
