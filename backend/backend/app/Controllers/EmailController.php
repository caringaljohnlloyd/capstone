<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\UserModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController extends ResourceController
{
    public function index()
    {
        //
    }
    public function fetchEmail($email)
    {
        $model = new UserModel();

        $data = $model->where('email', $email)->first();
        
        if (!$data) {
            return $this->failNotFound('No Data Found');
        }

        return $this->respond($data);
    }

    public function sendVerificationEmail($userData)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'support@eduardos-resort.online';
            $mail->Password   = 'Eduardo@pp29';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('support@eduardos-resort.online', 'Eduardos Resort');
            $mail->addAddress($userData['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email';
            $mail->Body = '<table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
            <tbody>
                <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                    <td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top">                    
                </td>
                    <td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                        <div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px; color: #0F172B;">
                            <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                                <tbody>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;padding: 30px;border: 3px solid #0F172B;border-radius: 7px; background-color: #fff;" valign="top">
                                            <meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <tbody>
                                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                            <b>MA\AM,SIR,</b>
                                                            <p>Greetings!</p>
                                                        </td>
                                                    </tr>
                                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                        Click the button below to confirm your reservation and start exploring our resort amenities.                                                        </td>
                                                    </tr>
                                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
We may need to send you important updates and information regarding your reservation, so its crucial that we have your accurate email address.                                                        </td>
                                                    </tr>
                                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block" itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                            <a href="https://eduardos-resort.online/backend/verify?token=' . $userData['ver_token'] . '&expiry=' . $userData['expiry'] . '" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #FEA116; margin: 0; border-color: #FEA116; border-style: solid; border-width: 8px 16px;">Confirm
                                                                email address</a>
                                                        </td>
                                                    </tr>
                                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                            <b>EDUARDOS RESORT</b>
                                                            <p>Support Team</p>
                                                        </td>
                                                    </tr>
                                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block" style="text-align: center;font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0; color: #0F172B;" valign="top">
                                                            Sent on ' . date('F j, Y') . '
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>';
        





            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function resendVerificationEmail()
    {
        $json = $this->request->getJSON();
        $email = $json->email;

        // Check if the user exists
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->failNotFound('No user found with that email address.');
        }

        // Generate new verification token and expiry
        $verificationToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update user with the new token and expiry
        $userModel->update($user['id'], [
            'ver_token' => $verificationToken,
            'expiry' => $tokenExpiry
        ]);

        // Prepare updated user data for email
        $userData = [
            'email' => $user['email'],
            'ver_token' => $verificationToken,
            'expiry' => $tokenExpiry
        ];

        // Resend verification email
        $this->sendVerificationEmail($userData);

        return $this->respond(['message' => 'Verification email has been resent.']);
    }
    public function verify_email()
    {
        $token = $this->request->getGet('token');

        if ($this->isValidToken($token)) {
            $this->updateUserStatus($token);
            //https://eduardos-resort.online/verify
            return redirect()->to('https://eduardos-resort.online/login');
        } else {
            return redirect()->to('https://eduardos-resort.online/token-expired');
        }
    }

    private function isValidToken($token)
    {
        if (!$token) return false;

        $userModel = new UserModel();
        $user = $userModel->where('ver_token', $token)->first();

        if ($user) {
            $expiryTimestamp = strtotime($user['expiry']);
            $currentTimestamp = time();

            return ($expiryTimestamp && $currentTimestamp <= $expiryTimestamp);
        }

        return false;
    }

    private function updateUserStatus($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('ver_token', $token)->first();

        if ($user) {
            $userModel->update($user['id'], ['status' => 'active']);
        }
    }
}
