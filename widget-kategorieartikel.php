<?php
	/*
		Plugin Name: Widget: Kategorieartikel
		Plugin URI: http://hovida-design.de
		Description: Dieses Plugin erstellt ein Sidebar-Widget was es ermöglicht Artikel einer bestimmten Kategorie auszugeben.
		Author: Adrian Preuß
		Version: 1.0
		Author URI: mailto:a.preuss@hovida-design.de
	*/
	
	class widget_categorys {
		private static $widget_name = "Kategorieartikel";
		
		function init() {
			register_sidebar_widget(self::$widget_name, array('widget_categorys', 'frontend'));
			register_widget_control(self::$widget_name, array('widget_categorys', 'backend'));
		}
		
		function backend() {
			if(isset($_POST['id_base'])) {
				self::save();
			}
			
			$categorys = wp_list_categories();
			print_r($categorys);
			$data = get_option("widget_categorys");
			print "<p><label for=\"title\">Titel</label><input class=\"widefat\" id=\"title\" name=\"title\" type=\"text\" value=\"" . $data->title . "\" /></p>";
			print "<p><label for=\"css\">CSS-Klasse</label><input class=\"widefat\" id=\"css\" name=\"css\" type=\"text\" value=\"" . $data->css . "\" /></p>";
			print "<p><label for=\"category\">Kategorie</label><input class=\"widefat\" id=\"category\" name=\"category\" type=\"text\" value=\"" . $data->category . "\" /></p>";
			print "<p><label for=\"limit\">Limit</label><input class=\"widefat\" id=\"limit\" name=\"limit\" type=\"text\" value=\"" . $data->limit . "\" /></p>";
			print "<p><label for=\"sort\">Sortierung</label><select class=\"widefat\" id=\"sort\" name=\"sort\"><option value=\"ASC\"" . ($data->sort == "ASC" ? " SELECTED" : "") . ">Älteste Einträge</option><option value=\"DESC\"" . ($data->sort == "DESC" ? " SELECTED" : "") . ">Neuste Einträge</option></select></p>";
		}
		
		function save() {
			$data = null;
			$data->title = $_POST['title'];
			$data->category = $_POST['category'];
			$data->limit = $_POST['limit'];
			$data->sort = $_POST['sort'];
			$data->css = $_POST['css'];		
			update_option("widget_categorys", $data);
		}
		
		function frontend($args) {
			$data = get_option("widget_categorys");
			print_r($data);
		}
	}
	
	add_action("widgets_init", array('widget_categorys', 'init'));
?>