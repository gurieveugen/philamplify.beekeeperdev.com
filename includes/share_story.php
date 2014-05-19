<?php
// =========================================================
// REQUIRE
// =========================================================
require_once($_SERVER["DOCUMENT_ROOT"].'/wp-blog-header.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/wp-admin/includes/media.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/wp-admin/includes/file.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/wp-admin/includes/image.php');
require_once('Google/Client.php');
require_once('Google/Service/YouTube.php');

// =========================================================
// HEADERS
// =========================================================
header("HTTP/1.1 200 OK");

class ShareStory{
	//                          __              __      
	//   _________  ____  _____/ /_____ _____  / /______
	//  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
	// / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
	// \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
	const META_KEY      = 'meta';
	const GOOGLE_CLIENT = '639351240322-13da8o4p2hpq935e1t20806lbcj7qd1d.apps.googleusercontent.com';
	const GOOGLE_SECRET = '-dPE4AxfuZPWwg5f3xZwLLfB';
	const REDIRECT_PAGE = '/thank-you-for-submitting-your-story';

	//                __  _                 
	//   ____  ____  / /_(_)___  ____  _____
	//  / __ \/ __ \/ __/ / __ \/ __ \/ ___/
	// / /_/ / /_/ / /_/ / /_/ / / / (__  ) 
	// \____/ .___/\__/_/\____/_/ /_/____/  
	//     /_/                              
	private $post;
	private $files;
	private $errors;
	private $fields_meta       = array('first_name', 'last_name', 'email', 'zip', 'industry', 'media_title', 'media_description', 'media_link', 'video_title', 'video_description', 'state');
	private $fields_required   = array('first_name', 'last_name', 'email', 'story');
	private $fields_files      = array('video', 'photo');
	private $fields_photo_file = array('file' => 'photo', 'title' => 'photo_title', 'description' => 'photo_description');
	private $fields_video_file = array('file' => 'video', 'title' => 'video_title', 'description' => 'video_description');
	
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct($post, $files)
	{	
		$this->_session_start();		
		
		$this->post  = $post;
		$this->files = $files;
		$user_id     = get_current_user_id();		
		if(isset($_GET['unset']))
		{
			session_unset();
			return;
		}	

		if(isset($_GET['code']) OR isset($_SESSION['token']))
		{			
			$res = $this->loadToYouTube($_SESSION['video_file'], $_SESSION['video_title'], $_SESSION['video_description']);			
			if($res['uploaded'])
			{
				$meta = get_post_meta($_SESSION['post_id'], 'meta', true);				
				$meta[$this->fields_video_file['file']] = $res['video_id'];
				update_post_meta($_SESSION['post_id'], self::META_KEY, $meta);				
			}
			else
			{
				$this->errors[] = $res['msg'];
				$this->displayErrorPage();
			}
		}
		else
		{	
			// =========================================================
			// CHECK ERRORS
			// =========================================================
			$this->checkPostErrors($this->fields_required);
			$this->checkFilesErrors($this->fields_files);

			if($this->errors == null)
			{
				// =========================================================
				// INSERT POST
				// =========================================================
				$p = array(
					'post_title'   => $this->post['story_title'],
					'post_content' => $this->post['story'],
					'post_status'  => 'Pending',
					'post_type'    => 'story',
					'post_author'  => $user_id);

				$post_id = wp_insert_post($p);
				if($post_id)
				{
					$meta = $this->fillMeta($this->fields_meta);					
					update_post_meta($post_id, self::META_KEY, $meta);					
					// =========================================================
					// INSERT THUMBNAIL
					// =========================================================
					$photo_file        = $this->files[$this->fields_photo_file['file']];
					$photo_title       = $this->post[$this->fields_photo_file['title']];
					$photo_description = $this->post[$this->fields_photo_file['description']];

					if($photo_file)
					{
						$photo_upload = wp_handle_upload($photo_file, array('test_form' => false));
						                   
			            $photo_attachment = array(
							'post_mime_type' => $photo_file['type'],
							'post_title'     => $photo_title,
							'post_content'   => $photo_description,
							'post_status'    => 'inherit',
							'post_parent'    => $post_id);
			            
			            $photot_attach_id  = wp_insert_attachment($photo_attachment, $photo_upload['file']);	 	            
			            $photo_attach_data = wp_generate_attachment_metadata($photot_attach_id, $photo_upload['file']);	            
			            wp_update_attachment_metadata($photot_attach_id, $photo_attach_data);
			            set_post_thumbnail($post_id,  $photot_attach_id);			            
					}
		            // =========================================================
		            // INSERT VIDEO YOUTUBE
		            // =========================================================
					$video_file                    = $this->files[$this->fields_video_file['file']];
					$video_title                   = $this->post[$this->fields_video_file['title']];
					$video_description             = $this->post[$this->fields_video_file['description']];

					if($video_file)
					{
						$video_upload = wp_handle_upload($video_file , array('test_form' => false));
						$_SESSION['post_id']           = $post_id;
						$_SESSION['video_file']        = $video_upload['file'];
						$_SESSION['video_title']       = $video_title;
						$_SESSION['video_description'] = $video_description;
						
			            $res =  $this->loadToYouTube($video_upload['file'], $video_title, $video_description);
			            if($res['uploaded'])
			            {
			            	$meta[$this->fields_video_file['file']] = $res['video_id'];		            	
			            	update_post_meta($post_id, self::META_KEY, $meta);
			            	
			            }
			            else
			            {
			            	$this->errors[] = $res['msg'];	
			            }
					}
					
				}
				else
				{
					$this->errors[] = 'Can not insert post!';
				}
			}
		}
		
		if($this->errors)
		{
			unset($_SESSION['token']);
			$this->displayErrorPage();
		}
		else
		{			
			wp_redirect(get_bloginfo('url').self::REDIRECT_PAGE);
		}
	}

