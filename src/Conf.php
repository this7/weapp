<?php
/**
 * @Author: isglory
 * @E-mail: admin@ubphp.com
 * @Date:   2016-08-26 15:05:16
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-29 10:09:32
 * Copyright (c) 2014-2016, UBPHP All Rights Reserved.
 */
namespace this7\weapp;

use \Exception as Exception;

class Conf {
    // 是否输出 SDK 日志
    private static $EnableOutputLog = FALSE;

    // SDK 日志输出目录
    private static $LogPath = '';

    // SDK 日志输出级别
    private static $LogThreshold = 0;

    // SDK 日志输出级别（数组）
    private static $LogThresholdArray = [];

    // 程序运行的根路径
    private static $RootPath = '';

    // 微信小程序 AppID
    private static $AppId = '';

    // 微信小程序 AppSecret
    private static $AppSecret = '';

    // 微信小程序 AppSecret
    private static $UseQcloudLogin = true;

    // 当前使用 SDK 服务器的主机，该主机需要外网可访问
    private static $ServerHost = '';

    // 信道服务器服务地址
    private static $TunnelServerUrl = '';

    // 和信道服务器通信的签名密钥，该密钥需要保密
    private static $TunnelSignatureKey = '';

    // 腾讯云 AppID
    private static $QcloudAppId = 123456789;

    // 腾讯云 QcloudSecretId
    private static $QcloudSecretId = '';

    // 腾讯云 QcloudSecretKey
    private static $QcloudSecretKey = '';

    // 微信消息通知 token
    private static $WxMessageToken = '';

    // 微信登录态有效期
    private static $WxLoginExpires = 7200;

    // 网络请求超时时长（单位：毫秒）
    private static $NetworkTimeout = 3000;

    public static function __callStatic($name, $arguemnts) {
        $class = get_class();

        if (strpos($name, 'get') === 0) {
            $key = preg_replace('/^get/', '', $name);

            if (property_exists($class, $key)) {
                $value = self::$$key;

                if (strpos($key, 'Log') === 0) {
                    return $value;
                }

                if (is_string($value) && !$value) {
                    throw new Exception("`{$key}`不能为空，请确保 SDK 配置已正确初始化", 1);
                }

                return $value;
            }
        }

        if (strpos($name, 'set') === 0) {
            $key   = preg_replace('/^set/', '', $name);
            $value = isset($arguemnts[0]) ? $arguemnts[0] : NULL;

            if (property_exists($class, $key)) {
                if (gettype($value) === gettype(self::$$key)) {
                    self::$$key = $value;
                } else {
                    throw new Exception("Call to method {$class}::{$name}() with invalid arguements", 1);
                }
                return;
            }
        }

        throw new Exception("Call to undefined method {$class}::{$name}()", 1);
    }

    public static function setup($config = NULL) {
        if (!is_array($config)) {
            throw new Exception(Constants::E_INIT_LOST_CONFIG);
        }

        $class = get_class();

        foreach ($config as $key => $value) {
            $key = ucfirst($key);
            if (property_exists($class, $key)) {
                if (gettype($value) === gettype(self::$$key)) {
                    if (gettype($value) === 'array') {
                        self::$$key = array_merge(self::$$key, $value);
                    } else {
                        self::$$key = $value;
                    }
                } else {
                    throw new Exception(Constants::E_INIT_CONFIG_TYPE . ': ' . $key);
                }
            }
        }
    }
}
