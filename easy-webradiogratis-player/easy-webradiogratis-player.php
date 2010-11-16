<?php

/*
  Plugin Name: Easy webradio gratis player
  Plugin URI: http://player.webradiogratis.com/
  Description: Widget that plays your web radio system www.webradiogratis.com/en/ service, free internet web radio with shoutcast and centovacast.
  Author: Webradiogratis.com
  Version: 0.1
  Author URI: http://www.webradiogratis.com/en/
 */

class WP_Widget_EasyWebradiogratisPlayer extends WP_Widget {

    // The widget construct. Mumbo-jumbo that loads our code.
    function WP_Widget_EasyWebradiogratisPlayer() {
        $widget_ops = array('classname' => 'widget_EasyWebradiogratisPlayer', 'description' => __("Stream music to your visitors with this widget and webradiogratis.com"));
        $this->WP_Widget('EasyWebradiogratisPlayer', __('Easy webradio player'), $widget_ops);
    }

// End function WP_Widget_EasyWebradiogratisPlayer

    function readWebservice($username) {

        $url = "http://player.webradiogratis.com/index.php/player/webservice/" . $username;

        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_USERAGENT => "Wordpress Plugin", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return $header;
    }

    function getSkins() {

        $url = "http://player.webradiogratis.com/index.php/player/getSkins/";

        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_USERAGENT => "Wordpress Plugin", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        $result = $header;


        if ($result['errno'] != 0)
            return false;

        if ($result['http_code'] != 200)
            return false;

        $data = $result['content'];
        $data = json_decode($data);

        return $data;
    }

    function getSkinSize($skin) {

        $url = "http://player.webradiogratis.com/index.php/player/getSkinSize/" . $skin;

        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_USERAGENT => "Wordpress Plugin", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        $result = $header;


        if ($result['errno'] != 0)
            return false;

        if ($result['http_code'] != 200)
            return false;

        $data = $result['content'];
        $data = json_decode($data);

        return $data;
    }

    function getData($username) {

        $result = $this->readWebservice($username);

        if ($result['errno'] != 0)
            return false;

        if ($result['http_code'] != 200)
            return false;

        $data = $result['content'];
        $data = json_decode($data);

        return $data;
    }

    // This code displays the widget on the screen.
    function widget($args, $instance) {
        extract($args);
        extract($instance);

        //swf path
        $urlwp = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));

        //verifica o tipo de servidor
        if ($servertype == "IceCast")
            $compl = "/live";
        else
            $compl = "/;";

        echo $before_widget;

        if (!empty($radio_username)) {
            echo $before_title .
            ' <object id="obj-wrg"  width="' . $largura . '" height="' . $altura . '">
                <param name="movie" value="' . $urlwp . 'tocando.swf" />
                <param name="flashvars" value="url=http://' . $radio_hostname . ':' . $radio_port . $compl . '&codec=mp3&volume=77&tracking=true&autoplay=' . $radio_autoplay . '&skin=http://player.webradiogratis.com/swf/skins/' . $player_skin . '/' . $player_skin . '.xml&title=' . $radio_username . '&jsevents=false&welcome=Webradiogratis.com" />
                <param name="wmode" value="window" />
                <param name="scale" value="noscale" />
                <embed  src="' . $urlwp . 'tocando.swf" flashvars="url=http://' . $radio_hostname . ':' . $radio_port . $compl . '&codec=mp3&volume=77&autoplay=' . $radio_autoplay . '&tracking=true&skin=http://player.webradiogratis.com/swf/skins/' . $player_skin . '/' . $player_skin . '.xml&title=' . $radio_username . '&jsevents=false&welcome=Webradiogratis.com"  scale="noscale" width="' . $largura . '" height="' . $altura . '"   wmode="window" bgcolor="#FFFFFF" type="application/x-shockwave-flash" />
            </object> ' .
            '<a href="http://www.webradiogratis.com" title="Create web radio free" target="_blank" style="font-size:10px;">Webradiogratis.com</a> '  .
            $after_title;
        }

        echo $after_widget;
    }

// End function widget.
    // Updates the settings.
    function update($new_instance, $old_instance) {
          //Tamanhos
        if (!empty($new_instance["player_skin"])) {
            $skinsize = $this->getSkinSize($new_instance["player_skin"]);

            //Largura
            $new_instance['largura'] = $skinsize[0]->largura;
            echo '<input type="hidden" id="' . $this->get_field_id("largura") . '"
            name="' . $this->get_field_name("largura") . '"
            VALUE="' . $new_instance['largura'] . '">';
            //Altura
            $new_instance['altura'] = $skinsize[0]->altura;
            echo '<input type="hidden" id="' . $this->get_field_id("altura") . '"
            name="' . $this->get_field_name("altura") . '"
            VALUE="' . $new_instance['altura'] . '">';
        }

        return $new_instance;
    }