	/**
	 * Fill meta 
	 * @param  array $fields
	 * @return mixed
	 */
	private function fillMeta($fields)
	{
		if($fields == null OR $this->post == null) return null;
		
		foreach ($fields as &$field) 
		{
			$meta[$field] = isset($this->post[$field]) ? $this->post[$field] : '';
		}
		return $meta;
	}

	/**
	 * Check text fields errors
	 */
	private function checkPostErrors($fields)
	{
		foreach ($fields as &$field) 
		{
			if(!strlen($this->post[$field]))
			{
				$this->errors[] = $fields.' is empty. This field must necessarily be filled!';
			}
		}
	}

	/**
	 * Check files error
	 */
	private function checkFilesErrors($fields)
	{
		foreach ($fields as &$field) 
		{
			if(!isset($this->files[$field]))
			{
				$this->errors[] = 'Problems uploading a file!'.print_r($_SESSION, true);
			}
			else
			{				
				if($this->files[$field]['error'] != 0 && $this->files[$field]['error'] != 4)
				{
					$this->errors[] = sprintf('Problems uploading a file! Error code: %s.', $this->files[$field]['error']);
				}
				else if($this->files[$field]['error'] == 4)
				{
					$this->files[$field] = null;
				}
			}
		}
	}	

	/**
	 * Start session if session not started
	 */
	private function _session_start()
	{
		if(session_id() == '') 
		{
    		return session_start();
		}
		return false;
	}

