<?php
	global $contract;
	if (isset($_GET['tender_id']))
	{
		if ($_GET['tender_id'] > 0)
		{
			?>
				$("#client").change(function()
				{
					var client_id = $(this).val();
					setTimeout(function() {
						$("a.link_new_manager").attr("href", "/managers/create/?client_id="+client_id+"&return=/direction/contracts/update/?id=<?=$contract["id"]?>{AMP}master_client_id={CLIENT_ID}{AMP}master_manager_id={MANAGER_ID}{AMP}tender_id=<?=$_GET['tender_id']?>");
					}, 500);
				});					
			<?php
		}
	}
?>