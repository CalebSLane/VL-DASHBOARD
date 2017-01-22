<script type="text/javascript">
	$().ready(function(){
		$.get("<?php echo base_url();?>template/dates", function(data){
    		obj = $.parseJSON(data);
	
			if(obj['month'] == "null" || obj['month'] == null){
				obj['month'] = "";
			}
			$(".display_date").html("( "+obj['year']+" "+obj['month']+" )");
			$(".display_range").html("( "+obj['prev_year']+" - "+obj['year']+" )");
    	});

    	$("#first").show();
		$("#second").hide();

		$("#county").load("<?php echo base_url('charts/summaries/county_outcomes');?>");
		$("#county_sites").load("<?php echo base_url('charts/county/county_table');?>");

		$("select").change(function(){
			em = $(this).val();

			// Send the data using post
	        var posting = $.post( "<?php echo base_url();?>template/filter_county_data", { county: em } );
	     
	        // Put the results in a div
	        posting.done(function( data ) {
	        	$.get("<?php echo base_url();?>template/breadcrum/"+data, function(data){
	        		$("#breadcrum").html(data);
	        	});
	        	$.get("<?php echo base_url();?>template/dates", function(data){
	        		obj = $.parseJSON(data);
			
					if(obj['month'] == "null" || obj['month'] == null){
						obj['month'] = "";
					}
					$(".display_date").html("( "+obj['year']+" "+obj['month']+" )");
					$(".display_range").html("( "+obj['prev_year']+" - "+obj['year']+" )");
	        	});

	        	// alert(data);
	        	//$('#heading').html('Sub-Counties Outcomes <div class="display_date"></div>');
	        	

	        	if(em == 48){
	        		$("#first").show();
					$("#second").hide();

					$("#county_sites").html("<center><div class='loader'></div></center>");
					$("#county_sites").load("<?php echo base_url('charts/county/county_table'); ?>"); 
	        	}
	        	else{
	        		$("#second").show();
					$("#first").hide();

					$("#sub_counties").html("<center><div class='loader'></div></center>");
					$("#sub_counties").load("<?php echo base_url('charts/county/county_subcounties'); ?>/"+null+"/"+null+"/"+data);
	        	}

		         
	        });
		});
	});

	function date_filter(criteria, id)
 	{
 		if (criteria === "monthly") {
 			year = null;
 			month = id;
 		}else {
 			year = id;
 			month = null;
 		}

 		var posting = $.post( '<?php echo base_url();?>template/filter_date_data', { 'year': year, 'month': month } );

 		// Put the results in a div
		posting.done(function( data ) {
			obj = $.parseJSON(data);
			
			if(obj['month'] == "null" || obj['month'] == null){
				obj['month'] = "";
			}
			$(".display_date").html("( "+obj['year']+" "+obj['month']+" )");
			$(".display_range").html("( "+obj['prev_year']+" - "+obj['year']+" )");
			
		});

		$("#county").html("<center><div class='loader'></div></center>"); 
 		$("#county").load("<?php echo base_url('charts/summaries/county_outcomes'); ?>/"+year+"/"+month);
		

		$.get("<?php echo base_url('county/check_county_select');?>", function(site) {
			//Checking if site was previously selected and calling the relevant views
			console.log(site);
			if (site==0) {
				$("#first").show();
				$("#second").hide();

				$("#county_sites").html("<center><div class='loader'></div></center>");
				$("#county_sites").load("<?php echo base_url('charts/county/county_table'); ?>/"+year+"/"+month);

			} else {
				$("#second").show();
				$("#first").hide();

				$("#sub_counties").html("<center><div class='loader'></div></center>");
				$("#sub_counties").load("<?php echo base_url('charts/county/county_subcounties'); ?>/"+year+"/"+month);
			}
		});
 		
		
 		 
	}
</script>