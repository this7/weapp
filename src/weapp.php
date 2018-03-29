<?php
/**
 * @Author: isglory
 * @E-mail: admin@ubphp.com
 * @Date:   2016-08-26 15:05:16
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-29 10:15:44
 * Copyright (c) 2014-2016, UBPHP All Rights Reserved.
 */
namespace this7\weapp;
use \Exception as Exception;

class weapp {
    public function __construct() {
        Conf::setup(C("weapp"));
    }

    /**
     * 用户登录
     * @return [type] [description]
     */
    public function login() {
        try {
            $code          = self::getHttpHeader(Constants::WX_HEADER_CODE);
            $encryptedData = self::getHttpHeader(Constants::WX_HEADER_ENCRYPTED_DATA);
            $iv            = self::getHttpHeader(Constants::WX_HEADER_IV);

            return AuthAPI::login($code, $encryptedData, $iv);
        } catch (Exception $e) {
            return [
                'loginState' => Constants::E_AUTH,
                'error'      => $e->getMessage(),
            ];
        }
    }

    /**
     * 检查是否登录
     * @return [type] [description]
     */
    public function check() {
        try {
            $skey = self::getHttpHeader(Constants::WX_HEADER_SKEY);

            return AuthAPI::checkLogin($skey);
        } catch (Exception $e) {
            return [
                'loginState' => Constants::E_AUTH,
                'error'      => $e->getMessage(),
            ];
        }
    }

    /**
     * 获取头部信息
     * @param  [type] $headerKey [description]
     * @return [type]            [description]
     */
    private function getHttpHeader($headerKey) {
        $headerValue = util::getHttpHeader($headerKey);

        if (!$headerValue) {
            throw new Exception("请求头未包含 {$headerKey}，请配合客户端 SDK 登录后再进行请求");
        }

        return $headerValue;
    }
}