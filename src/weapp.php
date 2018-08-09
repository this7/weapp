<?php
/**
 * @Author: isglory
 * @E-mail: admin@ubphp.com
 * @Date:   2016-08-26 15:05:16
 * @Last Modified by:   else
 * @Last Modified time: 2018-08-09 19:11:53
 * Copyright (c) 2014-2016, UBPHP All Rights Reserved.
 */
namespace this7\weapp;
use \Exception as Exception;

class weapp {
    public function __construct() {
        if (C("weapp", "useQcloudLogin")) {
            // 系统判断
            if (PHP_OS === 'WINNT') {
                $sdkConfigPath = 'C:\qcloud\sdk.config';
            } else {
                $sdkConfigPath = '/data/release/sdk.config.json';
            }

            $sdkConfig = [];

            if (file_exists($sdkConfigPath)) {
                $sdkConfig = json_decode(file_get_contents($sdkConfigPath), true);
            }

            if (!is_array($sdkConfig)) {
                echo "SDK 配置文件（{$sdkConfigPath}）内容不合法";
                die;
            }

            // 合并 sdk config 和原来的配置
            $config = array_merge($sdkConfig, C("weapp"));
        } else {
            $config = C("weapp");
        }
        /**
         * --------------------------------------------------------------------
         * 设置 SDK 基本配置
         * --------------------------------------------------------------------
         */
        Conf::setup($config);

        /**
         * --------------------------------------------------------------------
         * 设置 SDK 日志输出配置（主要是方便调试）
         * --------------------------------------------------------------------
         */

        // 开启日志输出功能
        Conf::setEnableOutputLog(true);

        // 设置日志输出级别
        // 1 => ERROR, 2 => DEBUG, 3 => INFO, 4 => ALL
        Conf::setLogThresholdArray([2]); // output debug log only

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