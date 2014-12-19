<?php

function beginTestTableOutput()
{
	echo '<table border="1" style="width:1200px">'.
		 	'<thead>'.
		 		'<tr>';

	echo 			'<th>Test ID</th>';
	echo 			'<th>Title</th>';
	echo 			'<th>Status</th>';
	echo 			'<th>Failure Reason</th>';
	echo 	    '<tr>'.
		 	'</thead>'.
		 	'<tbody>';
}

function rowTestTableOutput($testID,$testTitle,$testResult,$error)
{
	echo		'<tr>';
	echo 			'<td>'.$testID.'</td>';
	echo 			'<td>'.$testTitle.'</td>';
	echo 			'<td>'.$testResult.'</td>';
	echo 			'<td>'.$error.'</td>';
	echo 	    '</tr>';
}

function endTestTableOutput()
{
	echo 	'</tbody>'.
		 '<table>';

}

?>