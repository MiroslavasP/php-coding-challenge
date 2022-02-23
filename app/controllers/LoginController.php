<?php

namespace Routes\Controllers;

use Routes\App;
use Routes\Messages;
use App\Services\Subscription\GetScheduledOrders;

class LoginController
{
    static public function getSubscriberInfo($userName)
    {
        $subscribersInfo = json_decode(file_get_contents(__DIR__ . '/subscribers_info.json'), true);
        if ($subscribersInfo) {

            foreach ($subscribersInfo as $subscriber => $subscriberInfo) {

                if ($userName === $subscriber) {

                    return $subscriberInfo;
                }
            }
        }
        return false;
    }

    static public function getAllSubscribersInfo()
    {
        $subscribersInfo = json_decode(file_get_contents(__DIR__ . '/subscribers_info.json'), true);

        return $subscribersInfo ?? [];
    }

    static public function setSubscribes($subscribersInfo)
    {
        file_put_contents(__DIR__ . '/subscribers_info.json', json_encode($subscribersInfo));
    }

    static private function logIn($userName, $password)
    {
        $user = self::getSubscriberInfo($userName);

        if ($user['username'] === $userName && $user['password'] === $password) {
            $_SESSION['username'] = $userName;
            $_SESSION['logged'] = 1;
            return true;
        }
        return false;
    }

    static public function isLogged()
    {
        return isset($_SESSION['logged']) && $_SESSION['logged'] == 1;
    }

    public function showHomePage()
    {
        App::view('home_page');
    }

    public function doLogin()
    {
        $userName = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $ok = self::logIn($userName, $password);

        if (!$ok) {
            Messages::add('info', 'Bad username or password');
            App::redirect('home');
        } else {
            Messages::add('success', 'You are logged in succesfully');
            $subscribesInfo = LoginController::getSubscriberInfo($userName);
            $address = $subscribesInfo['address'];
            $scedule = (new GetScheduledOrders)->handle($subscribesInfo);
            $nextDelivery = array_key_first($scedule);

            App::view('delivery_calendar', [
                'user_name' => $userName,
                'address' => $address,
                'next_delivery' => $nextDelivery,
                'scedule' => $scedule
            ]);
        }
    }

    public function doLogOut()
    {
        unset($_SESSION['username'], $_SESSION['logged'], $_SESSION['new_chedule'], $_SESSION['calendar_before_modification']);

        App::redirect('home');
    }
}
