<?php

namespace AllPref\Models\User;

use AllPref\Entities\UserCredentials;
use AllPref\Entities\UserDetail;
use AllPref\Helpers\Doctrine;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use AllPref\Helpers\Mailer;
use Hashids\Hashids;

class UserModel
{
    private $doctrine;

    public function __construct()
    {
        $getEntityManager = new Doctrine;
        $this->doctrine = $getEntityManager->getDoctrine();
    }

    public function getUserByEmail(string $userEmail)
    {
        $email = filter_var(filter_var($userEmail, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        if ($email) {
            $user = $this->doctrine->getRepository('AllPref\Entities\UserCredentials')->findOneBy(array('email' => $email));
            if ($user) {
                return $user;
            }
        }
        return false;
    }

    public function recoverPassword(string $userEmail):bool
    {
        $user = $this->getUserByEmail($userEmail);
        $newPass = self::generatePassword();
        $newHash = self::hashPassword($newPass);
        $user->setPassword($newHash);
        try {
            $this->doctrine->flush();
        } catch (\Exception $e) {
            return false;
        }
        $sendMail = new Mailer;
        $sent = $sendMail->sendMailRecoverPassword($user->getEmail(), $newPass);
        if ($sent) {
            return true;
        }
        return false;
    }

    public function createUser($name, $email, $password)
    {
        $userdetail = new UserDetail;
        $userdetail->setName($name);
        $this->doctrine->persist($userdetail);

        $usercredentials = new UserCredentials;
        $usercredentials->setEmail($email);
        $usercredentials->setPassword(self::hashPassword($password));
        $usercredentials->setUserdetail($userdetail);
        $this->doctrine->persist($usercredentials);

        try {
            $this->doctrine->flush();
        } catch (\Exception $e) {
            return false;
        }

        $id = $usercredentials->getId();
        $sendMail = new Mailer;
        $sent = $sendMail->sendMailNewUser($email, $name, $password);
        if (!$sent) {
            $removeUserCredentials = $this->doctrine->find('Application\Entities\UserCredentials', $id);
            $this->doctrine->remove($removeUserCredentials);
            $this->doctrine->flush();
            return false;
        }
        return true;
    }

    public function getProfile()
    {
        $userHashId = self::getCurrentUser();
        $hashids = new Hashids(HASHID_SALT, HASHID_LEVEL);
        $hashidsArr = $hashids->decode($userHashId);
        if (!isset($hashidsArr[0])) {
            return false;
        }
        $id = (int)$hashidsArr[0];
        $user = $this->doctrine->getRepository('AllPref\Entities\UserCredentials')->find($id);
        if (!$user) {
            return false;
        }
        return $user->getUserdetail();
    }

    public function getAll()
    {
        $users = $this->doctrine->getRepository('AllPref\Entities\UserCredentials')->findAll();

        $result = Array();
        foreach ($users as $property => $value) {
            $user = Array();
            $user['avatar'] = $value->getUserdetail()->getAvatar();
            $user['name'] = $value->getUserdetail()->getName();
            $user['city'] = $value->getUserdetail()->getCity();
            $user['state'] = $value->getUserdetail()->getState();
            $result[] = $user;
        }
        return $result;

    }

    public function updateDetails(array $request)
    {
        $userHashId = self::getCurrentUser();
        $hashids = new Hashids(HASHID_SALT, HASHID_LEVEL);
        $hashidsArr = $hashids->decode($userHashId);
        if (!isset($hashidsArr[0])) {
            return false;
        }
        $id = (int)$hashidsArr[0];
        $userDetailId = $this->doctrine->getRepository('AllPref\Entities\UserCredentials')->find($id)->getUserdetail()->getId();
        $userDetail = $this->doctrine->getRepository('AllPref\Entities\UserDetail')->find($userDetailId);
        if (!$userDetail) {
            return false;
        }
        $userDetail->setName($request['inputName']);
        $userDetail->setPhones($request['inputPhones']);
        $userDetail->setAddress($request['inputAddress']);
        $userDetail->setNeighborhood($request['inputNeighborhood']);
        $userDetail->setCity($request['inputCity']);
        $userDetail->setState($request['inputState']);
        $this->doctrine->persist($userDetail);
        try {
            $this->doctrine->flush();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function updateAvatar(string $avatar):string
    {
        $currentUser = self::getCurrentUser();
        $hashids = new Hashids(HASHID_SALT, HASHID_LEVEL);
        $hashidsArr = $hashids->decode($currentUser);
        if (!isset($hashidsArr[0])) {
            return false;
        }
        $id = (int)$hashidsArr[0];
        $userDetailId = $this->doctrine->getRepository('AllPref\Entities\UserCredentials')->find($id)->getUserdetail()->getId();
        $userDetail = $this->doctrine->getRepository('AllPref\Entities\UserDetail')->find($userDetailId);
        if (!$userDetail) {
            return false;
        }
        $userDetail->setAvatar($avatar);
        $this->doctrine->persist($userDetail);
        try {
            $this->doctrine->flush();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function changePassword(array $request):bool
    {
        $userHashId = self::getCurrentUser();
        $hashids = new Hashids(HASHID_SALT, HASHID_LEVEL);
        $hashidsArr = $hashids->decode($userHashId);
        if (!isset($hashidsArr[0])) {
            return false;
        }
        $id = (int)$hashidsArr[0];
        $user = $this->doctrine->getRepository('AllPref\Entities\UserCredentials')->find($id);
        if (!$user) {
            return false;
        }
        if (
            (\password_verify($request['password'], $user->getPassword())) &&
            ($request['newPassword'] === $request['repeatPassword'])
        ) {
            $passwordHash = self::hashPassword($request['newPassword']);
            $user->setPassword($passwordHash);
            try {
                $this->doctrine->flush();
            } catch (\Exception $e) {
                return false;
            }
            $sendMail = new Mailer;
            $sent = $sendMail->sendMailRecoverPassword($user->getEmail(), $request['newPassword']);
            if ($sent) {
                return true;
            }
        }
        return false;
    }

    public static function generatePassword():string
    {
        $generator = new ComputerPasswordGenerator();
        $generator->setUppercase()->setLowercase()->setNumbers()->setSymbols(false)->setLength(8);
        $password = $generator->generatePasswords();
        return $password[0];
    }

    public static function hashPassword($password):string
    {
        return \password_hash($password, PASSWORD_DEFAULT);
    }

    public static function getCurrentUser():string
    {
        return session()->get('loggedUser');
    }
}