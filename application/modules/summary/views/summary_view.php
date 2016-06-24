<style type="text/css">
	.display_date {
		width: 130px;
		display: inline;
	}
	.display_range {
		width: 130px;
		display: inline;
	}
	#nattatdiv {
		background-color: white;
		margin-right: 1em;
		margin-left: 1em;
		margin-bottom: 1em;
	}
	#title {
		padding-top: 1.5em;
		color: blue;
	}
</style>
<div class="row">
	<div class="col-md-12" id="nattatdiv">
		<div class="col-md-6 col-md-offset-6">
			<div class="col-md-4" id="title">
				<center>National TAT</center>
			</div>
			<div class="col-md-8">
				<div id="nattat"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    Sample Types <div class="display_range"></div>
		  </div>
		  <div class="panel-body" id="samples">
		    <div>Loading...</div>
		  </div>
		</div>
	</div>
</div>
<div class="row">
	<!-- Map of the country -->
	<div class="col-md-3 col-sm-3 col-xs-12">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		  	VL Outcomes <div class="display_date" ></div>
		  </div>
		  <div id="vlOutcomes">
		  	<div>Loading...</div>
		  </div>
		  
		</div>
	</div>
	<!-- Map of the country -->
	<div class="col-md-4 col-sm-4 col-xs-12">
		<div class="row">
			<div class="panel panel-primary">
			  <div class="panel-heading">
				  Justification for tests <div class="display_date"></div>
			  </div>
			  <div class="panel-body" id="justification">
			    <div>Loading...</div>
			  </div>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-primary">
				  <div class="panel-heading">
				    Age <div class="display_date"></div>
				  </div>
				  <div class="panel-body" id="ageGroups">
				    <div>Loading...</div>
				  </div>
				  <div>
				  	<button class="btn btn-primary" onclick="ageModal();">Click here for breakdown</button>
				  </div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-primary">
				  <div class="panel-heading">
				    Gender <div class="display_date"></div>
				  </div>
				  <div class="panel-body" id="gender">
				    <div>Loading...</div>
				  </div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<div class="row">
	<!-- Map of the country -->
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		  	County Outcomes <div class="display_date"></div>
		  </div>
		  <div class="panel-body" id="county">
		    <div>Loading...</div>
		  </div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="agemodal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Age Category Breakdown</h4>
      </div>
      <div class="modal-body" id="CatAge">
        <p>Loading...</p>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('summary_view_footer'); ?>