// End function update
    // The admin form.
    function form($instance) {

        $data = $this->getData($instance["radio_username"]);
        if ($data != false) {

            switch ($data->message) {
                case 'Ok':
                    $instance["message"] = $data->message;
                    $instance["radio_hostname"] = $data->hostname;
                    $instance["radio_port"] = $data->port;
                    break;
                case 'No playing':
                    $retorno = "The radio is not playing.";
                    break;
                case 'No have details':
                    $retorno = "Problem to view information, try a few minutes.";
                    break;
                case 'Not found':
                    $retorno = 'Radio not found. <a href="http://auth.webradiogratis.com" target="_blank">Create a free web radio now.</a>';
                    break;
            }
            if (!empty($retorno))
                echo "<strong style='color:red'>$retorno</strong><br/><br/>";
        } else {
            echo "I can't talk to the server.";
        }


        echo '<div id="EasyWebradiogratisPlayer-admin-panel">';

        echo '<input type="hidden" id="' . $this->get_field_id("radio_hostname") . '"
            name="' . $this->get_field_name("radio_hostname") . '"
            VALUE="' . $instance['radio_hostname'] . '">';
        echo '<input type="hidden" id="' . $this->get_field_id("radio_port") . '"
            name="' . $this->get_field_name("radio_port") . '"
            VALUE="' . $instance['radio_port'] . '">';
        echo '<input type="hidden" id="' . $this->get_field_id("message") . '"
            name="' . $this->get_field_name("message") . '"
            VALUE="' . $instance['message'] . '">';

        //Servertype
        if (empty($instance['servertype']))
            $instance['servertype'] = $data->servertype;
        echo '<input type="hidden" id="' . $this->get_field_id("servertype") . '"
            name="' . $this->get_field_name("servertype") . '"
            VALUE="' . $instance['servertype'] . '">';

      

        echo '<label for="' . $this->get_field_id("radio_username") . '">Radio username:</label>';
        echo '<input type="text" class="widefat" ';
        echo 'name="' . $this->get_field_name("radio_username") . '" ';
        echo 'id="' . $this->get_field_id("radio_username") . '" ';
        echo 'value="' . $instance["radio_username"] . '" />';

        echo '<label for="' . $this->get_field_id("player_skin") . '">Skin:</label><br/>';
        //skins
        echo '<select name="' . $this->get_field_name("player_skin") . '" id="' . $this->get_field_id("player_skin") . '">';
        $skinswrg = $this->getSkins();
        for ($i = 0; $i <= count($skinswrg); $i++) {
            if (!empty($skinswrg[$i]->nome)) {
                if ($instance["player_skin"] == $skinswrg[$i]->nome)
                    echo '<option value="' . $skinswrg[$i]->nome . '"  selected="selected">' . $skinswrg[$i]->nome . ' (' . $skinswrg[$i]->largura . 'x' . $skinswrg[$i]->altura . ')</option>';
                else
                    echo '<option value="' . $skinswrg[$i]->nome . '">' . $skinswrg[$i]->nome . ' (' . $skinswrg[$i]->largura . 'x' . $skinswrg[$i]->altura . ')</option>';
            }
        }
        echo '</select><br/>';
        //skins fim
        echo '<label for="' . $this->get_field_id("radio_autoplay") . '">Autoplayer:</label><br/>';
        echo '<select name="' . $this->get_field_name("radio_autoplay") . '" id="' . $this->get_field_id("radio_autoplay") . '">';
        if ($instance["radio_autoplay"] == "true") {
            echo ' <option value="true" selected="selected">True</option>';
            echo ' <option value="false" >False</option> ';
        } else {
            echo ' <option value="true">True</option>';
            echo ' <option value="false" selected="selected" >False</option> ';
        }
        echo '  </select>';


        echo '<p><br/><a href="http://www.webradiogratis.com/forum" target="_blank">www.webradiogratis.com/forum</a></p>';
        echo '</div>';
    }

// end function form
}

// end class WP_Widget_EasyWebradiogratisPlayer
// Register the widget.
add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_EasyWebradiogratisPlayer");'));
?>