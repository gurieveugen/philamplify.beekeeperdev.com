<?php

// =========================================================
// REUIRE
// =========================================================
require_once 'google_url.php'; 
require_once 'twitteroauth/twitteroauth.php'; 
require_once 'facebook/facebook.php';

class SocialFeed{
	//                          __              __      
	//   _________  ____  _____/ /_____ _____  / /______
	//  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
	// / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
	// \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
	const TWITTER_CACHE_LABEL         = 'twitter';
	const TWITTER_CONSUMER_KEY        = 'FoRJZBenKUFmIQFLDp2gQ';
	const TWITTER_CONSUMER_SECRET     = 'Kudk8D5ZAxb5tWAoXRO21T47gp6EXRplJ82MEUiqc';
	const TWITTER_ACCESS_TOKEN        = '532546390-23aT4nDlWpYLA543yUfmExBqFs0RDb9AZBRbNFTd';
	const TWITTER_ACCESS_TOKEN_SECRET = 'Mt9Hj9aocqQ7qSQGzowzUkFWpvJx8kyBoLAV9GGfV9kvL';
	const FACEBOOK_CACHE_LABEL        = 'fb';
	const FACEBOOK_APP_ID			  = '1423814364535515';
	const FACEBOOK_SECRET			  = 'bdeb449de6a7a8fb5d0cafa953446ed6';
	const GOOGLE_PLUS_CACHE_LABEL     = 'google_plus';
	const GOOGLE_PLUS_KEY			  = 'AIzaSyAAF3e1RCZrRV86bVKLY4LBqfKREV57DWg';
	const CACHE_ON                    = TRUE;
	const CACHE_KEY					  = '34f0';

	//                __  _                 
	//   ____  ____  / /_(_)___  ____  _____
	//  / __ \/ __ \/ __/ / __ \/ __ \/ ___/
	// / /_/ / /_/ / /_/ / /_/ / / / (__  ) 
	// \____/ .___/\__/_/\____/_/ /_/____/  
	//     /_/                              
	private $googl;
	private $twitter;
	private $facebook;
	private $options; 

	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct()
	{		
		$this->twitter  = new TwitterOAuth(self::TWITTER_CONSUMER_KEY, self::TWITTER_CONSUMER_SECRET, self::TWITTER_ACCESS_TOKEN, self::TWITTER_ACCESS_TOKEN_SECRET);
		$this->facebook = new Facebook(array( 'appId'  => self::FACEBOOK_APP_ID, 'secret' => self::FACEBOOK_SECRET)); 
		$this->options  = $GLOBALS['sfoptions']->getAll();
			

		wp_enqueue_script('social_feed', get_bloginfo('template_url').'/js/social_feed.js', array('jquery'));
	}

	public function displayFeed()
	{	
		?>
		<div class="content-socials">
			<div class="title-row cf">
				<h2 class="title-section green">Recent Activity</h2>
				<ul class="socials-filter pc-visible-dib">
					<li class="active"><a href="#" data-social="all">All</a></li>
					<li><a href="#" data-social="philamplify">Philamplify</a></li>
					<li><a href="#" data-social="twitter">Twitter</a></li>
					<li><a href="#" data-social="facebook">Facebook</a></li>
					<li><a href="#" data-social="google_plus">Google+</a></li>
				</ul>
			</div>
			<div class="socials-filter-row pc-hide bf">
				<select name="socials-filter" class="select-socials-filter">
					<option value="0" class="filter">FILTER</option>
					<option value="all">All</option>
					<option value="philamplify">Philamplify</option>
					<option value="twitter">Twitter</option>
					<option value="facebook">Facebook</option>
					<option value="google_plus">Google+</option>
				</select>
			</div>
			<div class="socials-holder">
				<?php 
					if(isset($this->options['twitter']) && strlen($this->options['twitter']))
					{
						$tweets = $this->getTwitterMsg($this->options['twitter'], $this->options['count']);
						echo $this->getTweets($tweets); 	
					}
				?>						
				<?php 
					echo $this->getAssessmentFeed($this->options['count']);	
					
					if(isset($this->options['facebook']) && strlen($this->options['facebook']))
					{
						echo $this->getFacebookFeed($this->options['facebook'], $this->options['count']);	
					}
					
					if(isset($this->options['google_plus']) && strlen($this->options['google_plus']))
					{
						echo $this->getGooglePlusFeed($this->options['google_plus'], $this->options['count']);								
					}
				?>				
			</div>
		</div>
		<?php
	}

