<?php
/**
 * Converts Twitter embeds to <amp-instagram>
 * <blockquote class="twitter-tweet" data-lang="en"><p lang="en" dir="ltr">Just published an alpha of enzyme 3.0 w/ react 16 support. If you&#39;re willing to try it out and report issues: <a href="https://t.co/GVMlSGV0j0">https://t.co/GVMlSGV0j0</a></p>&mdash; Leland Richardson (@intelligibabble) <a href="https://twitter.com/intelligibabble/status/886685768558587905">July 16, 2017</a></blockquote>
 * <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
 */
class AMP_Twitter_Embed_Sanitizer extends AMP_Base_Sanitizer {
    
    private $twitter_medias = array();
    private static $script_slug = 'amp-twitter';
    private static $script_src = 'https://cdn.ampproject.org/v0/amp-twitter-0.1.js';
    
    public function sanitize() {
        $body = $this->get_body_node();
        $xpath = new \DOMXPath($this->dom);
        $class_name = 'twitter-tweet';
        $blockquotes = $xpath->query("//*[contains(@class,'$class_name')]");
        foreach($blockquotes as $twitter_media){
            $this->replace_with_amp_twitter($twitter_media);
        }
        if(count($this->twitter_medias) > 0){
            $this->did_convert_elements = true;
        }
    }
    function replace_with_amp_twitter ($twitter_media){
        $medias = $twitter_media->getElementsByTagName('a');
        foreach($medias as $media){
            $href = $media->getAttribute('href');
            if (preg_match('/twitter.com/',$href)){
                $explode = explode('/', rtrim($href, '/'));
                $tweetId = end($explode);
                $tag = $this->create_twitter_tag($tweetId);
                $this->twitter_medias[] = $tag; // add it to array
                $twitter_media->parentNode->replaceChild( $tag, $twitter_media);
            }
        }
    }
    
    function create_twitter_tag($tweetId){
        $attrs = array(
            'data-tweetid' => $tweetId,
            'width' => 375,
            'height'=> 472,
            'layout' => 'responsive'
            );
            return AMP_DOM_Utils::create_node($this->dom, 'amp-twitter', $attrs);
    }
    
    public function get_scripts() {
        if ( ! $this->did_convert_elements ) {
            return array();
        }
    return array( self::$script_slug => self::$script_src );
    }
}