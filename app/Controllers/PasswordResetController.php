<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class PasswordResetController extends BaseController
{
    /**
     * Display the forgot password page
     */
    public function index()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            return redirect()->to(base_url('Dashboard'));
        }

        return view('Template/ForgotPassword');
    }

    /**
     * Request OTP for password reset
     */
    public function requestOTP()
    {
        $email = $this->request->getPost('email');

        // Validate email
        if (!$this->validateData(['email' => $email], [
            'email' => 'required|valid_email'
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Please provide a valid email address'
            ]);
        }

        // Check if user exists
        $user = $this->usersModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'No account found with this email address'
            ]);
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in session with expiry time (3 minutes)
        $session = session();
        $session->set([
            'password_reset_email' => $email,
            'password_reset_otp' => $otp,
            'password_reset_otp_expiry' => time() + (3 * 60), // 3 minutes from now
            'password_reset_verified' => false
        ]);

        // Send OTP via email
        $emailSent = $this->sendOTPEmail($email, $otp, $user['firstname']);

        if (!$emailSent) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again later.'
            ]);
        }

        log_message('info', 'Password reset OTP sent to: ' . $email);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Verification code sent to your email'
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOTP()
    {
        $email = $this->request->getPost('email');
        $otp = $this->request->getPost('otp');

        // Validate input
        if (!$email || !$otp) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Email and OTP are required'
            ]);
        }

        $session = session();
        $storedEmail = $session->get('password_reset_email');
        $storedOTP = $session->get('password_reset_otp');
        $otpExpiry = $session->get('password_reset_otp_expiry');

        // Check if OTP session exists
        if (!$storedEmail || !$storedOTP || !$otpExpiry) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No active password reset request. Please request a new code.'
            ]);
        }

        // Check if OTP has expired
        if (time() > $otpExpiry) {
            // Clear expired OTP
            $session->remove(['password_reset_otp', 'password_reset_otp_expiry']);
            
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.'
            ]);
        }

        // Verify email matches
        if ($email !== $storedEmail) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Email mismatch. Please try again.'
            ]);
        }

        // Verify OTP
        if ($otp !== $storedOTP) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid verification code. Please try again.'
            ]);
        }

        // Mark OTP as verified
        $session->set('password_reset_verified', true);

        log_message('info', 'Password reset OTP verified for: ' . $email);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Verification successful'
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword()
    {
        $email = $this->request->getPost('email');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validate input
        if (!$this->validateData([
            'email' => $email,
            'new_password' => $newPassword,
            'confirm_password' => $confirmPassword
        ], [
            'email' => 'required|valid_email',
            'new_password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]'
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => $this->validator->listErrors()
            ]);
        }

        $session = session();
        $storedEmail = $session->get('password_reset_email');
        $verified = $session->get('password_reset_verified');

        // Check if OTP was verified
        if (!$verified || $email !== $storedEmail) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Verification required. Please verify your email first.'
            ]);
        }

        // Find user
        $user = $this->usersModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Update password
        $updateData = [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ];

        if (!$this->usersModel->update($user['user_id'], $updateData)) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to reset password. Please try again.'
            ]);
        }

        // Clear password reset session data
        $session->remove([
            'password_reset_email',
            'password_reset_otp',
            'password_reset_otp_expiry',
            'password_reset_verified'
        ]);

        log_message('info', 'Password reset successful for: ' . $email);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * Send OTP email
     */
    private function sendOTPEmail($email, $otp, $firstName)
    {
        $emailService = \Config\Services::email();

        // Configure email
        $emailService->setFrom('noreply@engbakery.com', 'E n\' G Bakery');
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Verification Code');

        // Email message
        $message = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #007B4C; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                    .content { background-color: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
                    .otp-box { background-color: white; border: 2px dashed #007B4C; padding: 20px; text-align: center; margin: 20px 0; border-radius: 5px; }
                    .otp-code { font-size: 32px; font-weight: bold; color: #007B4C; letter-spacing: 8px; font-family: 'Courier New', monospace; }
                    .warning { color: #dc3545; font-size: 14px; margin-top: 20px; }
                    .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>E n' G Bakery</h1>
                        <p>Password Reset Request</p>
                    </div>
                    <div class='content'>
                        <p>Hello " . htmlspecialchars($firstName) . ",</p>
                        <p>You requested to reset your password. Please use the verification code below to proceed:</p>
                        
                        <div class='otp-box'>
                            <p style='margin: 0; font-size: 14px; color: #666;'>Your Verification Code</p>
                            <div class='otp-code'>" . $otp . "</div>
                        </div>
                        
                        <p><strong>This code will expire in 3 minutes.</strong></p>
                        
                        <p>If you didn't request a password reset, please ignore this email or contact support if you have concerns.</p>
                        
                        <div class='warning'>
                            <strong>⚠ Security Notice:</strong> Never share this code with anyone. Our team will never ask for your verification code.
                        </div>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " E n' G Bakery. All rights reserved.</p>
                        <p>This is an automated message, please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        $emailService->setMessage($message);
        $emailService->setMailType('html');

        // Send email
        try {
            if ($emailService->send()) {
                log_message('info', 'OTP email sent successfully to: ' . $email);
                return true;
            } else {
                log_message('error', 'Failed to send OTP email: ' . $emailService->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception sending OTP email: ' . $e->getMessage());
            return false;
        }
    }
}
