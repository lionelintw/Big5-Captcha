<?php
if (!isset($_SESSION))
{
    session_start();
}
$_SESSION["captcha_count"] = time();
?>

<title><?=stripslashes(iconv("big5", "UTF-8", "可包含中文的captcha試作"))?></title>
<body>

<?php
$flag = 5;
if (isset($_POST["flag"]))
{
    $input = $_POST["input"];
    $flag = $_POST["flag"];
}

if ($flag == 1)
{
    if ($input == $_SESSION["captcha_string"])
    {
?>

        <div style="text-align:center;">
            <h1><?=stripslashes(iconv("big5", "UTF-8", "輸入正確!"))?></h1>

            <form action=" <?php
        echo $_SERVER["PHP_SELF"];
?>" method="POST">
                <input type="button" value="<?=stripslashes(iconv("big5", "UTF-8", "重來"))?>" onclick="this.disabled=true;this.form.submit()"/>
            </form>
        </div>

    <?php
    } else
    {
?>

        <div style="text-align:center;">
            <h1><?=stripslashes(iconv("big5", "UTF-8", "輸入錯誤!"))?><br/> </h1>
        </div>

        <?php
        create_image();
        display();
    }
} else
{
    create_image();
    display();
}

function display()
{
?>

    <div style="text-align:center;">
        <h3><?=stripslashes(iconv("big5", "UTF-8", "請輸入圖中文字"))?></h3>
        <b><?=stripslashes(iconv("big5", "UTF-8", "其中可能包含中文數字"))?></b>

        <div style="display:block;margin-bottom:20px;margin-top:20px;">
            <img src="image<?php
    echo $_SESSION["captcha_count"]
?>.png"/>
        </div>
        <form action=" <?php
    echo $_SERVER["PHP_SELF"];
?>" method="POST"
         >
        <input type="text" name="input"/>
        <input type="hidden" name="flag" value="1"/>
        <input type="button" value="<?=stripslashes(iconv("big5", "UTF-8", "送出"))?>" onclick="this.disabled=true;this.form.submit()"/>
        </form>

        <form action=" <?php
    echo $_SERVER["PHP_SELF"];
?>" method="POST">
            <input type="button" value="<?=stripslashes(iconv("big5", "UTF-8", "換圖"))?>" onclick="this.disabled=true;this.form.submit()"/>
        </form>
    </div>

<?php
}

function create_image()
{
    global $image;
    $image = imagecreatetruecolor(150, 40) or die("Cannot Initialize new GD image stream");

    $background_color = imagecolorallocate($image, 255, 255, 255);
    $text_color = imagecolorallocate($image, 0, 255, 255);
    $line_color = imagecolorallocate($image, 64, 64, 64);
    $pixel_color = imagecolorallocate($image, 0, 0, 255);

    imagefilledrectangle($image, 0, 0, 150, 40, $background_color);

    for ($i = 0; $i < 3; $i++)
    {
        imageline($image, 0, rand() % 40, 150, rand() % 40, $line_color);
    }

    for ($i = 0; $i < 1000; $i++)
    {
        imagesetpixel($image, rand() % 150, rand() % 40, $pixel_color);
    }
    $letters = "abcdefghijklmnopqrstuvwxyz一二三四五六七八九十"; //ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz
    $lens = mb_strlen($letters, "big5");
    for ($i = 0; $i < 36; $i++)
    {
        $rand = rand(0, $lens - 1);
        $letterso[$i] = mb_substr($letters, $rand, 1, "big5");
    }
    for ($i = 0; $i < 36; $i++)
    {
        shuffle($letterso);
        $letters1 .= $letterso[$i];
    }
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $word = "";
    for ($i = 0; $i < 6; $i++)
    {
        //$letter = $letters1[rand(0, $lens - 1)];
        //imagestring($image, 7, 5 + ($i * 30), 20, $letter, $text_color);
        //$word .= $letter;
        
        $rand = rand(0, $lens - 1);
        $letter = mb_substr($letters1, $rand, 1, "big5");
        $word .= $letter;
    }
    ImageTTFText($image, 16, 0, 30, 25, $text_color, "./wqy-microhei.ttc", stripslashes(iconv("big5",
        "UTF-8", $word)));
    $_SESSION["captcha_string"] = stripslashes(iconv("big5", "UTF-8", $word));
    $images = glob("*.png");
    foreach ($images as $image_to_delete)
    {
        @unlink($image_to_delete);
    }
    imagepng($image, "image" . $_SESSION["captcha_count"] . ".png");
}
?>
</body>