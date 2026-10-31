<?php
require_once __DIR__ . '/bootstrap.php';

use App\View;

$auth->logout();
View::redirect('login.php');
