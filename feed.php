<?php
require_once('workflows.php');
$workflow = new Workflows();

$feed_url = "http://api.ihackernews.com/page";
$icon_path = 'icon.png';

$stories = $workflow->request($feed_url);
$stories = json_decode($stories);

if(is_object($stories) && isset($stories->items) && is_array($stories->items)) {
    foreach($stories->items as $story) {
        $workflow->result(time()."-HN-".$story->id, $story->url, $story->title, "Posted ".$story->postedAgo." by ".$story->postedBy.". ".$story->commentCount." comments. ".$story->points." points.", $icon_path);
    }
}
else {
    $workflow->result(time()."-HN-0", "https://news.ycombinator.com/", "Unavailable", "Damn bro! The Hacker News feed is broke. Go to the front page?", $icon_path);
}

echo $workflow->toxml();