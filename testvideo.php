<?php


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


function getDailyMotionId($url)
{

    if (preg_match('!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $url, $m)) {
        if (isset($m[6])) {
            return $m[6];
        }
        if (isset($m[4])) {
            return $m[4];
        }
        return $m[2];
    }
    return false;
}


function getVimeoId($url)
{
    if (preg_match('#(?:https?://)?(?:www.)?(?:player.)?vimeo.com/(?:[a-z]*/)*([0-9]{6,11})[?]?.*#', $url, $m)) {
        return $m[1];
    }
    return false;
}


function getYoutubeId($url)
{
    $parts = parse_url($url);
    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        }
        else if (isset($qs['vi'])) {
            return $qs['vi'];
        }
    }
    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path) - 1];
    }
    return false;
}



function getVideoThumbnailByUrl($url)
{
    if (false !== ($id = getVimeoId($url))) {
        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
        /**
         * thumbnail_small
         * thumbnail_medium
         * thumbnail_large
         */
        return $hash[0]['thumbnail_large'];
    }
    elseif (false !== ($id = getDailyMotionId($url))) {
        return 'http://www.dailymotion.com/thumbnail/video/' . $id;
    }
    elseif (false !== ($id = getYoutubeId($url))) {
        /**
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/0.jpg
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/1.jpg
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/2.jpg
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/3.jpg
         *
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/default.jpg
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/hqdefault.jpg
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/mqdefault.jpg
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/sddefault.jpg
         * http://img.youtube.com/vi/<insert-youtube-video-id-here>/maxresdefault.jpg
         */
        return 'http://img.youtube.com/vi/' . $id . '/default.jpg';
    }
    return false;
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
}