	/**
	 * Get tweets from response
	 * @param  array $tweets 
	 * @return string
	 */
	public function getTweets($tweets)
	{
		if(!$tweets) return '';

		$out           = '';
		$first         = true;		
		$article_class = array('box-social', 'blue', 'feed-twitter');

		if($tweets)
		{
			foreach ($tweets as &$tweet) 
			{				
				if($first)
				{
					$first    = false;
					$feed_all = ' feed-all';
				}
				else
				{
					$feed_all = '';	
				}

				$classes = implode(' ', $article_class).$feed_all;
				$url     = 'https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id_str;
				$url     = $url;
				$time    = strtotime($tweet->created_at);
				$time    = $time - 14400;	

				$out.= sprintf('<article class="%s">', $classes);
				$out.= '<header class="cf">';
				$out.= '<div class="ico"><img src="'.TDU.'/images/ico-twitter-2.png" alt=""></div>';
				$out.= sprintf('<a href="%s" class="link-arrow-blue mobile-hide-dib">View on Twitter</a>', $url);
				$out.= '<div class="h-text">';
				$out.= sprintf('<h4>%s</h4>', $tweet->user->name);
				$out.= sprintf('<strong class="date">%s</strong>', $this->formatDate($time));
				$out.= '</div>';
				$out.= '</header>';
				$out.= sprintf('<div class="content"><p>%s</p></div>', $tweet->text);				
				$out.= '</article>';
			}
		}
		return $out;
	}

	/**
	 * Get twitter messages
	 * @param  string $user 
	 * @param  integer $count
	 * @return array
	 */
	public function getTwitterMsg($user, $count)
	{
		$cache = $this->getCache($user.$count, self::TWITTER_CACHE_LABEL);
		if($cache)
		{			
			return $cache;
		}

		//$tweets = $this->twitter->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$user."&count=".$count);
		$tweets = $this->twitter->get("https://api.twitter.com/1.1/search/tweets.json?q=".urlencode($user)."&count=".$count);
		$tweets = $tweets->statuses;

		$this->setCache($user.$count, $tweets, 3600, self::TWITTER_CACHE_LABEL);

		return $tweets;
	}

	/**
	 * Get Facebook HTML feed
	 * @param  string $user  
	 * @param  integer $count 
	 * @return string
	 */
	public function getAssessmentFeed($count)
	{
		$out   = '';
		$first = true;
		$class = array('box-social', 'green', 'feed-philamplify');

		$disqus = new DisqusAPI('mf8qrBtFMVSLRiw2AZu8keys4lYnhywyJEKmY1mZT8UGTAK0qu5Kl3AcrUJFBqhv');
		$assesments = $disqus->posts->list(array(
			'forum' => 'philamplify',
			'limit' => $count));		

		if($assesments)
		{
			foreach ($assesments as $value) 
			{
				$hash = sprintf('#comment-%s', $value->id);
                $assesments_link = '#';
                if(!empty($value->thread)){
                    $threadQuery = new WP_Query(array(
                        'meta_key' => 'dsq_thread_id',
                        'meta_value' => $value->thread
                    ));
                    if($threadQuery->have_posts()):
                        while($threadQuery->have_posts()):$threadQuery->the_post();
                            $assesments_link = get_permalink();
                        endwhile;
                    endif;
                    wp_reset_postdata();
                }
				if($first)
				{
					$first    = false;
					$feed_all = ' feed-all';
				}
				else
				{
					$feed_all = '';	
				}
				$assesments_link.= $hash;
				$classes = implode(' ', $class).$feed_all;
				$msg     = explode('<!--more-->', $value->message);
				$msg     = $msg[0];
				$user    = $value->author->name;
				$time    = strtotime($value->createdAt);
				$time    = $time - 14400;

				$out.= sprintf('<article class="%s">', $classes);
				$out.= '<header class="cf">';
				$out.= '<div class="ico"><img src="'.TDU.'/images/ico-assessment.png" alt=""></div>';
				$out.= sprintf('<a href="%s" class="link-arrow mobile-hide-dibb">Learn More</a>', $assesments_link);
				$out.= '<div class="h-text">';
				$out.= sprintf('<h4>%s</h4>', $user);
				// $out.= sprintf('<h4>%s</h4>',$value ->post_title);
				$out.= sprintf('<strong class="date">%s</strong>', $this->formatDate($time));
				$out.= '</div>';
				$out.= '</header>';
				//$out.= sprintf('<div class="content"><p>%s</p></div>', $msg);
				$out.= sprintf('<div class="content"><p>%s</p></div>', $value->message);
								
				$out.= '</article>';
			}
		}
		return $out;
	}

