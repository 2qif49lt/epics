<html>
<head>
<style type="text/css">
body{font-size:10px;}
</style>
</head>
<body>
<?php 
// 验证码识别

// 创建一个新cURL资源
$ch = curl_init();

// 设置URL和相应的选项
curl_setopt($ch, CURLOPT_URL, "http://passport.7fgame.com/ValidateCode.aspx");
curl_setopt($ch, CURLOPT_REFERER, 'http://www.1ygame.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// 抓取URL并把它传递给浏览器
$gif = curl_exec($ch);

// 关闭cURL资源，并且释放系统资源
curl_close($ch);

$fp = fopen("valid.gif","wb");
fwrite($fp, $gif);
fclose($fp);

echo '<br><img src="valid.gif"><br><br>';

getHec("valid.gif");

function getHec($imagePath)
{
	$res = @imagecreatefromgif($imagePath);
	$size = @getimagesize($imagePath);
	
	for($i = 0; $i < $size[1]; ++$i)
	{
        if($i == 15) continue;
        printf("%02d: ",$i);
		for($j = 0; $j < $size[0]; ++$j)
		{
			$rgb = @imagecolorat($res, $j, $i);
			$rgbarray = @imagecolorsforindex($res, $rgb);
			if($rgbarray['red'] < 150 || $rgbarray['green']<150 
                               || $rgbarray['blue'] < 150)
			{
				echo "+";
			}else{
				echo "-";
			}
		}
		echo "<br>";
	}
}
?>
</body>
</html>
