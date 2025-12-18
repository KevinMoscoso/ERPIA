<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Auth;

class DashboardController
{
    public function index(): void
    {
        Auth::check(); // 🔐 protege el dashboard

        View::render('dashboard/index', [
            'user' => Auth::user(),
        ]);
    }
}