	/**
	 * Get Facebook HTML feed
	 * @param  string $user  
	 * @param  integer $count 
	 * @return string
	 */
	public function getFacebookFeed($user, $count)
	{
		$out   = '';
		$fb    = $this->getFacebookMsg($user, $count);
		$first = true;
		$class = array('box-social', 'dark-blue', 'feed-facebook');

		if($fb)
		{
			foreach ($fb as $value) 
			{
				if($first)
				{
					$first    = false;
					$feed_all = ' feed-all';
				}
				else
				{
					$feed_all = '';	
				}

				$classes = implode(' ', $class).$feed_all;

				$out.= sprintf('<article class="%s">', $classes);
				$out.= '<header class="cf">';
				$out.= '<div class="ico"><img src="'.TDU.'/images/ico-facebook-2.png" alt=""></div>';
				$out.= sprintf('<a href="%s" class="link-arrow-darkblue mobile-hide-dib">View on Facebook</a>', $value['url']);
				$out.= '<div class="h-text">';
				$out.= sprintf('<h4>%s</h4>', $value['name']);
				$out.= sprintf('<strong class="date">%s</strong>', $this->formatDate($value['created_time']));
				$out.= '</div>';
				$out.= '</header>';
				$out.= sprintf('<div class="content"><p>%s</p></div>', $value['msg']);				
				$out.= '</article>';
			}
		}
		return $out;
	}

	/**
	 * Get facebook messages
	 * @param  string $user 
	 * @return array
	 */
	public function getFacebookMsg($user, $count)
	{
		$key   = $user.$count;
		$cache = $this->getCache($key, self::FACEBOOK_CACHE_LABEL);
		if($cache)
		{	
			return $cache;
		}

		$fb = array();
		$user_profile = $this->facebook->api('/'.$user.'/posts?fields=message,story,id,from,created_time');	
		
		foreach ($user_profile['data'] as &$post) 
		{
			$id  = $post['id'];
			$msg = isset($post['story']) ? $post['story'] : '';
			$msg = isset($post['message']) ? $post['message'] : $msg;
			$url = 'https://www.facebook.com/'.$id.'/';
			$url = $url;
			$c   = intval($count);

			if(strlen($msg) && $count)
			{
				$count--;
				$time = strtotime($post['created_time']);
				$time = $time - 14400;				
				$fb[] = array(
					'id'           => $id, 
					'name'         => $post['from']['name'],
					'msg'          => $msg,
					'url'          => $url,
					'created_time' => $time);
			}
		}		

		$this->setCache($key, $fb, 3600, self::FACEBOOK_CACHE_LABEL);

		return $fb;
	}

