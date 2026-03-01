<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 *
 * Centralized authentication filter that checks if a user is logged in
 * before allowing access to protected routes.
 *
 * - For normal (non-AJAX) requests: redirects to the login page.
 * - For AJAX / JSON requests: returns a 401 JSON response with a
 *   `session_expired` flag so the front-end can react gracefully.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Run before the controller action.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('is_logged_in') === true) {
            // User is authenticated — let the request continue.
            return;
        }

        // Session expired or user never logged in.
        session()->destroy();

        // Detect AJAX / fetch requests.
        $isAjax = ($request instanceof IncomingRequest && $request->isAJAX())
            || $request->getHeaderLine('Accept') === 'application/json';

        if ($isAjax) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'success'         => false,
                    'session_expired' => true,
                    'message'         => 'Your session has expired. Please log in again.',
                    'redirect'        => base_url('login'),
                ]);
        }

        // Normal browser request — redirect to login with flash message.
        return redirect()->to(base_url('login'))
            ->with('error_message', 'Your session has expired. Please log in again.');
    }

    /**
     * Run after the controller action (unused).
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after the response.
    }
}
