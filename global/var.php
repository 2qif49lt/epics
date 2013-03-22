<?php
    class cfg_mysql
    {
        const db    = 'epics';
        const srv   = '127.0.0.1:3306';
        const usr   = 'root';
        const pwd   = 'admin';
    }
    
    class ver_secure    // 如果安全验证算法更新则增加vernow的值,并且增加老的版本记录.
    {
        //const ver0 = 0;
        const vernow = 0;   // 当前版本.
        static function check($plaintext,$ciphertext,$vernum)   // 
        {
            switch($vernum)
            {
                case self::vernow:
                    {
                        if(strcmp(md5('epics'.$plaintext),$ciphertext) == 0)
                            return true;
                        else
                            return false;
                    }
                    break;
                default:
                    return false;
            }
        }
    }
?>