<?php

namespace AllPref\Helpers;

use PHPMailer;

class Mailer
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer;
        $this->mail->isSMTP();
        $this->mail->Host = EMAILHOST;
        $this->mail->SMTPAuth = EMAILSMTPAUTH;
        $this->mail->Username = EMAIL;
        $this->mail->Password = EMAILPASS;
        $this->mail->SMTPSecure = EMAILSMTPSECURE;
        $this->mail->Port = EMAILPORT;
        $this->mail->isHTML(true);
        $this->mail->setFrom(EMAIL, EMAILFROM);
    }

    public function sendMailRecoverPassword(string $emailTo, string $newPassword):bool
    {
        $this->mail->addAddress($emailTo);
        $this->mail->Subject = 'Your new password in ' . EMAILFROM;
        $this->mail->Body = 'Your new password is: <strong>' . $newPassword . '</strong><br><br>Access <a href="' .
            URL_BASE . '">' . EMAILFROM . '</a> to login.';
        $this->mail->AltBody = 'Your new password in ' . EMAILFROM . '( ' . URL_BASE . ' ) is: ' . $newPassword;
        if (!$this->mail->send()) {
            return false;
        } else {
            return true;
        }
    }

    public function sendMailNewUser(string $emailTo, string $emailName, string $newPassword):bool
    {
        $this->mail->addAddress($emailTo, $emailName);
        $this->mail->Subject = 'Registration in ' . EMAILFROM;
        $this->mail->Body = '
            Hello ' . $emailName . ',<br><br>
            Your registration in '. EMAILFROM .' was successful. To access the system, use the following data:
            <br><br>
            Email: <strong>'. $emailTo .'</strong><br>
            Password: <strong>' . $newPassword . '</strong>
            <br><br>
            Access <a href="' . URL_BASE . '">' . EMAILFROM . '</a> to login.
        ';
        $this->mail->AltBody = 'Hello ' . $emailName . ', Your registration in '. EMAILFROM .
            ' was successful. To access the system, use the following data: \r\n \r\n Email: '.
            $emailTo .' \r\n Password: ' . $newPassword . ' \r\n \r\n  Access  '. EMAILFROM .' ( ' . URL_BASE .
            ' ) to login.';

        if (!$this->mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}
