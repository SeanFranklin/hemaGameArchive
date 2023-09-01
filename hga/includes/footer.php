<?php
/*******************************************************************************
	Footer
	
	Page footer and javascript declarations
		
*******************************************************************************/

	displayPageAlerts();

?>

	</div id='a'><!-- End Page Wrapper -->

<!-- Footer content -->
	<div class='reveal tiny text-center' id='you-suck' data-reveal>

		<img src="includes/images/suck.png">

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>
	</div>

	<div class='grid-x grid-margin-x text-right align-right'  style='border-top: 1px solid black; margin-top: 20px;'>

		<div class='grid-x grid-margin-x align-right'>

			<div class='shrink cell'>
				<div class='grid-x grid-margin-x align-right'>
					<div class='shrink cell'>
						<b>HEMA Game Archive</b><BR>
						Developed by Sean Franklin <BR>
						<a href='https://www.gd4h.org/'>GD4H project</a>
						
					</div>
					<div class='shrink cell'>
							<img src='includes/images/HGA_logo.png' data-open="you-suck">
					</div>
				</div>
			</div>
		</div>
	</div>
	

<!-- Start Scripts ------------------------------------------------------->

<!-- jQuery and Foundation -->
	<script src="includes/foundation/js/vendor/jquery.js"></script>
    <script src="includes/foundation/js/vendor/what-input.js"></script>
    <script src="includes/foundation/js/vendor/foundation.js"></script>
    <script src="includes/foundation/js/app.js<?=$vJ?>"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<!-- Summernote -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
	<script>
		$(document).ready(function() {
		  $('.summernote').summernote();
		});
		
	</script>

<!-- My scripts -->
	<script type='text/javascript' src='includes/scripts/general_scripts.js<?=$vJ?>'></script>
	<script type='text/javascript' src='includes/scripts/tag_scripts.js<?=$vJ?>'></script>
	
	<?php 
		if(isset($jsIncludes)){
			foreach((array)$jsIncludes as $includePath){
				echo "<script type='text/javascript' src='includes/scripts/{$includePath}{$vJ}'></script>";
			}
		}
	?>

<!-- Sortable data table -->
	<?php if(isset($createSortableDataTable)): ?>
			
	    <script src='https://code.jquery.com/jquery-3.3.1.js'></script>
		<script src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>
		<script src='https://cdn.datatables.net/1.10.19/js/dataTables.foundation.min.js'></script>

		<script>
		<?php foreach($createSortableDataTable as $table): 
			$tableName = $table[0];
			$tableSize = $table[1];
			?>

			$(document).ready(function() { 
				$('#<?=$tableName?>').DataTable({
					"pageLength": <?=$tableSize?>,
					stateSave: true,
				}); 
			} );
			
		<?php endforeach ?>

		google.charts.load('current', {'packages':['corechart']});
		
		</script>
		
	<?php endif ?>

<!-- End Scripts --------------------------------------------------------------->

</body>
</html>

<?php

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/


/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

?>
