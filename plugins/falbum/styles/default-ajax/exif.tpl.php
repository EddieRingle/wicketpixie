<table>
	<?php foreach ($exif as $row): ?>	
	<tr class='<?php echo $row['class']; ?>'>
		<td><?php echo $row['label']; ?></td>
		<td><?php echo $row['data']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
