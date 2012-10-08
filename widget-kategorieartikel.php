<?php
	/*
		Plugin Name:		Widget: Kategorieartikel
		Plugin URI:			http://hovida-design.de
		Description:		Dieses Plugin erstellt ein Sidebar-Widget was es ermöglicht Artikel einer bestimmten Kategorie auszugeben.
		Author:				Adrian Preuß
		Version:			1.4
		Author URI:			mailto:a.preuss@hovida-design.de
	*/

	class widget_categorys extends WP_Widget {
		private static $widget_name		= "Kategorieartikel";
		private static $widget_slug		= "widget-kategorieartikel";
		private static $widget_class	= "widget_categorys";
		private static $help			= "";
		
		function widget_categorys() {
			self::$help		= "<a href=\"plugin-install.php?tab=plugin-information&plugin=" . self::$widget_slug . "&section=faq&TB_iframe=true&width=640&height=566\" style=\"color: #FF0000; float: right;\" class=\"thickbox\" title=\"FAQ aufrufen\">Hilfe aufrufen!</a>";
			$options		= array(
				'classname'		=> self::$widget_class,
				'description'	=> __('Dieses Plugin erstellt ein Sidebar-Widget was es ermöglicht Artikel einer bestimmten Kategorie auszugeben.')
			);
			
			$control		= array(
				'id_base'		=> self::$widget_class
			);
			
			self::WP_Widget('widget_categorys', self::$widget_name, $options, $control);
		}
		
		function init() {
			register_widget(self::$widget_class);
			$template = get_template_directory();
			
			if(is_dir($template . "/css/") && !file_exists($template . "/" . self::$widget_slug . ".css")) {
				if(file_exists($template . "/css/" . self::$widget_slug . ".css")) {
					wp_enqueue_style(self::$widget_slug, $template . "/css/" . self::$widget_slug . ".css");
				} else {
					wp_enqueue_style(self::$widget_slug, plugins_url(self::$widget_slug . '.css', __FILE__));
				}
			} else {
				if(!file_exists($template . "/" . self::$widget_slug . ".css")) {
					wp_enqueue_style(self::$widget_slug, $template . "/" . self::$widget_slug . ".css");
				} else {
					wp_enqueue_style(self::$widget_slug, plugins_url(self::$widget_slug . '.css', __FILE__));
				}
			}
		}
		
		function admin_init() {
			wp_enqueue_script(self::$widget_slug, plugins_url(self::$widget_slug . '.js', __FILE__), array('jquery'), '1.3', true);
			wp_enqueue_script('thickbox',null,array('jquery'));
			wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');
		}

		/* Backend */
		function form($instance) {
			if($this->id == "widget_categorys-__i__") {
				print "<p>Das Hinzufügen des Widgets erfordert ein neu laden der Seite: <a href=\"widgets.php\">Jetzt neu laden</a></p>";
			} else {
				if(isset($_POST['id_base'])) {
					self::save($this->id);
				}
				
				$categorys	= get_categories(array(
					'hide_empty'	=> 0
				));

				$data		= get_option($this->id);
				print "<p><strong>Probleme?</strong> " . self::$help . "</p>";
				print "<p><label for=\"title\">Titel</label><input class=\"widefat\" id=\"title\" name=\"title\" type=\"text\" value=\"" . $data->title . "\" /></p>";
				print "<p><label for=\"with_count\">Artikelanzahl im Titel</label><br /><input id=\"with_count\" name=\"with_count\" type=\"radio\" value=\"1\"" . ($data->with_count == 1 && $data->with_count != 0 ? " CHECKED" : "") . " /> Ja <input id=\"with_count\" name=\"with_count\" type=\"radio\" value=\"0\"" . ($data->with_count == 0 ? " CHECKED" : "") . " /> Nein</p>";
				print "<p><label for=\"css\">CSS-Klasse</label><input class=\"widefat\" id=\"css\" name=\"css\" type=\"text\" value=\"" . $data->css . "\" /></p>";
				print "<p><label for=\"category\">Kategorie</label>";
				print "<select class=\"widefat\" id=\"category\" name=\"category\">";
				
				for($i = 0; $i < count($categorys); $i++) {
					print "<option value=\"" . $categorys[$i]->cat_ID . "\"" . ($categorys[$i]->cat_ID == $data->category ? " SELECTED" : "") . ">" . $categorys[$i]->name . "</option>";
				}
				
				print "</select></p>";
				print "<p><label for=\"limit\">Artikel-Limit</label><input class=\"widefat\" id=\"limit\" name=\"limit\" type=\"text\" value=\"" . $data->limit . "\" /></p>";
				print "<p><label for=\"sort\">Sortierung</label><select class=\"widefat\" id=\"sort\" name=\"sort\"><option value=\"ASC\"" . ($data->sort == "ASC" ? " SELECTED" : "") . ">Älteste Einträge</option><option value=\"DESC\"" . ($data->sort == "DESC" ? " SELECTED" : "") . ">Neuste Einträge</option></select></p>";
				print "<p><label for=\"with_content\">Text ausgeben</label><br /><input id=\"with_content\" name=\"with_content\" type=\"radio\" value=\"1\"" . ($data->with_content == 1 && $data->with_content != 0 ? " CHECKED" : "") . " /> Ja <input id=\"with_content\" name=\"with_content\" type=\"radio\" value=\"0\"" . ($data->with_content == 0 ? " CHECKED" : "") . " /> Nein</p>";
				print "<p class=\"for_with_content\"" . ($data->with_content == 0 ? " style=\"display: none;\"" : "") . "><label for=\"length\">Textlänge</label><input class=\"widefat\" id=\"length\" name=\"length\" type=\"text\" value=\"" . $data->length . "\" /></p>";
				print "<p class=\"for_with_content\"" . ($data->with_content == 0 ? " style=\"display: none;\"" : "") . "><label for=\"more_label\">Link-Beschriftung</label><input class=\"widefat\" id=\"more_label\" name=\"more_label\" type=\"text\" value=\"" . $data->more_label . "\" /></p>";
				print "<p><label for=\"with_thumbnail\">Bild ausgeben</label><br /><input id=\"with_thumbnail\" name=\"with_thumbnail\" type=\"radio\" value=\"1\"" . ($data->with_thumbnail == 1 && $data->with_thumbnail != 0 ? " CHECKED" : "") . " /> Ja <input id=\"with_thumbnail\" name=\"with_thumbnail\" type=\"radio\" value=\"0\"" . ($data->with_thumbnail == 0 ? " CHECKED" : "") . " /> Nein</p>";
				print "<p class=\"for_with_thumbnail\"" . ($data->with_thumbnail == 0 ? " style=\"display: none;\"" : "") . "><label for=\"size_width\">Bildgröße</label><br /><input id=\"size_width\" size=\"5\" name=\"size_width\" type=\"text\" value=\"" . $data->size_width . "\" /> x <input id=\"size_height\" size=\"5\" name=\"size_height\" type=\"text\" value=\"" . $data->size_height . "\" /></p>";
				print "<p class=\"for_with_thumbnail\"" . ($data->with_thumbnail == 0 ? " style=\"display: none;\"" : "") . "><label for=\"image_position\">Bildposition</label><br /><select name=\"image_position\"><option value=\"0\"" . ($data->image_position == 0 ? " SELECTED" : "") . ">Links</option><option value=\"1\"" . ($data->image_position == 1 ? " SELECTED" : "") . ">Rechts</option></select></p>";
			}
		}

		/* Save settings */
		function save($id) {
			$instance					= null;
			$instance->title			= $_POST['title'];
			$instance->category			= $_POST['category'];
			$instance->limit			= $_POST['limit'];
			$instance->sort				= $_POST['sort'];
			$instance->css				= $_POST['css'];
			$instance->length			= $_POST['length'];
			$instance->with_content		= $_POST['with_content'];
			$instance->with_thumbnail	= $_POST['with_thumbnail'];
			$instance->size_width		= $_POST['size_width'];
			$instance->size_height		= $_POST['size_height'];
			$instance->with_count		= $_POST['with_count'];
			$instance->more_label		= $_POST['more_label'];
			$instance->image_position	= $_POST['image_position'];
			update_option($id, $instance);
		}
		
		function update($new_instance, $old_instance) {
			$instance			= $old_instance;
			$instance['title']	= strip_tags($new_instance['title']);
			return $instance;
		}
		
		/* Frontend */
		function widget($args, $instance) {
			global $post;
			$data		= get_option($this->id);
			$classes	= self::$widget_class;
			$articles	= get_posts(array(
				"category"		=> $data->category,
				"order"			=> $data->sort,
				"numberposts"	=> $data->limit
			));
			
			if($data->css != "") {
				$classes = " " . $data->css;
			}
			
			print "<div class=\"" . $classes . "\">";
			print "<h3 class=\"widget-title\">" . $data->title . ($data->with_count == 1 ? "<span>(" . count($articles) . ")</span>" : "") . "</h3>";
			
			for($i = 0; $i < count($articles); $i++) {
				$class_entry	= " ";
				
				if($data->with_thumbnail == 1 && $data->image_position == 0) {
					$class_entry .= "thumb_left ";
				} else if($data->with_thumbnail == 1 && $data->image_position == 0) {
					$class_entry .= "thumb_right ";
				}
				
				print "<div class=\"entry" . rtrim($class_entry, " ") . "\">";
				print "<div class=\"top\"></div>";
				print "<div class=\"middle\">";
				
				if($data->with_thumbnail == 1 && $data->image_position == 0) {
					if(has_post_thumbnail($articles[$i]->ID)) {
						print get_the_post_thumbnail($articles[$i]->ID, array($data->size_width, $data->size_height), array('class' => 'left'));
					} else {
						echo "<img class=\"left\" style=\"max-width: " . $data->size_width . "px; max-height: " . $data->size_height . "px;\" src=\"" . plugins_url("default.png", __FILE__) . "\" alt=\"\" />";
					}
				}
				
				print "<h3><a href=\"" . get_permalink($articles[$i]->ID) . "\">" . $articles[$i]->post_title . "</a></h3>";
				
				if($data->with_content == 1) {
					if($data->length > 0) {
						$articles[$i]->post_content = substr($articles[$i]->post_content, 0, $data->length) . " <a href=\"" . get_permalink($articles[$i]->ID) . "\">" . $data->more_label . "</a>";
					}
					
					print "<p>" . $articles[$i]->post_content . "</p>";
				}
				
				if($data->with_thumbnail == 1 && $data->image_position == 1) {
					if(has_post_thumbnail($articles[$i]->ID)) {
						print get_the_post_thumbnail($articles[$i]->ID, array($data->size_width, $data->size_height), array('class' => 'right'));
					} else {
						echo "<img class=\"right\" style=\"max-width: " . $data->size_width . "px; max-height: " . $data->size_height . "px;\" src=\"" . plugins_url("default.png", __FILE__) . "\" alt=\"\" />";
					}
				}
				
				print "<div class=\"clear\"></div>";
				print "</div>";
				print "<div class=\"bottom\"></div>";
				print "</div>";
			}
			
			print "</div>";
		}
		
		/* Install */
		function install() {
			$template = get_template_directory();
			if(is_dir($template . "/css/") && !file_exists($template . "/" . self::$widget_slug . ".css")) {
				if(!file_exists($template . self::$widget_slug . ".css")) {
					if(!copy(dirname(__FILE__) . "/" . self::$widget_slug . ".css", $template . "/css/" . self::$widget_slug . ".css")) {
						print "Die Stylesheet \"" .  self::$widget_slug . ".css\" konnte nicht zum Theme kopiert werden (" . $template . "/css/)";
					}
				}
			} else {
				if(!file_exists($template . "/" . self::$widget_slug . ".css")) {
					if(!copy(dirname(__FILE__) . "/" . self::$widget_slug . ".css", $template . "/" . self::$widget_slug . ".css")) {
						print "Die Stylesheet \"" .  self::$widget_slug . ".css\" konnte nicht zum Theme kopiert werden (" . $template . "/)";
					}
				}
			}
		}
	}

	/* Hooks */
	add_action('widgets_init', array('widget_categorys', 'init'), 1);
	add_action('admin_init', array('widget_categorys', 'admin_init'));
	register_activation_hook(__FILE__, array('widget_categorys', 'install'));
?>