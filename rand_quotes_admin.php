<script>

function addInput()
{
	var x = document.getElementById('quotefields');   
	
	var input1 = document.createElement("input");
	x.appendChild(document.createTextNode("Enter Quote: "))
   	input1.setAttribute("type","text");
   	input1.setAttribute("size",50);
   	input1.setAttribute("name","quote[]" );
   	x.appendChild( input1 );
   	
	var input2 = document.createElement("input");
   	x.appendChild(document.createTextNode("Enter Author: "))
   	input2.setAttribute("type","text");
   	input2.setAttribute("size",15);
   	input2.setAttribute("name","author[]" );
   	
   	x.appendChild( input2 );
   	x.appendChild(document.createElement("br"));
   	
   	

}
</script>
<div class="wrap">
<p><font size=6><b>Shaf Random Quote Settings</b></font></p>
<?php
	if($_REQUEST['shaf_submitted']==True) //New quote is submitted
	{
		global $wpdb;
		$quotes=$_REQUEST['quote'];
		$authors=$_REQUEST['author'];
		$idx=0;
		$warning=False;
		$addcount=0;
		$failcount=0;		
		foreach($quotes as $quote)
		{
			$author=$authors[$idx];
			if($quote!='')
			{
				$addcount++;
				$data=array('quote' => $quote, 'author'=>$author);
				$wpdb->insert($wpdb->prefix."shaf_rand_quotes", $data);		
			}
			else $failcount++;
			$idx++;		
		}
		echo "<font color=green>".$addcount." quotes added</font><br>";
		if($failcount)echo "<font color=red>".$failcount." quote field was blank</font><br>";

	
	}
	if($_REQUEST['delete_quotes']=='Delete') //Delete quotes
	{
		$sel_row=$_REQUEST['sel_row'];
		global $wpdb;
		$count=0;
		foreach($sel_row as $id)
		{
			$count++;
			$data=array('id' => $id);
			$wpdb->delete($wpdb->prefix."shaf_rand_quotes", $data);
		}
		echo "<font color=red>".$count." quotes deleted</font><br>";
	}
?>


<form name='add_quote' method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="shaf_submitted" value="True">
<div id=quotefields>
<p><font size=4>Add New Quotes:</font></p>
Enter Quote: <input type="text" name='quote[]' value='' size=50 />
Enter Author: <input type="text" name='author[]' value='' size=15 /><br>
</div>
<input type=button value="Add more input field" name="addmore" onclick="addInput();" /><br>
<input type=submit value="Submit Quotes" name="submit" />

</form>
<br>
<p><font size=4>Quotes you added:</font></p>
<form name='edit_quote' method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<?php

		//Create quotes checkbox		
		global $wpdb;
		$table=$wpdb->prefix.'shaf_rand_quotes';
		$sql="SELECT * FROM $table";
		$count=0;
		$table_data = $wpdb->get_results($sql);
		foreach($table_data as $row)
		{
			$full_quote=$row->quote;
			$count++;
			if($row->author!='')
				$full_quote=$full_quote." (<i>".$row->author."</i>)";
			echo "<input type=checkbox name=sel_row[] value=$row->id />";
			echo $full_quote."<br>";
		}
		if($count==0)
		{
			echo "<font color=red>You don't have any quotes to display. Please add some quotes using the form above</font><br>";
		}
		echo "<input type=submit value=Delete name=delete_quotes /><br>";
?>
</form>

<p><font size=4>Plugin Usage:</font></p>
<p>Use <font color=green>[shaf_rand_quote]</font> shortcode anywhere to display quotes</p>
</div>

