<?php namespace Comodojo\Dispatcher\Template;

class TemplateBootstrap implements TemplateInterface {

    private $template = null;

    private $menus = array(
        "left"  => null,
        "side"  => null,
        "right" => null
    );

    private $menuitems = array(
        "left"  => array(),
        "side"  => array(),
        "right" => array()
    );

    // private $right_nav = array();

    // private $left_nav = array();

    // private $side_nav = array();

    private $scripts = array();

    private $css = array();

    private $title = null;

    private $brand = null;

    private $content = null;

    public function __construct($page="basic", $template="default") {

        $pages_path = DISPATCHER_REAL_PATH."vendor/comodojo/dispatcher.template.bootstrap/resources/html/";

        $templates_path = DISPATCHER_BASEURL."vendor/comodojo/dispatcher.template.bootstrap/resources/css/";

        switch ($page) {

            case 'navbar':
                $this->template = file_get_contents($pages_path."navbar.html");
                break;

            case 'dash':
                $this->template = file_get_contents($pages_path."dash.html");
                break;
            
            case 'basic':
            default:
                $this->template = file_get_contents($pages_path."basic.html");
                break;

        }

        $this->addCss($templates_path.$template."/bootstrap.min.css");

    }

    public function addCss($ref) {

        $pattern = '<link href="'.$ref.'" rel="stylesheet">';

        if ( !empty($ref) ) array_push($this->css, $pattern);

        return $this;

    }

    public function addScript($source) {

        array_push($this->scripts, '<script src="'.$source.'"></script>');

        return $this;

    }

    public function addMenu($position="right", $cssClass=null) {

        switch ($position) {

            case 'left':
                $this->menus['left'] = '<ul class="nav navbar-nav navbar-left '.$cssClass.'">__MENUCONTENT__</ul>';
                break;

            case 'side':
                $this->menus['side'] = '<ul class="nav nav-sidebar '.$cssClass.'">__MENUCONTENT__</ul>';
                break;

            case 'right':
            default:
                $this->menus['right'] = '<ul class="nav navbar-nav navbar-right '.$cssClass.'">__MENUCONTENT__</ul>';
                break;
        }

        return $this;

    }

    public function addMenuItem($name, $ref, $attachTo="right", $subitems=null, $cssClass=null) {

        switch ($attachTo) {

            case 'left':

                if ( is_array($subitems) ) {

                    $item = '<li class="dropdown '.$cssClass.'">
                                <a href="'.$ref.'" class="dropdown-toggle" data-toggle="dropdown">'.$name.'<span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">';

                    foreach ($subitems as $subname => $subcontext) {
                        $item .= '<li><a href="' . $subcontext . '">' . $subname . '</a></li>';
                    }

                    $item .= '</ul></li>';

                    array_push($this->menuitems['left'], $item);

                }

                else array_push($this->menuitems['left'], '<li class="'.$cssClass.'"><a href="'.$ref.'">'.$name.'</a></li>');
                
                break;

            case 'side':
                
                if ( is_array($subitems) ) {

                    $item = '<li class="nav nav-stacked fixed '.$cssClass.'">
                                <a href="'.$ref.'" >'.$name.'</a>
                                    <ul class="nav nav-stacked">';

                    foreach ($subitems as $subname => $subcontext) {
                        $item .= '<li><a href="' . $subcontext . '">' . $subname . '</a></li>';
                    }

                    $item .= '</ul></li>';

                    array_push($this->menuitems['side'], $item);

                }

                else array_push($this->menuitems['side'], '<li class="nav nav-stacked fixed '.$cssClass.'"><a href="'.$ref.'">'.$name.'</a></li>');

                break;

            case 'right':
            default:

                if ( is_array($subitems) ) {

                    $item = '<li class="dropdown '.$cssClass.'">
                                <a href="'.$ref.'" class="dropdown-toggle" data-toggle="dropdown">'.$name.'<span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">';

                    foreach ($subitems as $subname => $subcontext) {
                        $item .= '<li><a href="' . $subcontext . '">' . $subname . '</a></li>';
                    }

                    $item .= '</ul></li>';

                    array_push($this->menuitems['right'], $item);

                }

                else array_push($this->menuitems['right'], '<li class="'.$cssClass.'"><a href="'.$ref.'">'.$name.'</a></li>');

                break;
        }

        return $this;

    }

    public function setTitle($title) {

        $this->title = $title;

        return $this;

    }

    public function setBrand($brand) {

        $this->brand = $brand;

        return $this;

    }

    public function setContent($content) {

        $this->content = $content;

        return $this;

    }

    public function replace($tag, $data) {

        $this->template = str_replace($tag, $data, $this->template);

    }

    public function serialize() {

        if ( !is_null($this->menus['left']) ) {

            $left = str_replace('__MENUCONTENT__', implode("\n", $this->menuitems['left']), $this->menus['left']);

            $this->replace("__NAVBAR_LEFT__",$left);

        } else {

            $this->replace("__NAVBAR_LEFT__","");

        }

        if ( !is_null($this->menus['right']) ) {

            $right = str_replace('__MENUCONTENT__', implode("\n", $this->menuitems['right']), $this->menus['right']);

            $this->replace("__NAVBAR_RIGHT__",$right);

        } else {

            $this->replace("__NAVBAR_RIGHT__","");
            
        }

        if ( !is_null($this->menus['side']) ) {

            $side = str_replace('__MENUCONTENT__', implode("\n", $this->menuitems['side']), $this->menus['side']);

            $this->replace("__NAVBAR_SIDE__",$side);

        } else {

            $this->replace("__NAVBAR_SIDE__","");
            
        }

        $this->replace("__TITLE__", $this->title);

        $this->replace("__BRAND__", $this->brand);

        $this->replace("__CSS__", implode("\n",$this->css));

        $this->replace("__SCRIPTS__", implode("\n",$this->scripts));

        $this->replace("__CONTENT__", $this->content);

        $this->replace("__DISPATCHER_BASEURL__", DISPATCHER_BASEURL);

        return $this->template;

    }

}