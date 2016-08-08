<?php

namespace AllPref\Controllers;

use Silex\Application;
use AllPref\Auth\BaseAuth;
use AllPref\Models\User\UserModel;
use Upload\File;
use Upload\Storage\FileSystem;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;
use abeautifulsite\SimpleImage;

class ConfigController
{
    public function profile()
    {
        $userModel = new UserModel;
        $currentUser = $userModel->getProfile();
        return view()->render('config/profile.html.twig', [
            'token' => BaseAuth::tokenGen(),
            'currentUser' => $currentUser
        ]);
    }

    public function updateDetails(Application $application)
    {
        $request = $application['request_stack']->getCurrentRequest()->request;
        $auth = new BaseAuth;
        $userModel = new UserModel;
        if (true === $auth->tokenVerify($request->get('_token')) &&
            ($userModel->updateDetails($request->all()))
        ) {
            session()->set('success', 'Profile successfully changed');
            return $application->redirect(URL_AUTH . '/config/profile/');
        }
        session()->set('error', 'Has occurred a error while changing the profile details');
        return $application->redirect(URL_AUTH . '/config/profile/');
    }

    public function avatar(Application $application)
    {
        $currentUser = UserModel::getCurrentUser();
        $avatar = self::saveAvatar($currentUser);
        if (!$avatar) {
            if (isset($_FILES['file'])) {
                session()->set('error', 'Error while sending the file, try again');
                return $application->redirect(URL_AUTH . '/config/profile/');
            }
            if (isset($_FILES['webcam'])) {
                return false;
            }
        }
        $userModel = new UserModel;
        $updateAvatar = $userModel->updateAvatar($avatar);
        if (!$updateAvatar) {
            if (isset($_FILES['file'])) {
                session()->set('error', 'Error updating avatar, try again');
                return $application->redirect(URL_AUTH . '/config/profile/');
            }
            if (isset($_FILES['webcam'])) {
                return false;
            }
        }
        if (isset($_FILES['file'])) {
            session()->set('success', 'Avatar updated successfully');
            return $application->redirect(URL_AUTH . '/config/profile/');
        }
        if (isset($_FILES['webcam'])) {
            return $avatar;
        }
    }

    public static function saveAvatar(string $hashid):string
    {
        $storage = new FileSystem(__DIR__ . '/../../public/imgs/avatar');
        $file = null;
        if (isset($_FILES['file'])) {
            $file = new File('file', $storage);
        }
        if (isset($_FILES['webcam'])) {
            $file = new File('webcam', $storage);
        }
        if ($file->getName()) {
            $new_filename = $hashid . uniqid();
            $file->setName($new_filename);
            $file->addValidations(array(new Mimetype(array('image/png', 'image/jpg', 'image/jpeg')), new Size('2M')));
            try {
                $file->upload();
                $newAvatar = $new_filename.'.'.$file->getExtension();
                $savedAvatar = __DIR__ . '/../../public/imgs/avatar/' .$newAvatar;
                if (file_exists($savedAvatar)) {
                    try {
                        $img = new SimpleImage($savedAvatar);
                        $img->fit_to_width(200)->save($savedAvatar);
                    } catch (\Exception $e) {
                        return false;
                    }
                    chmod(__DIR__ . '/../../public/imgs/avatar/' .$newAvatar, 0644);
                    return $newAvatar;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function newPassword(Application $application)
    {
        $request = $application['request_stack']->getCurrentRequest()->request;
        $auth = new BaseAuth;
        $userModel = new UserModel;
        if (true === $auth->tokenVerify($request->get('_token')) &&
            ($userModel->changePassword($request->all()))
        ) {
            session()->set('success', 'Password successfully changed');
            return $application->redirect(URL_AUTH . '/config/profile/');
        }
        session()->set('error', 'Has occurred a error while changing the password');
        return $application->redirect(URL_AUTH . '/config/profile/');
    }
}
