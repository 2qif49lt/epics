<?php
    session_start();
    
    require_once('global/function.php');
    require_once('global/var.php');
    
    header("Content-Type: text/html; charset=utf-8");
    class dologin_ret
    {
        const OK                = 0;    // 成功登录,已经登录
        const PARA_MISS         = 1;    // 参数出错
        const CAPTCHA           = 2;    // 验证码出错
        const DB_CONNFAIL       = 3;    // db连接失败
        const DB_QUERYERR       = 4;    // db查询失败
        const DB_NOEXIST        = 5;    // 无此账户
        const ID_NOACTIVE       = 6;    // 账户没激活
        const ID_FBBYADMIN      = 7;    // 被管理员限制
        const ID_KILLBYSELF     = 8;    // 用户删除自己.
        const ID_UNKNOWN        = 9;    // 账户验证未定义错误
        const ID_WAPWD          = 10;   // 密码错误
        const ILLEGALLOGIN      = 11;   // 不合法页面的登录.没有验证码
        
        public $ret;
        public $msg;
        public $uid;
        public $data;
        
        function __construct($ret,$msg,$uid,$data)
        {
            $this->ret = $ret;
            $this->msg = $msg;
            $this->uid = $uid;
            $this->data = $data;
        }
    }
    function zhcncode(dologin_ret &$ret)    // 处理json 中文编码 utf-8 unicode双编码乱码
    {
        if($ret->msg)
            $ret->msg = urlencode($ret->msg);
    }
    $ret = 0;
    if(!isset($_SESSION['captcha']))
        $ret = new dologin_ret(dologin_ret::ILLEGALLOGIN,'验证码过期.',0,isset($_POST['data']) ? $_POST['data'] : '');
    else
    {
        if(!isset($_POST['email'],$_POST['pwd'],$_POST['captcha']))
        {
            $ret = new dologin_ret(dologin_ret::PARA_MISS,'参数错误.',0,isset($_POST['data']) ? $_POST['data'] : '');
        }
        else
        {
            if(isset($_SESSION['uid']))
            {
                $ret = new dologin_ret(dologin_ret::OK,'您已经登录.',$_SESSION['uid'],isset($_POST['data']) ? $_POST['data'] : '');
            } 
            else
            {
                if(strcasecmp($_POST['captcha'],$_SESSION['captcha']) != 0)
                    $ret = new dologin_ret(dologin_ret::CAPTCHA,'验证码错误.',0,isset($_POST['data']) ? $_POST['data'] : '');
                else
                {
                    $conn = @mysql_connect(cfg_mysql::srv,cfg_mysql::usr,cfg_mysql::pwd);
                    if (!$conn)
                    {
                        $ret = new dologin_ret(dologin_ret::DB_CONNFAIL,'数据库异常.',0,isset($_POST['data']) ? $_POST['data'] : '');
                    } 
                    else 
                    {
                        $email  = mysql_real_escape_string($_POST['email']);
                        
                        mysql_select_db(cfg_mysql::db, $conn);
                        $sqlstr = "select uid,email,password,version,status from user_account_base where email = '$email'";
                        $qrst = mysql_query($sqlstr);
                        if(!$qrst)
                            $ret = new dologin_ret(dologin_ret::DB_QUERYERR,'数据库异常.',0,isset($_POST['data']) ? $_POST['data'] : '');
                        else
                        {
                            if(mysql_num_rows($qrst) == 0)
                                $ret = new dologin_ret(dologin_ret::DB_NOEXIST,'账户不存在.',0,isset($_POST['data']) ? $_POST['data'] : '');
                            else
                            {
                                $rec = mysql_fetch_row($qrst);
                                
                                $status = $rec[4];
                                switch($status)
                                {
                                    case 0:
                                    {
                                        $version = $rec[3];
                                        if(ver_secure::check($_POST['pwd'],$rec[2],$rec[3])) // 正确
                                        {
                                            $_SESSION['uid'] = $rec[0];
                                            $ret = new dologin_ret(dologin_ret::OK,'登录成功.',$rec[0],isset($_POST['data']) ? $_POST['data'] : '');
                                        }
                                        else
                                            $ret = new dologin_ret(dologin_ret::ID_WAPWD,'密码错误.',0,isset($_POST['data']) ? $_POST['data'] : '');
                                    }
                                        break;
                                    case 100:
                                        $ret = new dologin_ret(dologin_ret::ID_NOACTIVE,'账户未激活.',0,isset($_POST['data']) ? $_POST['data'] : '');
                                        break;
                                    case 200:
                                        $ret = new dologin_ret(dologin_ret::ID_FBBYADMIN,'被限制登录.',0,isset($_POST['data']) ? $_POST['data'] : '');
                                        break;
                                    case 300:
                                        $ret = new dologin_ret(dologin_ret::ID_KILLBYSELF,'用户已经删除账户.',0,isset($_POST['data']) ? $_POST['data'] : '');
                                        break;
                                    default:
                                        $ret = new dologin_ret(dologin_ret::ID_UNKNOWN,'账户验证未定义错误.',0,isset($_POST['data']) ? $_POST['data'] : '');
                                }
                                
                                
                            }
                            
                        }
                        mysql_close( $conn );
                    }
                }
                unset($_SESSION['captcha']);

                
                
            }
        }
    }
    
    zhcncode($ret);
    echo  urldecode(json_encode($ret));
?>