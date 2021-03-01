
video_upload_master
对 https://github.com/xiyuanpingtadi/cuteQRcode 这个项目的改装，换成tp6环境以及加入只取背景图正中间的部分作为背景，加入logo

环境要求
php_GD库扩展
php_imagick扩展
ImageMagick软件
示例

app/Qrcodetest/makeCode


使用
参数：

data内容    填写扫码后的连接或信息
level容错等级  常规二维码容错等级
size尺寸    大小单位像素，不超过1000px，不小于125px
mode模式    background背景图模式，normal常规模式，char字符模式
alpha透明度   背景填充颜色，1半透明；2全透明。在背景图片非常暗的情况下半透明可以提高识别度，但是会使背景原图变灰
other其他内容

background_pic 背景图片路径
char 字符模式使用的字符（可以使用emoji）
logo  logo路径
TODO
 加入页边距支持
 容错率可以自由调整
 尺寸可调节