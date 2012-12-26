<?php
namespace Model;

/**
 * @property int $type
 *
 * @property string $dateTime
 *
 */
class Activity extends Model{

    const TYPE_REVIEW = 1;

    const TYPE_COMMENT = 2;

    const TYPE_POST_PERSONAL = 3;

    const TYPE_POST_PUBLIC = 4;

    protected $_type;

    protected $_dateTime;

    protected function setDateTime($dateTime) {
        $this->_dateTime = $dateTime;
    }

    protected function getDateTime() {
        return $this->_dateTime;
    }

    protected function setType($type) {
        if(in_array($type, array(self::TYPE_COMMENT,self::TYPE_POST_PERSONAL,self::TYPE_POST_PUBLIC,self::TYPE_REVIEW))) {
            $this->_type = $type;
        } else {
            throw new \Exception('Activity type '.$type .' is not define in class Activity');
        }
    }

    protected function getType() {
        return $this->_type;
    }
}
