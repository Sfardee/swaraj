<?php

namespace Drupal\custom_widget\MyFileSystem;

use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;

class MyFileSystem extends \Drupal\Core\File\FileSystem implements \Drupal\Core\File\FileSystemInterface {
    public function chmod($uri, $mode = NULL) {
        if (!isset($mode)) {
          if (is_dir($uri)) {
            $mode = $this->settings->get('file_chmod_directory', static::CHMOD_DIRECTORY);
          }
          else {
            $mode = $this->settings->get('file_chmod_file', static::CHMOD_FILE);
          }
        }

        @chmod($uri, $mode);
        return TRUE;
    }

    public function mkdir($uri, $mode = NULL, $recursive = FALSE, $context = NULL) {
        if (!isset($mode)) {
          $mode = $this->settings->get('file_chmod_directory', static::CHMOD_DIRECTORY);
        }
    
        // If the URI has a scheme, don't override the umask - schemes can handle
        // this issue in their own implementation.
        if (StreamWrapperManager::getScheme($uri)) {
          return $this->mkdirCall($uri, $mode, $recursive, $context);
        }
    
        // If recursive, create each missing component of the parent directory
        // individually and set the mode explicitly to override the umask.
        if ($recursive) {
          // Ensure the path is using DIRECTORY_SEPARATOR, and trim off any trailing
          // slashes because they can throw off the loop when creating the parent
          // directories.
          $uri = rtrim(str_replace('/', DIRECTORY_SEPARATOR, $uri), DIRECTORY_SEPARATOR);
          // Determine the components of the path.
          $components = explode(DIRECTORY_SEPARATOR, $uri);
          // If the filepath is absolute the first component will be empty as there
          // will be nothing before the first slash.
          if ($components[0] == '') {
            $recursive_path = DIRECTORY_SEPARATOR;
            // Get rid of the empty first component.
            array_shift($components);
          }
          else {
            $recursive_path = '';
          }
          // Don't handle the top-level directory in this loop.
          array_pop($components);
          // Create each component if necessary.
          foreach ($components as $component) {
            $recursive_path .= $component;
    
            if (!file_exists($recursive_path)) {
              if (!$this->mkdirCall($recursive_path, $mode, FALSE, $context)) {
                return FALSE;
              }
              // Not necessary to use self::chmod() as there is no scheme.
              @chmod($recursive_path, $mode);
            }
    
            $recursive_path .= DIRECTORY_SEPARATOR;
          }
        }
    
        // Do not check if the top-level directory already exists, as this condition
        // must cause this function to fail.
        if (!$this->mkdirCall($uri, $mode, FALSE, $context)) {
          return FALSE;
        }
        // Not necessary to use self::chmod() as there is no scheme.
        @chmod($uri, $mode);
        return true;
      }
}
