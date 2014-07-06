<?php namespace comodojo\Dispatcher\Template;

class TemplateBootstrap implements TemplateInterface {

	private $template = NULL;

	private $right_nav = Array();

	private $left_nav = Array();

	private $side_nav = Array();

	private $scripts = Array();

	private $title = NULL;

	private $brand = NULL;

	private $content = NULL;

	public function __construct($page="basic") {

		$template_path = DISPATCHER_REAL_PATH."vendor/comodojo/dispatcher.template.bootstrap/resources/html/";

		switch ($page) {

			case 'navbar':
				$this->template = file_get_contents($template_path."navbar.html");
				break;

			case 'dash':
				$this->template = file_get_contents($template_path."dash.html");
				break;
			
			case 'basic':
			default:
				$this->template = file_get_contents($template_path."basic.html");
				break;

		}	

	}

	public function addDropdown($drop, $items, $menu="left") {

		switch ( strtolower($menu) ) {

			case 'left':
				$this->left_nav[$drop] = $items;
				break;

			case 'right':
				$this->right_nav[$drop] = $items;
				break;

			case 'side':
				$this->side_nav[$drop] = $items;
				break;

		}

		return $this;

	}

	public function addMenuItem($item, $href, $menu="left") {

		switch ( strtolower($menu) ) {

			case 'left':
				$this->left_nav[$item] = $href;
				break;

			case 'right':
				$this->right_nav[$item] = $href;
				break;

			case 'side':
				$this->side_nav[$item] = $href;
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

	public function addScript($source) {

		array_push($this->scripts, '<script src="'.$source.'" "></script>');

		return $this;

	}

	public function replace($tag, $data) {

		$this->template = str_replace($tag, $data, $this->template);

	}

	public function serialize() {

		if ( sizeof($this->left_nav) != 0 ) {

			$nav = '<ul class="nav navbar-nav">';

			foreach ($this->left_nav as $key => $value) {

				if ( is_array($value) ) {

					$nav .= '<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$key.'<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">';

					foreach ($value as $mkey => $mvalue) {
						$nav .=  '<li><a href="' . $mvalue . '">' . $mkey . '</a></li>';
					}

					$nav .= '</ul></li>';

				}
				else $nav .= '<li><a href="'.$value.'">'.$key.'</a></li>';
			}
			
			$nav .= '</ul>';

			$this->replace("__NAVBAR_LEFT__",$nav);

		}

		else $this->replace("__NAVBAR_LEFT__","");

		if ( sizeof($this->right_nav) != 0 ) {

			$nav = '<ul class="nav navbar-nav navbar-right">';

			foreach ($this->right_nav as $key => $value) {

				if ( is_array($value) ) {

					$nav .= '<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$key.'<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">';

					foreach ($value as $mkey => $mvalue) {
						$nav .=  '<li><a href="' . $mvalue . '">' . $mkey . '</a></li>';
					}

					$nav .= '</ul></li>';
					
				}
				else $nav .= '<li><a href="'.$value.'">'.$key.'</a></li>';
			}
			
			$nav .= '</ul>';

			$this->replace("__NAVBAR_RIGHT__",$nav);

		}

		else $this->replace("__NAVBAR_RIGHT__","");

		if ( sizeof($this->side_nav) != 0 ) {

			$nav = '<ul class="nav nav-sidebar">';

			foreach ($this->side_nav as $key => $value) {

				if ( is_array($value) ) {

					$nav .= '<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$key.'<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">';

					foreach ($value as $mkey => $mvalue) {
						$nav .=  '<li><a href="' . $mvalue . '">' . $mkey . '</a></li>';
					}

					$nav .= '</ul></li>';
					
				}
				else $nav .= '<li><a href="'.$value.'">'.$key.'</a></li>';
			}
			
			$nav .= '</ul>';

			$this->replace("__NAVBAR_SIDE__",$nav);

		}

		else $this->replace("__NAVBAR_SIDE__","");

		$this->replace("__TITLE__", $this->title);

		$this->replace("__BRAND__", $this->brand);

		$this->replace("__SCRIPTS__", implode("\n",$this->scripts));

		$this->replace("__CONTENT__", $this->content);

		$this->replace("__DISPATCHER_BASEURL__", DISPATCHER_BASEURL);

		return $this->template;

	}

}

?>