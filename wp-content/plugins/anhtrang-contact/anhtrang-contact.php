<?php
/**
 * Plugin Name: Ánh Trang - Thông tin liên hệ
 * Description: Quản lý thông tin liên hệ tập trung, hỗ trợ Flatsome UX Builder.
 * Version: 1.0.3
 * Requires PHP: 7.4
 */
if (!defined('ABSPATH')) { exit; }
final class AnhTrang_Contact {
    const OPTION = 'anhtrang_contact';
    public static function fields() {
        return array('company'=>'Tên công ty','tagline'=>'Mô tả dưới tên công ty','phone'=>'Số điện thoại chung','email'=>'Email','address'=>'Địa chỉ công ty','north'=>'Số liên hệ Miền Bắc','central'=>'Số liên hệ Miền Trung','south'=>'Số liên hệ Miền Nam','representative_name'=>'Họ tên đại diện','representative_title'=>'Chức danh','representative_phone'=>'Điện thoại đại diện','representative_email'=>'Email đại diện','representative_address'=>'Địa chỉ đại diện');
    }
    public static function defaults() {
        return array_merge(array_fill_keys(array_keys(self::fields()), ''),array('company'=>'CÔNG TY TNHH THƯƠNG MẠI VÀ XUẤT NHẬP KHẨU ÁNH TRANG','tagline'=>'Chuyên thu mua dầu ăn đã qua sử dụng và mỡ động vật – Xuất khẩu làm nguyên liệu sinh học, Cam kết không tái chế làm thực phẩm cho con người','phone'=>'+84 243 683 0880','email'=>'info@anhtrangexport.vn','address'=>'Số 17, hẻm 83/32/2 đường Ngọc Hồi, Phường Yên Sở, TP Hà Nội','north'=>'0965.069.880','central'=>'0943.949.083','south'=>'098.973.4677'));
    }
    public static function activate() { add_option(self::OPTION,self::defaults(),'','no'); }
    public static function upgrade() {
        $data=get_option(self::OPTION,array());
        if(is_array($data) && !array_key_exists('tagline',$data)) {
            $defaults=self::defaults();$data['tagline']=$defaults['tagline'];
            update_option(self::OPTION,$data);
        }
    }
    public static function data() { return array_merge(array_fill_keys(array_keys(self::fields()),''),(array)get_option(self::OPTION,array())); }
    public static function tel($value) {
        $value=trim($value); $digits=preg_replace('/\D/','',$value);
        if (strpos($value,'00')===0) return '+'.substr($digits,2);
        return (strpos($value,'+')===0?'+':'').$digits;
    }
    public static function sanitize($input) {
        $old=self::data();$out=array();
        foreach (self::fields() as $key=>$label) {
            $raw=isset($input[$key]) && is_scalar($input[$key]) ? trim((string)$input[$key]) : '';
            $value=sanitize_text_field($raw);
            if (strpos($key,'email')!==false && $raw!=='' && !is_email($raw)) {
                add_settings_error(self::OPTION,$key,$label.': email không hợp lệ.');$value=$old[$key];
            } elseif (in_array($key,array('phone','north','central','south','representative_phone'),true) && $raw!=='' && (!preg_match('/^\+?[0-9\s().-]+$/D',$raw) || strlen(preg_replace('/\D/','',$raw))<7 || strlen(preg_replace('/\D/','',$raw))>15)) {
                add_settings_error(self::OPTION,$key,$label.': nhập từ 7 đến 15 chữ số, có thể dùng +, dấu cách, dấu chấm, ngoặc hoặc gạch nối.');$value=$old[$key];
            }
            $out[$key]=$value;
        }
        return $out;
    }
    public static function admin_init() {
        register_setting('anhtrang_contact_group',self::OPTION,array('type'=>'array','sanitize_callback'=>array(__CLASS__,'sanitize'),'show_in_rest'=>false));
        foreach(array('company'=>'Thông tin công ty','representative'=>'Đại diện ở nước ngoài (nếu có)') as $section=>$label) add_settings_section($section,$label,'__return_false','anhtrang-contact');
        foreach(self::fields() as $key=>$label) add_settings_field($key,$label,array(__CLASS__,'input'),'anhtrang-contact',strpos($key,'representative_')===0?'representative':'company',array('key'=>$key,'label_for'=>'at-'.$key));
    }
    public static function input($args) {
        $key=$args['key'];$data=self::data();
        if($key==='tagline') {
            printf('<textarea class="large-text" rows="3" id="at-tagline" name="%s[tagline]">%s</textarea>',esc_attr(self::OPTION),esc_textarea($data[$key]));
            return;
        }
        printf('<input class="regular-text" id="at-%1$s" name="%2$s[%1$s]" value="%3$s" type="%4$s">',esc_attr($key),esc_attr(self::OPTION),esc_attr($data[$key]),strpos($key,'email')!==false?'email':'text');
    }
    public static function page() {
        if(!current_user_can('manage_options')) return;
        echo '<div class="wrap"><h1>Thông tin liên hệ</h1>';settings_errors();
        echo '<form method="post" action="options.php">';settings_fields('anhtrang_contact_group');do_settings_sections('anhtrang-contact');submit_button();
        echo '</form><p>Khối đầy đủ: <code>[anhtrang_contact]</code>. Từng trường: <code>[anhtrang_contact_field field="phone" format="link"]</code>. Định dạng: text, link, tel, mailto, maps, url. Để trống để ẩn trường trong khối đầy đủ.</p></div>';
    }
    public static function value($key,$format='text') {
        $data=self::data(); if(!isset($data[$key]) || $data[$key]==='') return '';
        $v=$data[$key];$url='';
        if(in_array($key,array('phone','north','central','south','representative_phone'),true)) $url='tel:'.self::tel($v);
        elseif(strpos($key,'email')!==false) $url='mailto:'.$v;
        elseif(strpos($key,'address')!==false) $url='https://www.google.com/maps/search/?api=1&query='.rawurlencode($v);
        if(in_array($format,array('tel','mailto','maps','url'),true)) return esc_url($url,array('http','https','tel','mailto'));
        if($format==='link' && $url) return '<a href="'.esc_url($url,array('http','https','tel','mailto')).'">'.esc_html($v).'</a>';
        return esc_html($v);
    }
    public static function field($atts) { $a=shortcode_atts(array('field'=>'phone','format'=>'text'),$atts,'anhtrang_contact_field');return self::value($a['field'],$a['format']); }
    public static function block() {
        $d=self::data();$html='<div class="anhtrang-contact">';
        foreach(array('company','tagline','phone','email','address') as $key) if($d[$key]!=='') $html.='<div class="anhtrang-contact__'.$key.'">'.($key==='company'?'<strong>'.self::value($key).'</strong>':($key==='tagline'?'<em>'.self::value($key).'</em>':self::value($key,'link'))).'</div>';
        if($d['north']!=='' || $d['central']!=='' || $d['south']!=='') {
            $html.='<div class="anhtrang-contact__regions"><strong>Liên Hệ Theo Khu Vực:</strong>';
            foreach(array('north'=>'Miền Bắc','central'=>'Miền Trung','south'=>'Miền Nam') as $key=>$label) if($d[$key]!=='') $html.='<div>'.esc_html($label).': '.self::value($key,'link').'</div>';
            $html.='</div>';
        }
        return $html.'</div>';
    }
    public static function representative() {
        $html='';foreach(array('name','title','phone','email','address') as $key) {$v=self::value('representative_'.$key,'link');if($v!=='') $html.='<div>'.$v.'</div>';}
        return $html===''?'':'<div class="anhtrang-contact anhtrang-contact__representative">'.$html.'</div>';
    }
    // Tokens are attribute-safe: never nest a shortcode inside a UX Builder attribute.
    public static function tokens($text) {
        if(!is_string($text) || strpos($text,'{{anhtrang:')===false) return $text;
        return preg_replace_callback('/\{\{anhtrang:([a-z_]+):(text|tel|mailto|maps|url|zalo)\}\}/',function($m){
            if($m[2]==='zalo') { $d=self::data();return empty($d[$m[1]])?'':esc_url('https://zalo.me/'.preg_replace('/\D/','',$d[$m[1]])); }
            return self::value($m[1],$m[2]);
        },$text);
    }
    public static function finish_shortcode($html) {
        $html=self::tokens($html);
        return preg_replace_callback('/\[anhtrang_contact_field\s+([^\]]*)\]/',function($m){return self::field(shortcode_parse_atts($m[1]));},$html);
    }
    public static function tree($v) { if(is_array($v)) { foreach($v as &$item) $item=self::tree($item);unset($item);return $v; }return self::tokens($v); }
    public static function shortcode_attrs($return,$tag,$attr,$m) {
        if($return!==false || !is_array($attr)) return $return;
        $new=self::tree($attr);if($new===$attr) return $return;
        global $shortcode_tags;
        $output=call_user_func($shortcode_tags[$tag],$new,isset($m[5])?$m[5]:null,$tag);
        return $m[1].apply_filters('do_shortcode_tag',$output,$tag,$new,$m).$m[6];
    }
    public static function init() {
        add_action('plugins_loaded',array(__CLASS__,'upgrade'));
        add_action('admin_menu',function(){add_options_page('Thông tin liên hệ','Thông tin liên hệ','manage_options','anhtrang-contact',array(__CLASS__,'page'));});
        add_action('admin_init',array(__CLASS__,'admin_init'));
        add_shortcode('anhtrang_contact',array(__CLASS__,'block'));
        add_shortcode('anhtrang_contact_field',array(__CLASS__,'field'));
        add_shortcode('anhtrang_representative',array(__CLASS__,'representative'));
        add_filter('the_content',array(__CLASS__,'tokens'),8);
        add_filter('the_excerpt',array(__CLASS__,'tokens'),8);
        add_filter('rank_math/frontend/description',array(__CLASS__,'tokens'));
        add_filter('rank_math/opengraph/facebook/description',array(__CLASS__,'tokens'));
        add_filter('rank_math/opengraph/twitter/description',array(__CLASS__,'tokens'));
        add_filter('rank_math/json_ld',array(__CLASS__,'tree'),99);
        add_filter('get_post_metadata',function($value,$object_id,$key,$single){
            if($value!==null || $key!=='rank_math_description') return $value;
            global $wpdb;
            $raw=$wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key=%s LIMIT 1",$object_id,$key));
            return $raw===null?null:array(self::tokens($raw));
        },10,4);
        add_filter('widget_text',array(__CLASS__,'tokens'),8);
        add_filter('widget_custom_html_content',array(__CLASS__,'tokens'),8);
        add_filter('widget_text_content','do_shortcode',11);
        add_filter('widget_custom_html_content','do_shortcode',11);
        add_filter('pre_do_shortcode_tag',array(__CLASS__,'shortcode_attrs'),10,4);
        add_filter('do_shortcode_tag',array(__CLASS__,'finish_shortcode'),20);
        foreach(array('flatsome','flatsome-child') as $theme) add_filter('option_theme_mods_'.$theme,array(__CLASS__,'tree'));
        foreach(array('contact_phone'=>'phone','contact_email'=>'email','contact_email_label'=>'email','contact_location'=>'address','contact_location_label'=>'address') as $mod=>$field) add_filter('theme_mod_'.$mod,function()use($field){return html_entity_decode(self::value($field),ENT_QUOTES,'UTF-8');});
        add_filter('wp_nav_menu_objects',function($items){foreach($items as $item){$item->url=self::tokens($item->url);$item->title=self::tokens($item->title);}return $items;});
        add_action('wp_enqueue_scripts',function(){wp_enqueue_style('anhtrang-contact',plugins_url('contact.css',__FILE__),array(),'1.0.3');});
    }
}
register_activation_hook(__FILE__,array('AnhTrang_Contact','activate'));
AnhTrang_Contact::init();

require_once __DIR__ . '/contact-images.php';
