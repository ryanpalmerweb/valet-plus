<?php

class FishpigMagento2ValetDriver extends Magento2ValetDriver
{
    protected $wpDriver;

    /**
     * Determine if a uri belongs to WordPress
     *
     * @param  string  $uri
     * @return boolean
     */
    public function isWordPressUri($uri) {
      return (stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-login') !== false || stripos($uri, 'wp-content') !== false || stripos($uri, 'wp-includes') !== false);
    }

    /**
     * Instantiate the WordPressValetDriver when required
     *
     * @return WordPressValetDriver
     */
    public function getWordPressValetDriver() {
      if(!$this->wpDriver) {
        $this->wpDriver = new WordPressValetDriver;
      }

      return $this->wpDriver;
    }

    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        if (!file_exists($sitePath.'/wp/wp-content/themes/fishpig/index.php')) {
            return false;
        }

        return parent::serves($sitePath, $siteName, $uri);
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        // If the URI contains one of the main WordPress entry points, use the WordPress driver, else use the Magento 2 driver
        if($this->isWordPressUri($uri)) {
          return $this->getWordPressValetDriver()->isStaticFile($sitePath, $siteName, $uri);
        } else {
          return parent::isStaticFile($sitePath, $siteName, $uri);
        }
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        // If the URI contains one of the main WordPress entry points, use the WordPress driver, else use the Magento 2 driver
        if($this->isWordPressUri($uri)) {
          return $this->getWordPressValetDriver()->frontControllerPath($sitePath, $siteName, $uri);
        } else {
          return parent::frontControllerPath($sitePath, $siteName, $uri);
        }
    }
}
