<?php
$q = 'SELECT id, name, parent_id FROM categories';
$r = mysqli_query($conn, $q);

$categories = array();

while(list($category_id, $category, $parent_id) = mysqli_fetch_array($r, MYSQLI_NUM))
{
	$categories[$parent_id][$category_id] = $category;
}

//Doesn't work very well. Possibly bootstrap css that screws it up.
function make_list($parent, $bool = false) {
    
	global $categories;
    echo '<ol style="list-style: none;">';

	foreach ($parent as $category_id => $cat) {

	    echo '<li class="'.(isset($categories[$category_id]) ? "dropdowns" : "").'"><a href="products.php?category_id=' . $category_id . '">' . $cat . '</a>'; 
            
		if (isset($categories[$category_id])) { 
			echo '<div class="'.(($bool) ? "dropdowns-itmes" : "dropdowns-content").'">';
			make_list($categories[$category_id], true);
			echo '</div>';
		}         
        echo '</li>'; 
    }  
    echo '</ol>';
}

?>