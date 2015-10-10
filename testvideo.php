<?php


require_once __DIR__ . '/function.video.php';


function a()
{
    foreach (func_get_args() as $arg) {
        ob_start();
        var_dump($arg);
        $output = ob_get_clean();
        if ('1' !== ini_get('xdebug.default_enable')) {
            $output = preg_replace("!\]\=\>\n(\s+)!m", "] => ", $output);
        }
        echo '<pre>' . $output . '</pre>';
    }
}


//------------------------------------------------------------------------------/
// TESTS 
//------------------------------------------------------------------------------/
$vimeo = [
    'https://vimeo.com/87973054',
    'http://vimeo.com/87973054',
    'http://vimeo.com/87973054',
    'http://player.vimeo.com/video/87973054?title=0&amp;byline=0&amp;portrait=0',
    'http://player.vimeo.com/video/87973054',
    'http://player.vimeo.com/video/87973054',
    'http://player.vimeo.com/video/87973054?title=0&amp;byline=0&amp;portrait=0',
    'http://vimeo.com/channels/vimeogirls/87973054',
    'http://vimeo.com/channels/vimeogirls/87973054',
    'http://vimeo.com/channels/staffpicks/87973054',
    'http://vimeo.com/87973054',
    'http://vimeo.com/channels/vimeogirls/87973054',
];

$vimeoInvalid = [
    'http://vimeo.com/videoschool',
    'http://vimeo.com/videoschool/archive/behind_the_scenes',
    'http://vimeo.com/forums/screening_room',
    'http://vimeo.com/forums/screening_room/topic:42708',
];


$youtube = [
    "https://www.youtube.com/watch?v=nCwRJUg3tcQ&list=PLv5BUbwWA5RYaM6E-QiE8WxoKwyBnozV2&index=4",
    "http://www.youtube.com/watch?v=nCwRJUg3tcQ&feature=relate",
    'http://youtube.com/v/nCwRJUg3tcQ?feature=youtube_gdata_player',
    'http://youtube.com/vi/nCwRJUg3tcQ?feature=youtube_gdata_player',
    'http://youtube.com/?v=nCwRJUg3tcQ&feature=youtube_gdata_player',
    'http://www.youtube.com/watch?v=nCwRJUg3tcQ&feature=youtube_gdata_player',
    'http://youtube.com/?vi=nCwRJUg3tcQ&feature=youtube_gdata_player',
    'http://youtube.com/watch?v=nCwRJUg3tcQ&feature=youtube_gdata_player',
    'http://youtube.com/watch?vi=nCwRJUg3tcQ&feature=youtube_gdata_player',
    'http://youtu.be/nCwRJUg3tcQ?feature=youtube_gdata_player',
    "https://youtube.com/v/nCwRJUg3tcQ",
    "https://youtube.com/vi/nCwRJUg3tcQ",
    "https://youtube.com/?v=nCwRJUg3tcQ",
    "https://youtube.com/?vi=nCwRJUg3tcQ",
    "https://youtube.com/watch?v=nCwRJUg3tcQ",
    "https://youtube.com/watch?vi=nCwRJUg3tcQ",
    "https://youtu.be/nCwRJUg3tcQ",
    "http://youtu.be/nCwRJUg3tcQ?t=30m26s",
    "https://youtube.com/v/nCwRJUg3tcQ",
    "https://youtube.com/vi/nCwRJUg3tcQ",
    "https://youtube.com/?v=nCwRJUg3tcQ",
    "https://youtube.com/?vi=nCwRJUg3tcQ",
    "https://youtube.com/watch?v=nCwRJUg3tcQ",
    "https://youtube.com/watch?vi=nCwRJUg3tcQ",
    "https://youtu.be/nCwRJUg3tcQ",
    "https://youtube.com/embed/nCwRJUg3tcQ",
    "http://youtube.com/v/nCwRJUg3tcQ",
    "http://www.youtube.com/v/nCwRJUg3tcQ",
    "https://www.youtube.com/v/nCwRJUg3tcQ",
    "https://youtube.com/watch?v=nCwRJUg3tcQ&wtv=wtv",
    "http://www.youtube.com/watch?dev=inprogress&v=nCwRJUg3tcQ&feature=related"
];


$dailymotion = [
    'http://www.dailymotion.com/video/x2jvvep_coup-incroyable-pendant-un-match-de-ping-pong_tv',
    'http://www.dailymotion.com/video/x2jvvep_rates-of-exchange-like-a-renegade_music',
    'http://www.dailymotion.com/video/x2jvvep',
    'http://www.dailymotion.com/hub/x2jvvep_Galatasaray',
    'http://www.dailymotion.com/hub/x2jvvep_Galatasaray#video=x2jvvep',
    'http://www.dailymotion.com/video/x2jvvep_hakan-yukur-klip_sport',
    'http://dai.ly/x2jvvep',
];


echo '<h1>Ids</h1>';
foreach ($vimeo as $url) {
    a(getVimeoId($url));
}
echo '<hr>';
foreach ($vimeoInvalid as $url) {
    a(getVimeoId($url));
}
echo '<hr>';
foreach ($youtube as $url) {
    a(getYoutubeId($url));
}
echo '<hr>';
foreach ($dailymotion as $url) {
    a(getDailyMotionId($url));
}


echo '<hr>';
echo '<h1>Thumbnails</h1>';
$mixed = array_merge($vimeo, $youtube, $dailymotion);


foreach ($mixed as $url) {
    $thumb = getVideoThumbnailByUrl($url);
    if (false !== $thumb) {
        echo '<img src="' . $thumb . '" />';
    }
    else {
        echo 'not found';
    }
    echo '<br>';
    echo getVideoLocation($url);
    echo '<br>';
}





