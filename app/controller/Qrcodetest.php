<?php
/**
 * Created by PhpStorm.
 * User: 86138
 * Date: 2021/1/6
 * Time: 16:49
 */

namespace app\controller;
use \Qrcode\PHPQRcode\QRcode;

use think\Controller;
define('QR_MODE_NUL', -1);
define('QR_MODE_NUM', 0);
define('QR_MODE_AN', 1);
define('QR_MODE_8', 2);
define('QR_MODE_KANJI', 3);
define('QR_MODE_STRUCTURE', 4);


define('QR_ECLEVEL_L', 0);
define('QR_ECLEVEL_M', 1);
define('QR_ECLEVEL_Q', 2);
define('QR_ECLEVEL_H', 3);


define('QR_FORMAT_TEXT', 0);
define('QR_FORMAT_PNG', 1);

define('QR_CACHEABLE', false);
define('QR_CACHE_DIR', false);
define('QR_LOG_DIR', false);

define('QR_FIND_BEST_MASK', true);
define('QR_FIND_FROM_RANDOM', 2);
define('QR_DEFAULT_MASK', 2);

define('QR_PNG_MAXIMUM_SIZE', 1024);


define('QRSPEC_VERSION_MAX', 40);
define('QRSPEC_WIDTH_MAX', 177);

define('QRCAP_WIDTH', 0);
define('QRCAP_WORDS', 1);
define('QRCAP_REMINDER', 2);
define('QRCAP_EC', 3);
class Qrcodetest   extends Controller
{

    public function makeCode(){

//        $thumb='Upload/frame/2021-01/16100003819141thumb.png';
        $background_pic=input("background_pic");//背景图
        $size=getimagesize($background_pic);
        $width = $size[0];
        $height=$size[1];
        $img_r2 = imagecreatefromstring(file_get_contents($background_pic));

        if($width>=$height){
            $thumb_w=$height;
            $thumb_h=$height;
            $x = ($width-$height)/2;
            $thumb = imagecreatetruecolor($height,$height);
            imagecopyresampled($thumb, $img_r2, 0, 0, $x, 0, $thumb_w, $thumb_h, $thumb_w, $thumb_w);
            header("Content-type:image/x-png");
            imagepng($thumb,"temp.jpg");
            echo '<img src="../../temp.jpg">';
        }else{
            $thumb_w=$width;
            $thumb_h=$width;
            $y = ($height-$width)/2;
            $thumb = imagecreatetruecolor($width,$width);

            imagecopyresampled($thumb, $img_r2, 0, 0, 0, $y, $thumb_w, $thumb_h, $thumb_w, $thumb_w);
            header("Content-type: image/x-png");
            imagepng($thumb,"temp.jpg");
            echo '<img src="../../temp.jpg">';

        }
        imagedestroy($thumb);
        imagedestroy($img_r2);

        $data=input("data");
        $size=input("size");




        $config = array(
            'data'  => $data,
            'level' => 'L',    //支持二维码容错率，动图时建议提高容错率能提高识别率
            'size'  => $size,
            'mode'  => 'background',
            'alpha' =>  1,//背景填充颜色，1半透明；2全透明。半透明可以提高识别度，但是会使背景原图变灰
            'other' => ['filePath' =>"temp.jpg",
                'char' => '██'
            ],
        );

        Vendor('cuteQRcode-master.auto_load');

        if (php_uname('s')=='Windows NT') {
            exec('chcp 65001');
        }
        if (is_file($config['other']['filePath']) === false) {
            echo '文件不存在！';
            exit;
        }
        $isPic = exif_imagetype($config['other']['filePath']);
        if ($isPic === false) {
            echo '图片格式有误';
            exit;
        }
        $fileInfo = pathinfo($config['other']['filePath']);
        if ($config['other']['filePath'] != '') {
//            $outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR .'temp' . DIRECTORY_SEPARATOR . md5($fileInfo['filename'] . time()) . '.' . $fileInfo['extension'];
            $outFile = './Upload/Qrcodes/'. md5($fileInfo['filename'] . time()) . '.' . $fileInfo['extension'];
        }
        $startTime = microtime(true);
        $qrcode = new QRcode();
        $qrHander = $qrcode->png($config['data'], $outFile, $config['level'], $config['size']/25, 0, $saveandprint = false, $config['mode'], $config['other'],$config['alpha']);
        $endTime = microtime(true);
        $runTime = ($endTime-$startTime)*1000 . ' ms';
        echo $runTime;
        if ($qrHander) echo '成功!';
        $logo = input("logo");
        if ($logo !== FALSE) {
            // 从字符串中的图像流新建一图像
            $QR = imagecreatefromstring(file_get_contents($outFile));
            $logo = imagecreatefromstring(file_get_contents($logo));
            // 获取图像的宽度和高度
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 4;
            // 输出合适宽高的图片
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            imagepng ( $QR, $outFile );//带Logo二维码的文件名
//            imagepng ( $QR, $QR_name );//带Logo二维码的文件名
        }


    }



}