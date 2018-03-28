<?php
namespace this7\weapp;
use \Exception as Exception;
use \QCloud_WeApp_SDK\Constants as Constants;
use \QCloud_WeApp_SDK\Helper\Util as Util;

/**
 * This7 Frame
 * @Author: else
 * @Date:   2018-01-11 14:04:08
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-28 10:42:41
 */

class weapp {
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

    private function getHttpHeader($headerKey) {
        $headerValue = Util::getHttpHeader($headerKey);

        if (!$headerValue) {
            throw new Exception("请求头未包含 {$headerKey}，请配合客户端 SDK 登录后再进行请求");
        }

        return $headerValue;
    }
}