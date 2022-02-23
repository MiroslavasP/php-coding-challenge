<?php

namespace App\Services\Subscription;

use App\Entities\Subscription;
use Routes\Controllers\LoginController;
use Routes\App;

class GetScheduledOrders
{
    /* Generate the array of scheduled orders
     * for the given subscription and number of weeks
     */

    public function handle($subscription, int $forNumberOfWeeks = 6): array
    {
        $calendar = [];
        foreach (($subscription['scedule']) as $date => $status) {

            $deliveryTimestamp = strtotime($date);
            $startDate = strtotime('now');
            $finishDate = strtotime("now + $forNumberOfWeeks week");
            if ($deliveryTimestamp >= $startDate && $deliveryTimestamp <= $finishDate) {

                $calendar += [$date => $status];
            }
        }

        $_SESSION['calendar_before_modification'] = $calendar;
        return $calendar;
    }
    public function setChangesToCalendar()
    {
        $userName = ($_SESSION['username']);
        $sceduleBeforeChanges = $_SESSION['calendar_before_modification'];
        $sceduleBeforeChangesDates = array_keys($sceduleBeforeChanges);
        $newSceduleFromPost = $_SESSION['new_chedule'];
        $newSceduleFromPostDates = array_keys($newSceduleFromPost);
        $allSubscribersInfo = LoginController::getAllSubscribersInfo();
        $newScedule = [];

        foreach ($sceduleBeforeChangesDates as $date) {
            if (in_array($date, $newSceduleFromPostDates)) {

                $allSubscribersInfo[$userName]['scedule'][$date] = Subscription::STATUS_ACTIVE;
            } else {
                $allSubscribersInfo[$userName]['scedule'][$date] = Subscription::STATUS_CANCELLED;
            }
        }
        LoginController::setSubscribes($allSubscribersInfo);
        $subscriber = LoginController::getSubscriberInfo($userName);
        $scedule = $this->handle($subscriber);

        App::view('delivery_calendar', [
            'user_name' => $userName,
            'address' => $subscriber['address'],
            'next_delivery' => array_key_first($scedule),
            'scedule' => $scedule
        ]);
    }
}
