<?php
ob_start();

error_reporting(E_ALL & ~E_NOTICE);

require_once(__DIR__.'\vendor\autoload.php');
require_once(__DIR__.'\config.php');

use Intervention\Image\ImageManagerStatic as Image;

class bannerGenerator
{
  private $_address;
  private $_bakground;
  private $_font;
  private $_key;

  public function __construct()
  {
    global $config;

    $this->_address = $_GET['address'];
    $this->_background = $_GET['background'];
    $this->_font = strtolower($_GET['font']);

    $this->allowed_fonts = $config['fonts'];

    if(empty($this->_address))
    {
      $this->error = 1;
      $this->error_message = "Please specify a server address for the query!";
    }

    if(empty($this->_background))
    {
      $this->error = 1;
      $this->error_message = "Please specify a background for the banner!";
    }
    else if(!filter_var($this->_background, FILTER_VALIDATE_INT))
    {
      $this->error = 1;
      $this->error_message = "Invalid value given for the background!";
    }
    else
    {
      $this->check_dir = array_diff(scandir(__DIR__.'/img/background'), ['..', '.']);

      if(!in_array('banner_'.$this->_background.'.jpg', $this->check_dir))
      {
        $this->error = 1;
        $this->error_message = "Invalid value given for the background!";
      }
    }

    if(empty($this->_font))
    {
      $this->error = 1;
      $this->error_message = "Please specify a font for the banner!";
    }
    else if(!in_array($this->_font, $this->allowed_fonts))
    {
      $this->error = 1;
      $this->error_message = "Invalid value given for the font!";
    }

    if($config['enable_key'] == true)
    {
      $this->_key = $_GET['key'];

      if(!empty($this->_key))
      {
        if($this->_key != $config['app_key'])
        {
          $this->error = 1;
          $this->error_message = "The specified key is invalid!";
        }
      }
      else
      {
        $this->error = 1;
        $this->error_message = "Please specify the app key!";
      }
    }

    if($this->error != 1)
    {
      if($config['enable_key'] == true)
      {
        $this->query = json_decode(file_get_contents($config['app_url'].'query?address='.$this->_address.'&type=ping&key='.$config['app_key']));
      }
      else
      {
        $this->query = json_decode(file_get_contents($config['app_url'].'query?address='.$this->_address.'&type=ping'));
      }

      $this->banner_background = __DIR__.'/img/background/banner_'.$this->_background.'.jpg';
      $this->banner_font = __DIR__.'/fonts/'.$this->_font.'-regular.ttf';

      $img = Image::make($this->banner_background);

      if($this->query->online == "true")
      {
        if(!empty($this->query->icon))
        {
          $this->server_icon = Image::make($this->query->icon)->resize(42, 42)->encode('png');
        }
        else
        {
          $this->server_icon = Image::make(__DIR__.'/img/icon/server-icon.png')->resize(42, 42)->encode('png');
        }

        $img->text(strtoupper($this->_address), 58, 10, function($font) {
          $font->file($this->banner_font);
          $font->size(14);
          $font->color('#fff');
          $font->align('left');
          $font->valign('top');
        });

        $img->text('Version: '.$this->query->version, 58, 25, function($font) {
          $font->file($this->banner_font);
          $font->size(12);
          $font->color('#fff');
          $font->align('left');
          $font->valign('top');
        });

        $img->text('Players: '.$this->query->players->online.' / '.$this->query->players->max, 58, 40, function($font) {
          $font->file($this->banner_font);
          $font->size(12);
          $font->color('#fff');
          $font->align('left');
          $font->valign('top');
        });

        $img->insert($this->server_icon, 'left', 10, 10);
      }
      else
      {
        $this->server_icon = Image::make(__DIR__.'/img/icon/offline.png')->resize(42, 42)->encode('png');

        $img->text(strtoupper($this->_address), 58, 10, function($font) {
          $font->file($this->banner_font);
          $font->size(14);
          $font->color('#fff');
          $font->align('left');
          $font->valign('top');
        });

        $img->text('Version: N/A', 58, 25, function($font) {
          $font->file($this->banner_font);
          $font->size(12);
          $font->color('#fff');
          $font->align('left');
          $font->valign('top');
        });

        $img->text('Players: N/A', 58, 40, function($font) {
          $font->file($this->banner_font);
          $font->size(12);
          $font->color('#fff');
          $font->align('left');
          $font->valign('top');
        });

        $img->insert($this->server_icon, 'left', 10, 10);
      }

      $render = (string) $img->encode('jpg');

      // Render Banner
      header('Content-Type: image/jpeg');

      echo $render;
    }
    else
    {
      echo json_encode([
        'error' => $this->error_message
      ]);
    }
  }
}

$banner = new bannerGenerator;

ob_end_flush();