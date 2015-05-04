<?php

namespace Demofony2\AppBundle\Enum;

class IconEnum extends Enum
{
    const ARROW_DOWN = 10;
    const ARROW_LEFT = 20;
    const ARROW_RIGHT = 30;
    const ARROW_UP = 40;
    const BALANCE = 50;
    const BOOK = 60;
    const BOOKS = 70;
    const BUILDING_TREE = 80;
    const BUILDING_SEARCH = 90;
    const CALENDAR = 100;
    const CLOUD_DOWNLOAD = 110;
    const SEARCH_SHEET = 120;
    const COMMENT_LIKE_KO = 130;
    const COMMENT_LIKE_OK = 140;
    const COLLABORA = 150;
    const COMMENT = 160;
    const COMMENT_OUTLINE = 170;
    const COMMENT_QUESTION_MARK = 180;
    const INFO = 190;
    const LAWS = 200;
    const LINK = 210;
    const MAP_PLACEHOLDER = 220;
    const MONEY = 230;
    const PAY = 240;
    const PEOPLE_CROWD = 250;
    const PEOPLE_RING = 260;
    const PEOPLE = 270;
    const STATS = 280;
    const PHOTO_CAMERA = 290;
    const USER_ADD = 300;
    const GEARS = 310;
    const POWER = 320;
    const QUESTION_MARK_OUTLINE = 330;
    const QUESTION_MARK = 340;
    const SEARCH = 350;
    const SHARE_EMAIL = 360;
    const SHARE_FACEBOOK = 370;
    const SHARE_GOOGLE_PLUS = 380;
    const SHARE_TWITTER = 390;
    const TICK_KO = 400;
    const TICK_OK_OUTLINE = 410;
    const TICK_OK = 420;
    const VOTE_1 = 430;
    const VOTE_2 = 440;
    const VOTE_3 = 450;
    const VOTE_4 = 460;
    const VOTE_5 = 465;
    const VOTE_A = 470;
    const VOTE_B = 480;
    const VOTE_C = 490;
    const VOTE_D = 500;
    const VOTE_E = 510;
    const WARNING = 520;

    public static function arrayToCss()
    {
        $class = new \ReflectionClass(get_called_class());
        $constants = $class->getConstants();

        $result = [];

        foreach ($constants as $key => $constant) {
            $result[$constant] = strtolower($key);
        }

        return $result;
    }
}
