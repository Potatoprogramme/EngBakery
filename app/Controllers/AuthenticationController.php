<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class AuthenticationController extends BaseController
{
    // Google OAuth Configuration
    private const GOOGLE_CLIENT_ID = '89803932415-79qpupmj3ks8g8gnfttnclh688q4dfpk.apps.googleusercontent.com';
    private const GOOGLE_CLIENT_SECRET = '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';

    // Authorized users - hardcoded user list with email boundaries
    private const AUTHORIZED_USERS = [
        'engbakerymain@gmail.com',
        // Add more authorized emails here
    ];

    public function loginPage(): string
    {
        return view('LoginPage');
    }

    public function logout(): ResponseInterface
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success_message', 'You have been logged out successfully.');
    }

    public function manualLogin(): ResponseInterface
    {
        // Placeholder for manual login logic
        return redirect()->to(base_url('login'))->with('error_message', 'Manual login is not implemented yet.');
    }

    /**
     * Initiate Google OAuth login flow
     */
    public function googleLogin(): ResponseInterface
    {
        $state = bin2hex(random_bytes(16));
        session()->set('oauth_state', $state);

        $redirectUri = base_url('Auth/Google/Callback');

        $params = [
            'client_id' => self::GOOGLE_CLIENT_ID,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'offline',
            'prompt' => 'consent',
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

        return redirect()->to($authUrl);
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback(): ResponseInterface
    {
        // Verify state parameter to prevent CSRF attacks
        $state = $this->request->getVar('state');
        $sessionState = session()->get('oauth_state');

        if (!$state || !$sessionState || $state !== $sessionState) {
            log_message('error', 'Invalid state parameter during Google OAuth callback.');
            return redirect()->to(base_url('login'))->with('error_message', 'Invalid state parameter. Authentication failed.');
        }

        $code = $this->request->getVar('code');
        $error = $this->request->getVar('error');

        if ($error) {
            log_message('error', 'Authentication cancelled by user: ' . $error);
            return redirect()->to(base_url('login'))->with('error_message', 'Authentication cancelled: ' . $error);
        }

        if (!$code) {
            log_message('error', 'No authorization code received during Google OAuth callback.');
            return redirect()->to(base_url('login'))->with('error_message', 'No authorization code received.');
        }

        try {
            // Exchange authorization code for access token
            $tokenData = $this->getAccessToken($code);

            if (!$tokenData || !isset($tokenData['access_token'])) {
                log_message('error', 'Failed to obtain access token from Google.');
                return redirect()->to(base_url('login'))->with('error_message', 'Failed to obtain access token.');
            }

            // Get user info from Google
            $userInfo = $this->getUserInfo($tokenData['access_token']);

            if (!$userInfo) {
                log_message('error', 'Failed to retrieve user information from Google.');
                return redirect()->to(base_url('login'))->with('error_message', 'Failed to retrieve user information.');
            }

            // Check if user is authorized
            if (!$this->isUserAuthorized($userInfo['email'])) {
                log_message('error', 'Unauthorized access attempt by email: ' . $userInfo['email']);
                return redirect()->to(base_url('login'))->with('error_message', 'Your email address is not authorized to access this application.');
            }

            // Set user session
            $userData = [
                'id' => $userInfo['id'],
                'email' => $userInfo['email'],
                'name' => $userInfo['name'] ?? '',
                'picture' => $userInfo['picture'] ?? '',
                'logged_in' => true,
                'login_method' => 'google',
            ];

            session()->set($userData);

            // Clear OAuth state
            session()->remove('oauth_state');

            log_message('info', 'User logged in successfully via Google: ' . $userInfo['email']);
            return redirect()->to(base_url('Dashboard'))->with('success_message', 'Successfully logged in with Google.');
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth Error: ' . $e->getMessage());
            return redirect()->to(base_url('login'))->with('error_message', 'An error occurred during authentication. Please try again.');
        }
    }

    /**
     * Exchange authorization code for access token
     * 
     * @param string $code Authorization code from Google
     * @return array|null Token data
     */
    private function getAccessToken(string $code): ?array
    {
        $client = \Config\Services::curlRequest();

        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'code' => $code,
                'client_id' => self::GOOGLE_CLIENT_ID,
                'client_secret' => self::GOOGLE_CLIENT_SECRET,
                'redirect_uri' => base_url('Auth/Google/Callback'),
                'grant_type' => 'authorization_code',
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }

    /**
     * Get user information from Google using access token
     * 
     * @param string $accessToken Access token from Google
     * @return array|null User info
     */
    private function getUserInfo(string $accessToken): ?array
    {
        $client = \Config\Services::curlRequest();

        $response = $client->get('https://www.googleapis.com/oauth2/v1/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }

    /**
     * Check if user email is in the authorized list
     * 
     * @param string $email User email to check
     * @return bool True if authorized, false otherwise
     */
    private function isUserAuthorized(string $email): bool
    {
        return in_array(strtolower($email), array_map('strtolower', self::AUTHORIZED_USERS));
    }

    public function getCurrentUser()
    {
        // if (session()->get('logged_in')) {

            // $userID = session()->get('id');
            $userID = 1;

            $getUserInfo = $this->usersModel->find($userID);

            log_message('info', 'Fetched current user info for user ID: ' . $userID);
            log_message('info', 'User Info: ' . print_r($getUserInfo, true));

            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'id' => $userID,
                    'email' => $getUserInfo['email'],
                    'name' => $getUserInfo['firstname'] . ' ' . $getUserInfo['middlename'] . ' ' . $getUserInfo['lastname'] . ' ' . $getUserInfo['extension'],
                    'employee_type' => $getUserInfo['employee_type'],
                    'picture' => session()->get('picture'),
                    'login_method' => session()->get('login_method'),

                ],
            ]);
        // } else {
        //     return $this->response->setJSON([
        //         'status' => 'error',
        //         'message' => 'No user is currently logged in.',
        //     ]);
        // }
    }
}
