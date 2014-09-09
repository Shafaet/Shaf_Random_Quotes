<?php
    /*
    Plugin Name: Shaf Random Quotes
    Plugin URI: https://github.com/Shafaet/Shaf_Random_Quotes
    Description: Plugin for displaying random quotes
    Author: Shafaet Ashraf
    Version: 1.10
    Author URI: http://www.shafaetsplanet.com
    */

    /*
    Copyright (C) Shafaet Ashraf 2014

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
    
    class ShafRandomQuotes {
    	
    	
    	
    	public function __construct()
    	{
    		add_action( 'admin_menu', array($this,'my_plugin_menu') );
    		global $wpdb;
		$charset_collate = '';

		if ( ! empty( $wpdb->charset ) ) {
		  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}

		if ( ! empty( $wpdb->collate ) ) {
		  $charset_collate .= " COLLATE {$wpdb->collate}";
		}
		$table_name = $wpdb->prefix."shaf_rand_quotes";
		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		  quote text NOT NULL,
		  author text NULL,
		  UNIQUE KEY id(id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

    	}
    	
    	
	public function my_plugin_menu() {
		$text = 'shaf_random_quotes';
		add_options_page($text, "Shaf Random Quotes", 'manage_options', $text, array($this,'my_plugin_options') );
	}
	
	function my_plugin_options() {
		if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		include('rand_quotes_admin.php');
	}
	
	function activate_shortCode()
	{
		add_shortcode( 'shaf_rand_quote', array($this,'shaf_rand_quote') );
	}
	function shaf_rand_quote(){
	
		global $wpdb;
		$table=$wpdb->prefix.'shaf_rand_quotes';
		$sql="SELECT * FROM $table";
	
		$table_data = $wpdb->get_results($sql);
		echo "<form>";
		$count=0;
		foreach($table_data as $row)
		{
			$count+=1;
			$full_quote=$row->quote;
			if($row->author!='')
				$full_quote=$full_quote." (<i>".$row->author."</i>)";
			echo "<input type=hidden id=hidden_quote".$count." value='$full_quote' />";
		}
		echo "</form>";
		 
		setcookie("Shaf_number_of_quotes",$count);
		return "
		<p id='shaf_quotes'></p>
		<script>
		function f()
		{
			match = document.cookie.match(new RegExp('Shaf_number_of_quotes' + '=([^;]+)'));
  			var number_of_quotes=match[1];
			setInterval(function(){
				var show_quote_num=Math.floor((Math.random() * number_of_quotes) + 1);
				document.getElementById('shaf_quotes').innerHTML = document.getElementById('hidden_quote'+show_quote_num).value;
			}, 2000);


		}
		f();
		</script>

		</script>";
		
	}
	

    }
    $Shaf_Random_Quotes = new ShafRandomQuotes();
    $Shaf_Random_Quotes->activate_shortCode();
    	

	
	
	

?>