	/**
	 * Load video from YOUTUBE
	 * @param  string $video_path 
	 * @return string             
	 */
	public function loadToYouTube($video_path, $video_title = 'Some title', $video_description = 'Some description')
	{	
			
		$client = new Google_Client();
		$client->setClientId(self::GOOGLE_CLIENT);
		$client->setClientSecret(self::GOOGLE_SECRET);
		$client->setApplicationName('API Project');
		$client->setScopes('https://www.googleapis.com/auth/youtube');
		$redirect = filter_var('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
		$client->setRedirectUri($redirect);
		
		
		$youtube = new Google_Service_YouTube($client);

		// =========================================================
		// AUTHENTICATE
		// =========================================================
		if(isset($_GET['code'])) 
		{
			if (strval($_SESSION['state']) !== strval($_GET['state'])) 
			{
				die('The session state did not match.');
			}
			$client->authenticate($_GET['code']);
			$_SESSION['token'] = $client->getAccessToken();
			$this->checkToken();

			header('Location: '.$redirect);
		}

		if (isset($_SESSION['token'])) 
		{
			$client->setAccessToken($_SESSION['token']);
		}
		
		// =========================================================
		// UPLOAD VIDEO TO YOUTUBE
		// =========================================================
		if($client->getAccessToken() AND $client->getAccessToken() != '[]') 
		{
			try
			{
				$video          = new Google_Service_YouTube_Video();
				$snippet        = new Google_Service_YouTube_VideoSnippet();
				$status         = new Google_Service_YouTube_VideoStatus();
				$videoPath      = $video_path;
				$chunkSizeBytes = 1 * 1024 * 1024;
				
				$snippet->setTitle($video_title);
				$snippet->setDescription($video_description);
				$snippet->setCategoryId("22");				
				$status->privacyStatus = "public";
				$video->setSnippet($snippet);
				$video->setStatus($status);
				$client->setDefer(true);
				$insertRequest = $youtube->videos->insert("status,snippet", $video);
				$media = new Google_Http_MediaFileUpload( $client, $insertRequest, 'video/*', null, true, $chunkSizeBytes);
				$media->setFileSize(filesize($videoPath));
				$status = false;
				$handle = fopen($videoPath, "rb");
				while (!$status && !feof($handle)) 
				{
					$chunk = fread($handle, $chunkSizeBytes);
					$status = $media->nextChunk($chunk);
				}
				fclose($handle);
				$client->setDefer(false);

				$htmlBody          .= "<h3>Video Uploaded</h3><ul>";
				$htmlBody          .= sprintf('<li>%s (%s)</li>', $status['snippet']['title'], $status['id']);
				$htmlBody          .= '</ul>';
				$result['uploaded'] = true; 
				$result['video_id'] = $status['id'];				

			} 
			catch (Google_ServiceException $e) 
			{
				$result['uploaded'] = false;
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));				
			} 
			catch (Google_Exception $e) 
			{
				$result['uploaded'] = false;
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));				
			}

			$_SESSION['token'] = $client->getAccessToken();	
			$this->checkToken();		
		} 
		else 
		{
			$state = mt_rand();
			$client->setState($state);
			$_SESSION['state'] = $state;
			$authUrl           = $client->createAuthUrl();
			$htmlBody          = '<h3>Authorization Required</h3> <p>You need to <a href="'.$authUrl.'">authorize access</a> before proceeding.<p>';
		}

		$result['msg'] = $htmlBody;
		return $result;
	}

	private function checkToken()
	{
		if($_SESSION['token'] == '[]')
		{
			unset($_SESSION['token']);
		}
	}

	/**
	 * Display error
	 */
	private function displayError(&$item)
	{
		printf('<div class="error">%s</div>', $item);
	}

	/**
	 * Display all errors
	 */
	private function displayErrorPage()
	{
		get_header(); 
		?>
		
		<header class="page-title">
			<div class="holder">
				<div class="center-wrap">
					<h1><?php _e('Errors'); ?></h1>
				</div>
			</div>
		</header>
		<div id="main" class="center-wrap cf">
			<article id="content" class="main-content cf">
				<?php array_walk($this->errors, array($this, 'displayError')); ?>
			</article>
			<?php get_sidebar(); ?>
		</div>

		<?php get_footer();
	}
}
// =========================================================
// LAUNCH
// =========================================================
$GLOBALS['share_story'] = new ShareStory($_POST, $_FILES);

