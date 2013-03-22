<?php
    session_start();
    
    
    function getcaptcha($len)
    {
        srand();
        $arr =  array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',  
            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',  
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',  
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',  
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',  
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
            $ret = '';
            for($i = 0; $i != $len; ++$i)
            {
                $ret .= $arr[rand(0,count($arr) - 1)];
            }
            $_SESSION['captcha'] = $ret;
            return $ret;
    }
            
    
?>
<html>
<head>
<title>登录</title>
<script src="miao.js"></script>
<style type="text/css">
    body{
    font-family:"Microsoft YaHei","微软雅黑",tahoma,arial,simsun,"宋体";
    font-size:12px;
    }
    #get{
    font-size:24px;
    padding:0 0 0 500px;
    }
</style>
<meta charset="utf-8">
</head>
<body>
    <p>A: <textarea id="sectiona" rows="10" cols="80"></textarea></p>
    <p>B: <textarea id="sectionb" rows="10" cols="80"></textarea></p>
    <p>验证码:<?php echo getcaptcha(4); ?> <textarea id="captcha" rows="10" cols="80"></textarea></p>
    <a id="get" href="#">点 我</a>   
    <p id="rst"></p>

</body>
</html>