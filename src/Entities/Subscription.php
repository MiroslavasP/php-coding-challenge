<?php

namespace App\Entities;

use Carbon\Carbon;
use Routes\Messages;
use Routes\Controllers\LoginController;

class Subscription
{
    const STATUS_ACTIVE = 1;
    const STATUS_CANCELLED = 2;

    const STATUSES_ALLOWED = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    const PLAN_WEEKLY = 1;
    const PLAN_FORTNIGHTLY = 2;

    const PLANS_ALLOWED = [
        self::PLAN_WEEKLY => 'Weekly',
        self::PLAN_FORTNIGHTLY => 'Fortnightly',
    ];

    private int $status;

    private int $plan = 1;

    private ?Carbon $nextDeliveryDate;

    private string $userName;
    private string $password;
    private string $address;
    private string $deliveryDay;
    private string $startOfDate;

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function setPlan(int $plan): void
    {
        $this->plan = $plan;
    }
    public function getPlan(): int
    {
        return $this->plan;
    }
    public function setNextDeliveryDate(object $nextDeliveryDate): void
    {
        $this->nextDeliveryDate = $nextDeliveryDate;
    }
    public function setStartOfDate(object $startOfDate): void
    {
        $this->startOfDate = $startOfDate;
    }
    public function getNextDeliveryDate(int $week): string
    {
        $this->nextDeliveryDate = (new Carbon('Next ' . $this->deliveryDay . ' + ' . $week . ' week'));

        return $this->nextDeliveryDate;
    }

    public function getSubscribe()
    {
        $this->userName = $_POST['username'] ?? '';
        return LoginController::getSubscriberInfo($this->userName);
    }

    public function doSubscribe()
    {
        if (!$this->getSubscribe()) {
            $allSubscriptionsInfo = LoginController::getAllSubscribersInfo();
            $deliveryScedule = [];
            $this->password = $_POST['password'];
            $this->address = $_POST['address'];
            $this->deliveryDay = $_POST['delivery_day'];

            if ('weekly' === $_POST['subscription_plan']) {
                $this->plan = self::PLAN_WEEKLY;
                for ($i = 0; $i <= 51; $i++) {
                    $date = substr($this->getNextDeliveryDate($i), 0, -9);
                    $deliveryScedule += [$date => self::STATUS_ACTIVE];
                }
            } elseif ('fortnightly' === $_POST['subscription_plan']) {
                $this->plan = self::PLAN_FORTNIGHTLY;
                for ($i = 0; $i <= 51; $i++) {
                    $date = substr($this->getNextDeliveryDate($i), 0, -9);
                    if ($i % 2 === 0) {
                        $deliveryScedule += [$date => self::STATUS_ACTIVE];
                    } else {
                        $deliveryScedule += [$date => self::STATUS_CANCELLED];
                    }
                }
            }

            $subscriberInfo = [
                $this->userName => [
                    'username' => $this->userName,
                    'password' => $this->password,
                    'address' => $this->address,
                    'plan' => $this->plan,
                    'delivery_day' => $this->deliveryDay,
                    'scedule' => $deliveryScedule
                ]
            ];
            $allSubscriptionsInfo += $subscriberInfo;
            LoginController::setSubscribes($allSubscriptionsInfo);
        } else {
            Messages::add('info', 'Such user already exists');
        }
    }
}
