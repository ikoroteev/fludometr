<?php
include_once('Library/Autoload.php');


class FludoMeter {
    const WEIGHT_REVIEW = 20;

    const WEIGHT_POST_PERSONAL = 15;

    const WEIGHT_POST_PUBLIC = 30;

    const WEIGHT_COMMENT = 5;

    const TIME_FOR_TICK = 2;//2 min

    const PERCENT_PER_TICK = 1;

    const MAX_PERCENT = 100;

    private function __construct() {
    }

    private function __clone() {
    }

    /**
     * @static
     * @param int $type
     * @param int $userId
     * @param \Collection\Activity $activityHistory
     * @return bool
     */
    public static function checkAdd($type, $activityHistory) {

        $weight = 0;

        switch ($type) {
            case Model\Activity::TYPE_COMMENT:       $weight = self::WEIGHT_COMMENT; break;
            case Model\Activity::TYPE_POST_PERSONAL: $weight = self::WEIGHT_POST_PERSONAL; break;
            case Model\Activity::TYPE_POST_PUBLIC:   $weight = self::WEIGHT_POST_PUBLIC; break;
            case Model\Activity::TYPE_REVIEW:        $weight = self::WEIGHT_REVIEW; break;
            default:
                throw new \Exception('Type of activity '.$type .' not defined in class FludoMeter');
        }

        if((self::_getActivityBall($activityHistory) + $weight) < self::MAX_PERCENT) {
            return true;
        } else {
            return false;
        }
    }

    protected static function _getActivityBall(\Collection\Activity $activityHistory) {

        $result = 0;

        $minDateTime = $activityHistory[0]->dateTime;
        /**
         * @var \Model\Activity $activity
         */
        foreach($activityHistory as $activity) {
            switch ($activity->type) {
                case Model\Activity::TYPE_COMMENT:       $result += self::WEIGHT_COMMENT; break;
                case Model\Activity::TYPE_POST_PERSONAL: $result += self::WEIGHT_POST_PERSONAL; break;
                case Model\Activity::TYPE_POST_PUBLIC:   $result += self::WEIGHT_POST_PUBLIC; break;
                case Model\Activity::TYPE_REVIEW:        $result += self::WEIGHT_REVIEW; break;
            }

            if($activity->dateTime < $minDateTime) {
                $minDateTime = $activity->dateTime;
            }
        }

        $result -= ( ((time() - $minDateTime) / 60) / self::TIME_FOR_TICK ) * self::PERCENT_PER_TICK;
        return $result;
    }

}




function fillCollection(Collection\Activity $collection, $array) {
    foreach($array as $_) {
        $activity = new Model\Activity();

        $activity->type     = $_['type'];
        $activity->dateTime = $_['dateTime'];

        $collection[] = clone($activity);
        unset($activity);
    }
}

$testArray = array(
    array(
        'type' => Model\Activity::TYPE_POST_PUBLIC,
        'dateTime' =>time()-1
    ),
    array(
        'type' => Model\Activity::TYPE_POST_PUBLIC,
        'dateTime' =>time()-30
    ),
    array(
        'type' => Model\Activity::TYPE_POST_PUBLIC,
        'dateTime' =>time()-12
    ),
    array(
        'type' => Model\Activity::TYPE_POST_PUBLIC,
        'dateTime' =>time()-40
    ),
    array(
        'type' => Model\Activity::TYPE_POST_PUBLIC,
        'dateTime' =>time()-36
    ),
);

$ActivityCollection = new \Collection\Activity();
fillCollection($ActivityCollection, $testArray);

assert(FludoMeter::checkAdd(Model\Activity::TYPE_COMMENT, $ActivityCollection)==false);


$testArray1 = array(
    array(
        'type' => Model\Activity::TYPE_POST_PUBLIC,
        'dateTime' =>time()-100
    ),
    array(
        'type' => Model\Activity::TYPE_COMMENT,
        'dateTime' =>time()-300
    ),
    array(
        'type' => Model\Activity::TYPE_REVIEW,
        'dateTime' =>time()-1200
    ),
    array(
        'type' => Model\Activity::TYPE_POST_PERSONAL,
        'dateTime' =>time()-45
    )
);

$ActivityCollection1 = new \Collection\Activity();
fillCollection($ActivityCollection1, $testArray1);

assert(FludoMeter::checkAdd(Model\Activity::TYPE_COMMENT, $ActivityCollection1)==true);


