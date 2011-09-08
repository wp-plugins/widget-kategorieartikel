<?php
	/*
		Plugin Name: Widget: Kategorieartikel
		Plugin URI: http://hovida-design.de
		Description: Dieses Plugin erstellt ein Sidebar-Widget was es ermöglicht Artikel einer bestimmten Kategorie auszugeben.
		Author: Adrian Preuß
		Version: 1.2
		Author URI: mailto:a.preuss@hovida-design.de
	*/

	class widget_categorys extends WP_Widget {
		private static $widget_name = "Kategorieartikel";
		private static $widget_class = "widget_categorys";
		
		function widget_categorys() {
			$options = array('classname' => self::$widget_class, 'description' => __('Dieses Plugin erstellt ein Sidebar-Widget was es ermöglicht Artikel einer bestimmten Kategorie auszugeben.'));
			$control = array('id_base' => self::$widget_class);
			self::WP_Widget('widget_categorys', self::$widget_name, $options, $control);
		}
		
		function init() {
			register_widget(self::$widget_class);
		}

		/* Backend */
		function form($instance) {
			if($this->id == "widget_categorys-__i__") {
				print "<p>Das Hinzufügen des Widgets erfordert ein neu laden der Seite: <a href=\"widgets.php\">Jetzt neu laden</a></p>";
			} else {
				if(isset($_POST['id_base'])) {
					self::save($this->id);
				}
				
				$categorys = get_categories();
				$data = get_option($this->id);
				print "<p><label for=\"title\">Titel</label><input class=\"widefat\" id=\"title\" name=\"title\" type=\"text\" value=\"" . $data->title . "\" /></p>";
				print "<p><label for=\"css\">CSS-Klasse</label><input class=\"widefat\" id=\"css\" name=\"css\" type=\"text\" value=\"" . $data->css . "\" /></p>";
				print "<p><label for=\"category\">Kategorie</label>";
				print "<p><label for=\"category\">Kategorie</label>";
				print "<select class=\"widefat\" id=\"category\" name=\"category\">";
				
				for($i = 0; $i < count($categorys); $i++) {
					print "<option value=\"" . $categorys[$i]->cat_ID . "\"" . ($categorys[$i]->cat_ID == $data->category ? " SELECTED" : "") . ">" . $categorys[$i]->name . "</option>";
				}
				
				print "</select></p>";
				print "<p><label for=\"limit\">Limit</label><input class=\"widefat\" id=\"limit\" name=\"limit\" type=\"text\" value=\"" . $data->limit . "\" /></p>";
				print "<p><label for=\"sort\">Sortierung</label><select class=\"widefat\" id=\"sort\" name=\"sort\"><option value=\"ASC\"" . ($data->sort == "ASC" ? " SELECTED" : "") . ">Älteste Einträge</option><option value=\"DESC\"" . ($data->sort == "DESC" ? " SELECTED" : "") . ">Neuste Einträge</option></select></p>";
				print "<p><label for=\"length\">Textlänge</label><input class=\"widefat\" id=\"length\" name=\"length\" type=\"text\" value=\"" . $data->length . "\" /></p>";
			}
		}

		/* Save settings */
		function save($id) {
			$instance = null;
			$instance->title = $_POST['title'];
			$instance->category = $_POST['category'];
			$instance->limit = $_POST['limit'];
			$instance->sort = $_POST['sort'];
			$instance->css = $_POST['css'];	
			$instance->length = $_POST['length'];	
			update_option($id, $instance);
		}
		
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			print_r($instance);
			return $instance;
		}
		
		/* Frontend */
		function widget($args, $instance) {
			global $post;
			$data = get_option($this->id);
			$articles = get_posts(array("category" => $data->catergory, "order" => $data->sort, "numberposts" => $data->limit));
			
			print "<div class=\"" . $data->css . "\">";
			print "<h2><span>" . $data->title . "</span></h2>";
			
			for($i = 0; $i < count($articles); $i++) {
				print "<div class=\"entry\">";
				print "<div class=\"top\"></div>";
				print "<div class=\"middle\"><h3>" . $articles[$i]->post_title . "</h3><p>" . ($data->length > 0 ? substr($articles[$i]->post_content, 0, $data->length) . " <a href=\"" . $articles[$i]->guid . "\">[...]</a>" : $articles[$i]->post_content) . "</p></div>";
				print "<div class=\"bottom\"></div>";
				print "</div>";
			}
			
			print "</div>";
		}
	}

	add_action("widgets_init", array('widget_categorys', 'init'), 1);
?>