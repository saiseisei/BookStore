<?php
namespace Application\Service;

class ValidatorService
{
    public static function url($val) {
        if (empty($val))
            return true;

        $val = strtolower(trim($val));
        if (strlen($val)) {
            $validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=') . '\/0-9a-z]|(%[0-9a-f]{2}))';
            $jp = ((isset($params['jp']) && $params['jp'] == true) ?
                            '|(?:[a-z0-9々-龠][a-z0-9々-龠]{0,14}|(?:[-a-z0-9々-龠]{0,13}[a-z0-9々-龠]))' : ''); //jp domain

            $regex =
                    '^(?:(?:https?|ftps?|file|news|gopher):\/\/)?' .
                    '(?:' .
                    '(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])' . //ip
                    '|' .
                    '(?:[a-z0-9][-a-z0-9]*\.)*' .
                    '(?:[a-z0-9][-a-z0-9]{0,62}' . //domain
                    $jp .
                    ')\.' .
                    '(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)' .
                    ')' .
                    '(?::[1-9][0-9]{0,3})?' . //port number
                    '(?:\/?|\/' . $validChars . '*)?' .
                    '(?:\?' . $validChars . '*)?' .
                    '(?:#' . $validChars . '*)?' .
                    '$';
            return mb_ereg_match($regex, $val);
        }

        return true;
    }
    
    public static function alnum($val) {
        if (empty($val)) {
            return true;
        }

        $val = strtolower(trim($val));
        if (preg_match("/^[a-zA-Z0-9]+$/", $val)) {
            return true;
        } else {
            return false;
        }
    }
}