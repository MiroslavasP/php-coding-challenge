<?php

namespace Routes;

use Routes\Controllers\LoginController;
use Routes\Messages;
use App\Entities\Subscription;
use App\Services\Subscription\GetScheduledOrders;

class App
{

    public static function start()
    {
        return self::route();
    }

    public static function route()
    {
        $userUri = $_SERVER['REQUEST_URI'];
        $userUri = str_replace(INSTALL_DIR, '', $userUri);
        $userUri = preg_replace('/\?.*$/', '', $userUri);
        $userUri = explode('/', $userUri);

        if (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            '' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new LoginController)->showHomePage();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'home' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new LoginController)->showHomePage();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'subscribe' == $userUri[0] &&
            count($userUri) == 1
        ) {
            $newSubscription = new Subscription;
            $newSubscription->doSubscribe();
            self::view('home_page');
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'delivery_calendar' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new LoginController)->doLogin();
        }

        if (!LoginController::isLogged()) {
            self::redirect('home_page');
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'modify' == $userUri[0] &&
            count($userUri) == 1
        ) {
            array_pop($_POST);
            $_SESSION['new_chedule'] = $_POST ?? '';
            // $subscribeInfo = LoginController::getSubscriberInfo($_SESSION['username']);
            (new GetScheduledOrders)->setChangesToCalendar();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'log_out' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new LoginController)->doLogOut();
        }
    }

    public static function redirect($where)
    {
        header('Location: ' . URL . $where);
        die;
    }

    public static function view($temp, $data = [])
    {
        extract($data);
        $appUser = $_SESSION['username'] ?? '';
        $messages = Messages::get();
        require DIR . 'views/' . $temp . '.php';
    }
}
