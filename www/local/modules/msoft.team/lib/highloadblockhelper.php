<?php

namespace Msoft\Team;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Data\Cache;


class HighloadBlockHelper
{
    public static function getIdByCode($code)
    {
        if (empty($code)) return null;
        $result = null;
        Loader::includeModule('highloadblock');

        $cachePath = 'hl_helper_getbyid';
        $cacheTtl = 3600;
        $cacheKey = md5($cachePath . $code);

        $cache = Cache::createInstance();
        if ($cache->initCache($cacheTtl, $cacheKey, $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $dbRes = HL\HighloadBlockTable::getList(['filter' => ['=NAME' => $code]])->fetch();
            $result = $dbRes['ID'];
            if (empty($result)) {
                $cache->abortDataCache();
            }
            $cache->endDataCache($dbRes['ID']);
        };

        return $result;
    }

}