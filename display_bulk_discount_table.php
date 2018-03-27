<?php
/*
Plugin Name: Woocommerce Dynamic Discount Table view
Description: Table for Dynamic Discount

*/

add_action('woocommerce_before_add_to_cart_button', 'display_bulk_discount_table');

function display_bulk_discount_table() {
	global $woocommerce, $post, $product;
	
	$array_rule_sets = get_post_meta($post->ID, '_pricing_rules', true);
	$_regular_price = get_post_meta($post->ID, '_regular_price', true);
	
  global $wp_roles;

	foreach ( $wp_roles->role_names as $role => $name ) :
		if ( current_user_can( $role ) ) 	{
			$rr=$role;
			 $rr;
			 /* show here below role if echo is on */
			// echo $rr;
		}
		
	endforeach;
		
	if ($array_rule_sets && is_array($array_rule_sets) && sizeof($array_rule_sets) > 0) {
		
	
		//echo "<pre>"; print_r($array_rule_sets); echo "</pre>";
		
		$tempstring .= '<div class="table-1">
		<table width="100%">';
		
		//echo "<pre>"; print_r($array_rule_sets); echo "</pre>";

	foreach($array_rule_sets as $aa)
		{
			foreach($aa['rules'] as $bb) {
				
			//echo "<pre>"; print_r($bb['type']); echo "</pre>";

			}
			
				if ($bb['type'] == 'percentage_discount') 
				{
					
					$tempstring .= '<th>&emsp;&emsp;&emsp;Quantity&emsp;&emsp;&emsp;</th>
									<th>% Discount</th>								<th>&emsp;&emsp;&emsp;Price&emsp;&emsp;&emsp;</th>';
					
				}
				
				elseif ($bb['type'] == 'price_discount') 
					{
						$tempstring .= '<th>&emsp;&emsp;&emsp;Quantity&emsp;&emsp;&emsp;</th>
									<th>&euro; Discount</th>								<th>&emsp;&emsp;&emsp;Netto Price&emsp;&emsp;&emsp;</th>';
						
					}
				
				else {
					
					$tempstring .= '<th>&emsp;&emsp;&emsp;Quantity&emsp;&emsp;&emsp;</th>
		<th>&emsp;&emsp;&emsp;Price&emsp;&emsp;&emsp;</th>';
					}
		}
		//$tempstring .= '<th>&emsp;&emsp;&emsp;Quantity&emsp;&emsp;&emsp;</th>
		//<th>&emsp;&emsp;&emsp;Price&emsp;&emsp;&emsp;</th>';

		foreach($array_rule_sets as $pricing_rule_sets) {
			
			if($pricing_rule_sets['conditions']['1']['args']['roles']['0'] == $rr) {
				
				foreach ( $pricing_rule_sets['rules'] as $key => $value ) {

				$tempstring .= '<tr>';
				
				if ($pricing_rule_sets['rules'][$key]['to']) 
					{
						$tempstring .= '<td>&emsp;&emsp;&emsp;'.$pricing_rule_sets['rules'][$key]['from']."- ".$pricing_rule_sets['rules'][$key]['to']."</td>&emsp;&emsp;&emsp;";
					} 
				
				else {
						$tempstring .= '<td>&emsp;&emsp;&emsp;'.$pricing_rule_sets['rules'][$key]['from']."+</td>&emsp;&emsp;&emsp;";
					}
				
					$finalprice=$_regular_price;
					
					$finalprice = $pricing_rule_sets['rules'][$key]['amount'];
					
					if ($bb['type'] == 'percentage_discount') 
					{
						$tempstring .= '
						<td>&emsp;&emsp;&emsp;<span class="discount">'.$finalprice."%</span></td>&emsp;&emsp;&emsp;";
						
						$product = new WC_Product( get_the_ID() );
						$price = $product->price;
						
						$temp_price = ($finalprice / 100) * $price;

						$new_price = $price - $temp_price;
						$new_price = number_format($new_price, 2, '.', '');
						//echo $price;
						
						$tempstring .= '<td>'.get_woocommerce_currency_symbol().' '.$new_price.' </td>';
						
					}
							
					elseif ($bb['type'] == 'price_discount')
					
					{
						
						$tempstring .= '
						<td>&emsp;&emsp;&emsp;<span class="discount">'
						.get_woocommerce_currency_symbol().' '.$finalprice."</span></td>&emsp;&emsp;&emsp;";
						
						$product = new WC_Product( get_the_ID() );
						$price = $product->price;
						
						$temp_price = $price - $finalprice;
						$temp_price = number_format($temp_price, 2, '.', '');
						
						$tempstring .= '<td>'.get_woocommerce_currency_symbol().' '.$temp_price.' </td>';
					}
					
					else {
							$finalprice = number_format($finalprice, 2, '.', '');
							$tempstring .= '<td>&emsp;&emsp;&emsp;<span class="amount">'.get_woocommerce_currency_symbol().' '.$finalprice."</span></td>&emsp;&emsp;&emsp;";
							
					}
					
					$tempstring .= '</tr>';
				}
			}
	}

	$tempstring .= "</table></div><br>";

	echo $tempstring;
	}
} 

/* EOF Add-On Name: Woocommerce-dynamic-pricing display_bulk_discount_table */


?>