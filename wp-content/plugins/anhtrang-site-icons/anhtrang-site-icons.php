<?php
/**
 * Plugin Name: Ánh Trang - Khôi phục icon website
 * Description: Icon SVG cục bộ cho các ảnh icon bị thiếu của trang chủ Flatsome.
 * Version: 1.0.0
 * Requires PHP: 7.4
 */
if (!defined('ABSPATH')) exit;
add_filter('wp_get_attachment_image_attributes',function($attrs,$attachment){
    $icons=array(
        '2019/05/earth_icon.png'=>array('globe','Thu mua trên toàn quốc'),
        '2019/05/tuyen-dung-ky-su.png'=>array('truck','Thu mua tận nơi'),
        '2019/05/fly_icon.png'=>array('calendar','Thu mua tất cả các ngày'),
        '2019/05/icon-du-hoc.png'=>array('headset','Hỗ trợ tư vấn 24/7'),
        '2019/05/icon-chat-zalo.png'=>array('zalo','Chat Zalo'),
        '2019/05/icon-chat-messenger.png'=>array('messenger','Chat Messenger'),
        '2019/05/phone-icon.png'=>array('phone','Gọi điện'),
        '2019/09/iconchek.png'=>array('check','Cam kết Ánh Trang')
    );
    $file=get_post_meta($attachment->ID,'_wp_attached_file',true);
    if(!isset($icons[$file])) return $attrs;
    $icon=$icons[$file];
    $attrs['src']=plugins_url('assets/'.$icon[0].'.svg',__FILE__);
    $attrs['alt']=$icon[1];
    unset($attrs['srcset'],$attrs['sizes'],$attrs['data-srcset'],$attrs['data-src']);
    return $attrs;
},99,2);
