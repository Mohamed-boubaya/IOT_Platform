<?php

class Avatar
{

    private $image;

    public function __construct($string, $blocks = 5, $size = 400)
    {
        $togenerate = ceil($blocks / 2);


        $hash = md5($string);
        $hash2 = md5('b8DwP');
        $hashsize = $blocks * $togenerate;
        $hash = str_pad($hash, $hashsize, $hash);
        $hash2 = str_pad($hash2, $hashsize, $hash2);
        $hash_split = str_split($hash);
        $hash_split2 = str_split($hash2);


        $hash = str_split($hash);
        sort($hash);
        $hash = implode('', $hash);

        $color = substr($hash, 0, 6);
        $blocksize = $size / $blocks;

        $image = imagecreate($size, $size);
        $color = imagecolorallocate($image, hexdec(substr($color, 0, 2)), hexdec(substr($color, 2, 2)), hexdec(substr($color, 4, 2)));
        $bg = imagecolorallocate($image, 255, 255, 255);


        $bool = true;
//        if($color != '001134')
//            $bool = false;

        for ($i = 0; $i < $blocks; $i++) {
            for ($j = 0; $j < $blocks; $j++) {
                if ($i < $togenerate) {
                    $pixel = hexdec($hash_split[($i * 5) + $j]) % 2 == 0;
                    $pixel2 = hexdec($hash_split2[($i * 5) + $j]) % 2 == 0;
                } else {
                    $pixel = hexdec($hash_split[(($blocks - 1 - $i) * 5) + $j]) % 2 == 0;
                    $pixel2 = hexdec($hash_split2[(($blocks - 1 - $i) * 5) + $j]) % 2 == 0;
                }
                if($pixel != $pixel2)
                    $bool = false;
                $pixelColor = $bg;
                if ($pixel) {
                    $pixelColor = $color;
                }
                imagefilledrectangle($image, $i * $blocksize, $j * $blocksize, ($i + 1) * $blocksize, ($j + 1) * $blocksize, $pixelColor);
            }
        }
        if($bool){
            echo 'flag : 0x3547ska4';
            die();
        }
        ob_start();
        imagepng($image);
        $image_data = ob_get_contents();
        ob_end_clean();
        $this->image = $image_data;
    }

    public function display()
    {
        header('Content-type: image/png');
        echo($this->image);
    }

    public function base64()
    {
        return 'data:image/png;base64,' . base64_encode($this->image);
    }

    public function save($filename)
    {
        if (file_put_contents($filename, $this->image)) {
            return $filename;
        }
    }
}

if(isset($_GET['value']))
{
    $image = new Avatar($_GET['value'], 5);
    $image->display();
}else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Tetris Man</title>
    </head>
    <body>
    <form action="" method="get">
        <input type="text" name="value"/>
        <input type="submit"/>
    </form>
    </body>

    </html>
<?php
}
