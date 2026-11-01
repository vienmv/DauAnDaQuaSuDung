<?php
if (!defined('ABSPATH')) exit;
final class AnhTrang_Contact_Images {
    public static function render($html) {
        static $specs=null;
        if($specs===null) $specs=json_decode(file_get_contents(__DIR__.'/image-references.json'),true);
        if(!is_string($html) || strpos($html,'<img')===false) return $html;
        return preg_replace_callback('#(<span\b[^>]*class="at-contact-image[^>]*>.*?</span></span>)|(<img\b[^>]*>)#su',function($m)use($specs){
            if(!empty($m[1])) return $m[1];
            $img=$m[2];
            foreach($specs as $spec){
                $path=pathinfo($spec['file']);$pattern='#'.preg_quote($path['dirname'].'/'.$path['filename'],'#').'(?:-\d+x\d+)?\.'.preg_quote($path['extension'],'#').'#';
                if(!preg_match($pattern,$img))continue;
                $width=preg_match('/\bwidth="(\d+)"/',$img,$w)?(int)$w[1]:0;
                $align=preg_match('/\balign(left|right|center)\b/',$img,$a)?' align'.$a[1]:'';
                $out='<span class="at-contact-image'.$align.'" data-contact-image="'.(int)$spec['id'].'"'.($width?' style="max-width:'.$width.'px"':'').'>';
                $out.=$img;
                $data=AnhTrang_Contact::data();
                foreach($spec['rects'] as $rect){
                    list($x,$y,$rw,$rh,$bg,$fg,$fields,$font)=$rect;$values=[];
                    foreach($fields as $field)if(!empty($data[$field]))$values[]=esc_html($data[$field]);
                    $style='left:'.$x.'%;top:'.$y.'%;width:'.$rw.'%;height:'.$rh.'%;background:'.$bg.';color:'.$fg.';font-size:'.$font.'cqw';
                    // Blank fields keep an opaque mask so the retired number cannot reappear.
                    $out.='<span class="at-contact-image__value" style="'.esc_attr($style).'">'.implode(' · ',$values).'</span>';
                }
                return $out.'</span>';
            }
            return $img;
        },$html);
    }
}
add_filter('wp_get_attachment_image',array('AnhTrang_Contact_Images','render'),100);
add_filter('the_content',array('AnhTrang_Contact_Images','render'),100);
add_filter('do_shortcode_tag',array('AnhTrang_Contact_Images','render'),100);
add_filter('post_thumbnail_html',array('AnhTrang_Contact_Images','render'),100);
