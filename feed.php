<?php
require_once('workflows.php');
$workflow = new Workflows();

$feed_url = "http://api.ihackernews.com/page";
$icon_path = 'icon.png';

$stories = $workflow->request($feed_url);
$stories = json_decode($stories);

if(is_object($stories) && isset($stories->items) && is_array($stories->items)) {
    foreach($stories->items as $story) {
      if(preg_match('/^\/comments\/(\d+)/', $story->url, $match)) {
        $url = "https://news.ycombinator.com/item?id=$match[1]";
      } else {
        $url = $story->url;
      }

      $workflow->result(time()."-hn-".$story->id, $url, $story->title, "posted ".$story->postedago." by ".$story->postedby.". ".$story->commentcount." comments. ".$story->points." points.", $icon_path);
    }
} else {
    $workflow->result(time()."-hn-0", "https://news.ycombinator.com/", "unavailable", "damn bro! the hacker news feed is broke. go to the front page?", $icon_path);
}

echo $workflow->toxml();

?>
