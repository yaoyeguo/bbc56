<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2012 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

return array(
    /*
    |--------------------------------------------------------------------------
    | 默认storage处理类
    |--------------------------------------------------------------------------
    |
    | 默认storage处理方式
    | 目前支持 ttprosystem | base_storage_filesystem
    | 对应原系统:  KVSTORE_STORAGE
    |
    */
    //'default' => 'ttprosystem',
    'default' => 'filesystem',
    //'default' => 'qiniu',

    /*
    |--------------------------------------------------------------------------
    | mongodb配置
    |--------------------------------------------------------------------------
    |
    | hosts 支持多实例. 目前只支持 192.168.0.230:11211 风格的写法
    | "mongodb:///tmp/mongo-27017.sock" 两种风格
    | options MongoClient::__construct 第二个参数 An array of options for the
    | connection
    */
    'ttprosystem' => array(
        'hosts' => array(
            '192.168.51.96:1978',
            //'192.168.0.231:11211',
        ),
    ),

    'qiniu' => array(
        'auth' => [
            'accessKey'=>'kejAhJms5tFC7foJqqoML3RKYmeYyHQiUjiykbKK',
            'secretKey'=>'6BGwR95rnSsq5XDK5csknfrNBFX_mns2ff2K4Y_c'
        ],
        'bucket' => 'shopexdemoimage',
    ),

    /*
    |--------------------------------------------------------------------------
    | 静态资源映象站地址(js css)
    |--------------------------------------------------------------------------
    |
    | 资源映像站地址
    |
    */
    'host_mirrors' => array(
        //'http://img0.example.com',
        //'http://img2.example.com',
    ),

    /*
    |--------------------------------------------------------------------------
    | 图片映象站地址
    |--------------------------------------------------------------------------
    |
    | 图片资源映像站地址
    | 一个域名标识对应一个域名，在替换的时候域名的时候替换对应的值就可以了
    | 图片域名标识不可变，添加后慎重删除，除非确保改标识下不存在图片了
    | 如果未配置则默认使用 defaultHostMirrors 作为图片标识
    */
    'host_mirrors_img' => [
        //'img0'=>'http://img0.example.com',
        //'img1'=>'http://img2.example.com',
    ],
);
