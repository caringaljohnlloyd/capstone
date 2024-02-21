<?php

namespace App\Controllers;
use CodeIgniter\RestFul\ResourceController;
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
    public function sendVerificationEmail($userData)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'caringaljohnlloyd17@gmail.com';
            $mail->Password   = 'vvme tzch kbky qpfr';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('caringaljohnlloyd17@gmail.com', 'Aquatech');
            $mail->addAddress($userData['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email';
            //         $mail->Body = '
            // <div style="max-width: 500px; margin: 0 auto; font-family: Arial, sans-serif; text-align: center;">
            //     <div style="border: 2px solid #006994; border-radius: 10px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            //         <div style="background-color: #006994; padding: 20px; color: #fff;">
            //             <h2 style="margin: 0; font-size: 28px;">Aquatech Fish Emporium</h2>
            //         </div>
            //         <div style="padding: 20px;">
            //             <h3 style="margin: 0; font-size: 24px;">Hi, ' . $userData['first_name'] . '!</h3>
            //             <p style="margin: 15px 0; font-size: 16px;">Dive into a world of aquatic wonders at Aquatech Fish Emporium! 🐠 Click the button below to verify your email and start exploring our finest selection of fish and aquaculture products:</p>
            //             <a href="http://localhost:8080/verify?token=' . $userData['ver_token'] . '&expiry=' . $userData['expiry'] . '" style="display: inline-block; padding: 15px 30px; background-color: #006994; color: #fff; text-decoration: none; border-radius: 8px; font-size: 18px; transition: background-color 0.3s;">Verify Email</a>
            //         </div>
            //         <div style="background-color: #f8f9fa; padding: 15px; font-size: 14px; color: #6c757d;">
            //             Sent on ' . date('F j, Y') . '
            //         </div>
            //     </div>
            // </div>';
            $mail->Body = '<table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
    <tbody>
        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
            <td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                <div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                        <tbody><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;padding: 30px;border: 3px solid #67a8e4;border-radius: 7px; background-color: #fff;" valign="top">
                                <meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <tbody>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                    <b>MA&apos;AM/SIR ,</b>
                                    <p>Greetings!</p>
                                </td>
                                    </tr>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                        Click the button below to verify your email and start exploring our finest selection of fish and aquaculture products.
                                        </td>
                                    </tr>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            We may need to send you critical information about our service and it is important that we have an accurate email address.
                                        </td>
                                    </tr>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            <a href="http://localhost:8080/verify?token=' . $userData['ver_token'] . '&expiry=' . $userData['expiry'] . '" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #f06292; margin: 0; border-color: #f06292; border-style: solid; border-width: 8px 16px;">Confirm
                                                email address</a>
                                        </td>
                                    </tr>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            <b>AQUATECH</b>
                                            <p>Support Team</p>
                                        </td>
                                    </tr>

                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="text-align: center;font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                        Sent on ' . date('F j, Y') . '
                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                    </tbody></table>
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
        $token = $this->request->getPost('token');

        $userModel = new UserModel();
        $user = $userModel->where('ver_token', $token)->first();

        if (!$user) {
            return $this->respond(['message' => 'User not found'], 404);
        }

        $newToken =  bin2hex(random_bytes(32));
        $newExpiry = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        $userModel->update($user['id'], [
            'ver_token' => $newToken,
            'expiry' => $newExpiry
        ]);

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'caringaljohnlloyd17@gmail.com';
            $mail->Password   = 'vvme tzch kbky qpfr';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('caringaljohnlloyd17@gmail.com', 'Aquatech');
            $mail->addAddress($user['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email';
            $mail->Body = '<table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
    <tbody>
        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
            <td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                <div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                        <tbody><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;padding: 30px;border: 3px solid #67a8e4;border-radius: 7px; background-color: #fff;" valign="top">
                                <meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <tbody><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                        Click the button below to verify your email and start exploring our finest selection of fish and aquaculture products.
                                        </td>
                                    </tr>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            We may need to send you critical information about our service and it is important that we have an accurate email address.
                                        </td>
                                    </tr>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            <a href="http://localhost:8080/verify?token=' . $newToken . '&expiry=' . $newExpiry . '" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #f06292; margin: 0; border-color: #f06292; border-style: solid; border-width: 8px 16px;">Confirm
                                                email address</a>
                                        </td>
                                    </tr>
                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                            <b>AQUATECH</b>
                                            <p>Support Team</p>
                                        </td>
                                    </tr>

                                    <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block" style="text-align: center;font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                        Sent on ' . date('F j, Y') . '
                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                    </tbody></table>
                </div>
            </td>
        </tr>
    </tbody>
</table>';

            $mail->send();
            return view('Resent');
        } catch (Exception $e) {
            return $this->respond(['message' => "Error: {$mail->ErrorInfo}"], 500);
        }
    }

    public function verify_email()
    {
        $token = $this->request->getGet('token');

        if ($this->isValidToken($token)) {
            $this->updateUserStatus($token);
            //http://localhost:8081/verify
            return redirect()->to('http://localhost:8081/');
        } else {
            return redirect()->to('http://localhost:8081/token-expired');
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
