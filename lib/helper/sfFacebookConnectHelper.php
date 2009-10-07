<?php


/**
 *
 * @param string $on_load_js
 * @author fabriceb
 * @since May 27, 2009 fabriceb
 */
function include_bottom_facebook_connect_script($on_load_js = '')
{
  if (sfFacebook::isJsLoaded())
  {
    return;
  }
  ?>
  <script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/<?php echo sfFacebook::getLocale() ?>" type="text/javascript"></script>
  <script src="/sfFacebookConnectPlugin/js/sfFacebookConnect.js" type="text/javascript"></script>

  <script type="text/javascript">
    //<![CDATA[
    var sf_fb = null;
    window.onload = function()
    {
      <?php echo init_sf_fb(); ?>
      <?php echo $on_load_js ?>
    }
    //]]>
  </script>
  <?php
  sfFacebook::setJsLoaded();
}

/**
 *
 * @author fabriceb
 * @since May 27, 2009 fabriceb
 */
function include_facebook_connect_script()
{
  if (sfFacebook::isJsLoaded())
  {
    return;
  }
  ?>
  <script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/<?php echo sfFacebook::getLocale() ?>" type="text/javascript"></script>
  <script src="/sfFacebookConnectPlugin/js/sfFacebookConnect.js" type="text/javascript"></script>

  <script type="text/javascript">
    //<![CDATA[
    if (typeof sf_fb == 'undefined')
    {
      <?php echo init_sf_fb(); ?>
    }
    //]]>
  </script>
  <?php
  sfFacebook::setJsLoaded();
}

/**
 *
 * @author fabriceb
 * @since May 27, 2009 fabriceb
 */
function include_facebook_connect_script_src()
{
  if (sfFacebook::isJsLoaded())
  {
    return;
  }
  ?>
  <script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/<?php echo sfFacebook::getLocale() ?>" type="text/javascript"></script>
  <script src="/sfFacebookConnectPlugin/js/sfFacebookConnect.js" type="text/javascript"></script>
  <?php
  sfFacebook::setJsLoaded();
}

function init_sf_fb()
{
  return "var sf_fb = new sfFacebookConnect('".sfConfig::get('app_facebook_api_key')."', '".url_for(sfConfig::get('app_facebook_connect_signin_url','sfFacebookConnectAuth/signin'))."');";
}


/**
 *
 * @param string $forward a url to forward to
 * @return string
 * @author fabriceb
 * @since May 22, 2009 fabriceb
 */
function facebook_connect_button($forward = '', $callback = '', $options = array())
{
  $default_options =
    array(
      'size' => 'medium',
      'bg'   => 'light',
      'format' => 'short'
    );
  $options = array_merge($default_options, $options);

  $js_arguments = array("'".rawurlencode($forward)."'");
  if ($callback != '')
  {
    array_push($js_arguments,$callback);
  }

  $html =
  '
  <script type="text/javascript">
    //<![CDATA[
    function fb_button_click()
    {
      if (typeof sf_fb == "undefined")
      {';
  
  
  switch(sfConfig::get('app_facebook_js_framework'))
  {
    case 'jQuery':
      $html .= '
        jQuery(function(){sf_fb.requireSession('.implode(',',$js_arguments).'); });
      ';
      break;
    case 'prototype':
      $html .= '
        document.observe("dom:loaded", function(){sf_fb.requireSession('.implode(',',$js_arguments).'); });
      ';
      break;
    case 'none':
    default:
      $html .= '
        window.onload = function() { sf_fb.requireSession('.implode(',',$js_arguments).'); };
      ';
      break;
  }
  
  
  $html .=
      '
      }
      else
      {
        sf_fb.requireSession('.implode(',',$js_arguments).');
      }
      
      return false;
    }
    //]]>
  </script>
  
  <a href="#" onclick="return fb_button_click();">'.
    image_tag(
      '/sfFacebookConnectPlugin/images/fb_'.$options['bg'].'_'.$options['size'].'_'.$options['format'].'.gif',
      array(
        'id' => 'fb_login_image',
        'alt' => 'Facebook Connect'
      )
    ).
  '</a>';

  return $html;
}
