<?php
namespace Application\Service;

class UtilsService
{
    public static function nowYmdNum()
    {
        $d = date('Ymd');
        return (int) $d;
    }
    
    /**
     * RefererIDを生成し返す
     * @param type $id
     * @return type
     */
    public static function getRefererID($id) {
        $zero_padding_id = sprintf("%010d", $id);
        $referer_id = base64_encode("REF_".$zero_padding_id);
        return $referer_id;
    }
    
    /**
     * RefererIDをデコード
     * @param type $referer_id
     * @return type
     */
    public static function getDecodeRefererID($referer_id) {
        $decode_referer_id = base64_decode($referer_id);
        $zero_padding_id = str_replace("REF_","",$decode_referer_id);
        $id = sprintf("%0d", $zero_padding_id);
        return $id;
    }
}