	/**
	 * Get Google+ HTML feed
	 * @param  string $user  
	 * @param  integer $count 
	 * @return string
	 */
	public function getGooglePlusFeed($id, $count)
	{		
		$out    = '';
		$g_plus = $this->getGooglePlusMsg($id, $count);		
		$first  = true;
		$class  = array('box-social', 'red', 'feed-google_plus');

		if($g_plus)
		{
			foreach ($g_plus as $value) 
			{
				if($first)
				{
					$first    = false;
					$feed_all = ' feed-all';
				}
				else
				{
					$feed_all = '';	
				}

				$classes = implode(' ', $class).$feed_all;

				$out.= sprintf('<article class="%s">', $classes);
				$out.= '<header class="cf">';
				$out.= '<div class="ico"><img src="'.TDU.'/images/ico-google-2.png" alt=""></div>';
				$out.= sprintf('<a href="%s" class="link-arrow-red mobile-hide-dib">View on Google +</a>', $value['url']);
				$out.= '<div class="h-text">';
				$out.= sprintf('<h4>%s</h4>', $value['name']);
				$out.= sprintf('<strong class="date">%s</strong>', $this->formatDate($value['created_time']));
				$out.= '</div>';
				$out.= '</header>';
				$out.= sprintf('<div class="content"><p>%s</p></div>', $value['msg']);				
				$out.= '</article>';
			}
		}
		return $out;
	}

	/**
	 * Get Google+ messages
	 * @param  string  $id
	 * @param  integer $count
	 * @return array
	 */
	public function getGooglePlusMsg($id, $count)
	{
		// $cache = $this->getCache($id.$count, self::GOOGLE_PLUS_CACHE_LABEL);
		// if($cache)
		// {			
		// 	return $cache;
		// }

		$out         = array();
		$dest        = sprintf('https://www.googleapis.com/plus/v1/people/%s/activities/public?maxResults=%s&key=%s', $id, $count, self::GOOGLE_PLUS_KEY);		
		$json_string = $this->file_get_contents_curl($dest);
		$json        = json_decode($json_string, true);

		if($json['items'])
		{
			foreach ($json['items'] as &$post) 
			{
				$msg = $post['title'];
				if($msg == '')
				{

					if(isset($post['object']['attachments'][0]['embed']['url']))
					{
						$msg = sprintf('<iframe width="540" height="480" src="%s" frameborder="0" allowfullscreen></iframe>', str_replace('autoplay=1', 'autoplay=0', $post['object']['attachments'][0]['embed']['url']));
					}					
				}
				$time = strtotime($post['published']);
				$time = $time - 14400;		

				$out[] = array(
					'url'          => $post['url'],
					'msg'          => $msg,
					'name'         => $post['actor']['displayName'],
					'created_time' => $time);
			}
		}

		$this->setCache($id.$count, $out, 3600, self::GOOGLE_PLUS_CACHE_LABEL);

		return $out;
	}

	/**
	 * Set Cache
	 * @param string  $key    
	 * @param string  $val    
	 * @param integer $time   
	 * @param string  $prefix 
	 */
	public function setCache($key, $val, $time = 3600, $prefix = 'cheched-')
	{		
		set_transient($prefix.$key.self::CACHE_KEY, $val, $time);
	}

	/**
	 * Get Cache
	 * @param  string $key    
	 * @param  string $prefix 
	 * @return mixed
	 */
	public function getCache($key, $prefix = 'cheched-')
	{		
		if(self::CACHE_ON)
		{
			$cached   = get_transient($prefix.$key.self::CACHE_KEY);
			if (false !== $cached) return $cached;	
		}
		return false;
	}

	/**
	 * Format date time
	 * @param  time $time 
	 * @return string
	 */
	public function formatDate($time)
	{
		$d = date('n/j/y', $time);
		$t = date('g:ia', $time);
		return $d.' at '.$t;
	}

	/**
	 * Get contents 
	 * @param  string $url
	 * @return string
	 */
	public function file_get_contents_curl($url) 
	{
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($ch, CURLOPT_URL, $url);

	    $data = curl_exec($ch);
	    curl_close($ch);

	    return $data;
	}
}

// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['social_feed'] = new SocialFeed();