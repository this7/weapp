<?php
/**
 * @Author: isglory
 * @E-mail: admin@ubphp.com
 * @Date:   2016-08-26 15:05:16
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-29 10:24:22
 * Copyright (c) 2014-2016, UBPHP All Rights Reserved.
 */
namespace this7\weapp;

class User {
    public static function storeUserInfo($userinfo, $skey, $session_key) {
        $uuid            = bin2hex(openssl_random_pseudo_bytes(16));
        $create_time     = date('Y-m-d H:i:s');
        $last_visit_time = $create_time;
        $open_id         = $userinfo->openId;
        $user_info       = json_encode($userinfo);
        $cSessionInfo    = sql::table(C("weapp", "cSessionInfo"));
        $res             = $cSessionInfo->where("open_id", $open_id)->first()
        if ($res === NULL) {
            $cSessionInfo->insert(compact('uuid', 'skey', 'create_time', 'last_visit_time', 'open_id', 'session_key', 'user_info'));
        } else {
            $cSessionInfo->where("open_id", $open_id)
                ->update(compact('uuid', 'skey', 'last_visit_time', 'session_key', 'user_info'));
        }
    }

    public static function findUserBySKey($skey) {
        $row = sql::table(C("weapp", "cSessionInfo"))->where("skey", $skey)->first();

        return (object) $row;
    }
}
