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
<?php
	if($_REQUEST['shaf_submitted']==True)
	{
		global $wpdb;
		$quotes=$_REQUEST['quote'];
		$authors=$_REQUEST['author'];
		$idx=0;
		$warning=False;
		
		foreach($quotes as $quote)
		{
			$author=$authors[$idx];
			if($quote!='')
			{
				$data=array('quote' => $quote, 'author'=>$author);
				$wpdb->insert($wpdb->prefix."shaf_rand_quotes", $data);		
			}
			$idx++;		
		}
	
	}
?>
<div class="wrap">
<p><font size=6><b>Shaf Random Quote Settings</b></font></p>

<form name='add_quote' method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="shaf_submitted" value="True">
<div id=quotefields>
<p><font size=4>Add New Quotes!</font></p>
Enter Quote: <input type="text" name='quote[]' value='' size=50 />
Enter Author: <input type="text" name='author[]' value='' size=15 /><br>
</div>
<input type=button value="Add more input field" name="addmore" onclick="addInput();" /><br>
<input type=submit value="Submit Quotes" name="submit" />

</form>

</div>



