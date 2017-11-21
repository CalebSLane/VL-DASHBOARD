<?php
(defined('BASEPATH') or exit('No direct script access allowed!'));
/**
* 
*/
class Pmtct_model extends MY_Model
{
	
	function __construct()
	{
		parent:: __construct();
	}

	public function pmtct_outcomes($year=null,$month=null,$to_year=null,$to_month=null,$partner=null,$national=null,$county=null,$subcounty=null,$site=null)
	{
		$default = 0;
		if ($year==null || $year=='null') {
			$year = $this->session->userdata('filter_year');
		}
		if ($to_month==null || $to_month=='null') {
			$to_month = 0;
		}
		if ($to_year==null || $to_year=='null') {
			$to_year = 0;
		}
		if ($month==null || $month=='null') {
			if ($this->session->userdata('filter_month')==null || $this->session->userdata('filter_month')=='null') {
				$month = 0;
			}else {
				$month = $this->session->userdata('filter_month');
			}
		}

		if ($partner==null || $partner=='null') {
			$partner = $this->session->userdata('partner_filter');
		}
		if ($national==null || $national=='null') {
			$national = $default;
		}
		if ($county==null || $county=='null') {
			$county = $this->session->userdata('county_filter');
		}
		if ($subcounty==null || $subcounty=='null') {
			$subcounty = $this->session->userdata('sub_county_filter');
		}
		if ($site==null || $site=='null') {
			$site = $this->session->userdata('site_filter');
		}
		$sql = "CALL `proc_get_vl_pmtct`('".$default."','".$year."','".$month."','".$to_year."','".$to_month."','".$national."','".$county."','".$partner."','".$subcounty."','".$site."')";

		$result = $this->db->query($sql)->result();

		$count = 0;
		$name = '';
		
		// echo "<pre>";print_r($result);die();
		$data['pmtct'][0]['name'] = 'Not Suppresed';
		$data['pmtct'][1]['name'] = 'Suppresed';
 
		$data["pmtct"][0]["data"][0]	= $count;
		$data["pmtct"][1]["data"][0]	= $count;
		$data['categories'][0]			= 'No Data';
 
		foreach ($result as $key => $value) {
			$data['categories'][$key] 		= $value->pmtcttype;
			$data["pmtct"][0]["data"][$key]	=  (int) ($value->less5000+$value->above5000);
			$data["pmtct"][1]["data"][$key]	=  (int) ($value->undetected+$value->less1000);
		}
		// die();
		$data['pmtct'][0]['drilldown']['color'] = '#913D88';
		$data['pmtct'][1]['drilldown']['color'] = '#96281B';
 
		// echo "<pre>";print_r($data);die();
		// $data['categories'] = array_values($data['categories']);
		// $data["pmtct"][0]["data"] = array_values($data["pmtct"][0]["data"]);
		// $data["pmtct"][1]["data"] = array_values($data["pmtct"][1]["data"]);
		// echo "<pre>";print_r($data);die();
		return $data;
	}

	public function suppression($year=null,$month=null,$pmtcttype=null,$to_year=null,$to_month=null,$partner=null,$national=null,$county=null,$subcounty=null,$site=null)
	{
		$default = 0;
		if ($year==null || $year=='null') {
			$year = $this->session->userdata('filter_year');
		}
		if ($to_month==null || $to_month=='null') {
			$to_month = 0;
		}
		if ($to_year==null || $to_year=='null') {
			$to_year = $year;
			$year -= 1;
		}
		if ($month==null || $month=='null') {
			if ($this->session->userdata('filter_month')==null || $this->session->userdata('filter_month')=='null') {
				$month = 0;
			}else {
				$month = $this->session->userdata('filter_month');
			}
		}
		if ($pmtcttype==null || $pmtcttype=='null') {
			$pmtcttype = $this->session->userdata('pmtct_filter');
		}

		if ($partner==null || $partner=='null') {
			$partner = $this->session->userdata('partner_filter');
		}
		if ($national==null || $national=='null') {
			$national = $default;
		}
		if ($county==null || $county=='null') {
			$county = $this->session->userdata('county_filter');
		}
		if ($subcounty==null || $subcounty=='null') {
			$subcounty = $this->session->userdata('sub_county_filter');
		}
		if ($site==null || $site=='null') {
			$site = $this->session->userdata('site_filter');
		}
		$sql = "CALL `proc_get_vl_pmtct_suppression`('".$pmtcttype."','".$year."','".$default."','".$to_year."','".$default."','".$national."','".$county."','".$partner."','".$subcounty."','".$site."')";

		$result = $this->db->query($sql)->result();
		// echo "<pre>";print_r($result);die();
		$data['outcomes'][0]['name'] = "Not Suppressed";
		$data['outcomes'][1]['name'] = "Suppressed";
		$data['outcomes'][2]['name'] = "Suppression";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";
		

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['title'] = "";
 		
 		$data['categories'][0] 		= 'No Data';
		$data['outcomes'][0]['data'][0] = 0;
		$data['outcomes'][1]['data'][0] = 0;
		$data['outcomes'][2]['data'][0] = 0;
		
		foreach ($result as $key => $value) {
			$data['categories'][$key] 		   = $this->resolve_month($value->month)." - ".$value->year;
			$data['outcomes'][0]['data'][$key] = (int) $value->nonsuppressed;
			$data['outcomes'][1]['data'][$key] = (int) $value->suppressed;
			$data['outcomes'][2]['data'][$key] = round(@$value->suppression,1);
		}
		// echo "<pre>";print_r($data);die();
		return $data;
	}
	public function vl_outcomes($year=null,$month=null,$pmtcttype=null,$to_year=null,$to_month=null,$partner=null,$national=null,$county=null,$subcounty=null,$site=null)
	{
		$default = 0;
		if ($year==null || $year=='null') {
			$year = $this->session->userdata('filter_year');
		}
		if ($to_month==null || $to_month=='null') {
			$to_month = 0;
		}
		if ($to_year==null || $to_year=='null') {
			$to_year = 0;
		}
		if ($month==null || $month=='null') {
			if ($this->session->userdata('filter_month')==null || $this->session->userdata('filter_month')=='null') {
				$month = 0;
			}else {
				$month = $this->session->userdata('filter_month');
			}
		}
		if ($pmtcttype==null || $pmtcttype=='null') {
			$pmtcttype = $this->session->userdata('pmtct_filter');
		}

		if ($partner==null || $partner=='null') {
			$partner = $this->session->userdata('partner_filter');
		}
		if ($national==null || $national=='null') {
			$national = $default;
		}
		if ($county==null || $county=='null') {
			$county = $this->session->userdata('county_filter');
		}
		if ($subcounty==null || $subcounty=='null') {
			$subcounty = $this->session->userdata('sub_county_filter');
		}
		if ($site==null || $site=='null') {
			$site = $this->session->userdata('site_filter');
		}
		$sql = "CALL `proc_get_vl_pmtct`('".$pmtcttype."','".$year."','".$month."','".$to_year."','".$to_month."','".$national."','".$county."','".$partner."','".$subcounty."','".$site."')";

		$result = $this->db->query($sql)->result();

		$color = array('#6BB9F0', '#F2784B', '#1BA39C', '#5C97BF');
 
		$data['vl_outcomes']['name'] = 'Tests';
		$data['vl_outcomes']['colorByPoint'] = true;
		$data['ul'] = '';
 
		$data['vl_outcomes']['data'][0]['name'] = 'Suppresed';
		$data['vl_outcomes']['data'][1]['name'] = 'Not Suppresed';
 
		$count = 0;
 
		$data['vl_outcomes']['data'][0]['y'] = $count;
		$data['vl_outcomes']['data'][1]['y'] = $count;
 
		foreach ($result as $key => $value) {
			$total = (int) ($value->undetected+$value->less1000+$value->less5000+$value->above5000);
			$less = (int) ($value->undetected+$value->less1000);
			$greater = (int) ($value->less5000+$value->above5000);
			$non_suppressed = $greater + (int) $value->confirm2vl;
			$total_tests = (int) $value->confirmtx + $total + (int) $value->baseline;
			
			$data['ul'] .= '
			<tr>
	    		<td>Total VL tests done:</td>
	    		<td>'.number_format($total_tests ).'</td>
	    		<td>Non Suppression</td>
	    		<td>'. number_format($non_suppressed) . ' (' . round((($non_suppressed / $total_tests  )*100),1).'%)</td>
	    	</tr>
 
			<tr>
	    		<td>&nbsp;&nbsp;&nbsp;Routine VL Tests with Valid Outcomes:</td>
	    		<td>'.number_format($total).'</td>
	    		<td colspan="2"></td>
	    	</tr>
 
	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valid Tests &gt; 1000 copies/ml:</td>
	    		<td>'.number_format($greater).'</td>
	    		<td>Percentage Non Suppression</td>
	    		<td>'.round((($greater/$total)*100),1).'%</td>
	    	</tr>
 
	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valid Tests &lt; 1000 copies/ml:</td>
	    		<td>'.number_format($less).'</td>
	    		<td>Percentage Suppression</td>
	    		<td>'.round((($less/$total)*100),1).'%</td>
	    	</tr>
 
	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;Baseline VLs:</td>
	    		<td>'.number_format($value->baseline).'</td>
	    		<td>Non Suppression ( &gt; 1000cpml)</td>
	    		<td>'.number_format($value->baselinesustxfail). ' (' .round(@($value->baselinesustxfail * 100 / $value->baseline), 1). '%)' .'</td>
	    	</tr>
	    	<tr>
	    		<td>&nbsp;&nbsp;&nbsp;Confirmatory Repeat Tests:</td>
	    		<td>'.number_format($value->confirmtx).'</td>
	    		<td>Non Suppression ( &gt; 1000cpml)</td>
	    		<td>'.number_format($value->confirm2vl). ' (' .round(@($value->confirm2vl * 100 / $value->confirmtx), 1). '%)' .'</td>
	    	</tr>
 
	    	<tr>
	    		<td>Rejected Samples:</td>
	    		<td>'.number_format($value->rejected).'</td>
	    		<td>Percentage Rejection Rate</td>
	    		<td>'. round(@(($value->rejected*100)/$total_tests), 1, PHP_ROUND_HALF_UP).'%</td>
	    	</tr>';
						
			$data['vl_outcomes']['data'][0]['y'] = (int) $value->undetected+(int) $value->less1000;
			$data['vl_outcomes']['data'][1]['y'] = (int) $value->less5000+(int) $value->above5000;
 
			$data['vl_outcomes']['data'][0]['color'] = '#1BA39C';
			$data['vl_outcomes']['data'][1]['color'] = '#F2784B';
		}

		return $data;
	}

	public function breakdown($year=null,$month=null,$pmtcttype=null,$to_year=null,$to_month=null,$county=null,$sub_county=null,$partner=null,$site=null)
	{
		$default = 0;
		$li = '';
		$table = '';
		if ($year==null || $year=='null') {
			$year = $this->session->userdata('filter_year');
		}
		if ($to_month==null || $to_month=='null') {
			$to_month = 0;
		}
		if ($to_year==null || $to_year=='null') {
			$to_year = 0;
		}
		//Assigning the value of the month or setting it to the selected value
		if ($month==null || $month=='null') {
			if ($this->session->userdata('filter_month')==null || $this->session->userdata('filter_month')=='null') {
				$month = 0;
			}else {
				$month = $this->session->userdata('filter_month');
			}
		}

		if ($pmtcttype==null || $pmtcttype=='null') {
			$pmtcttype = $this->session->userdata('pmtct_filter');
		}

		if ($county == 1 || $county == '1') {
			$sql = "CALL `proc_get_vl_pmtct_breakdown`('".$pmtcttype."','".$year."','".$month."','".$to_year."','".$to_month."','".$county."','".$default."','".$default."','".$default."')";
			$div_name = 'countyLising';
			$modal_name = 'countyModal';
		} elseif ($partner == 1 || $partner == '1') {
			$sql = "CALL `proc_get_vl_pmtct_breakdown`('".$pmtcttype."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$default."','".$partner."','".$default."')";
			$div_name = 'partnerLising';
			$modal_name = 'partnerModal';
		} elseif ($sub_county == 1 || $sub_county == '1') {
			$sql = "CALL `proc_get_vl_pmtct_breakdown`('".$pmtcttype."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$sub_county."','".$default."','".$default."')";
			$div_name = 'subcountyLising';
			$modal_name = 'subcountyModal';
		} elseif ($site == 1 || $site == '1') {
			$sql = "CALL `proc_get_vl_pmtct_breakdown`('".$pmtcttype."','".$year."','".$month."','".$to_year."','".$to_month."','".$default."','".$default."','".$default."','".$site."')";
			$div_name = 'siteLising';
			$modal_name = 'siteModal';

		}
		

		$result = $this->db->query($sql)->result_array();
		// echo "<pre>";print_r($result);die();
		$count = 1;

		if($result)
		{
			foreach ($result as $key => $value)
			{
				$suppressed = $value['undetected']+$value['less1000'];
				$nonsuppressed = $value['less5000']+$value['above5000'];
				if ($count<16) {
					$li .= '<a href="javascript:void(0);" class="list-group-item" ><strong>'.$count.'.</strong>&nbsp;'.$value['name'].':&nbsp;&nbsp;&nbsp;'.round(@($suppressed/$value['routine']),1).'%&nbsp;&nbsp;&nbsp;('.number_format($value['routine']).')</a>';
				}
					$table .= '<tr>';
					$table .= '<td>'.$count.'</td>';
					$table .= '<td>'.$value['name'].'</td>';
					$table .= '<td>'.number_format((int) $value['routine']).'</td>';
					$table .= '<td>'.number_format((int) $suppressed).'</td>';
					$table .= '<td>'.number_format((int) $nonsuppressed).'</td>';
					$table .= '<td>'.round(@($suppressed/$value['routine']),1).'%</td>';
					$table .= '</tr>';
					$count++;
			}
		}else{
			$li = 'No Data';
		}
		
		$data = array(
						'ul' => $li,
						'table' => $table,
						'div_name' => $div_name,
						'modal_name' => $modal_name);
		return $data;
	}
	public function pmtct($year=null,$month=null,$pmtcttype=null,$to_year=null,$to_month=null,$county=null,$sub_county=null,$partner=null)
	{
		if ($year==null || $year=='null') {
			$year = $this->session->userdata('filter_year');
		}
		if ($to_month==null || $to_month=='null') {
			$to_month = 0;
		}
		if ($to_year==null || $to_year=='null') {
			$to_year = 0;
		}
		//Assigning the value of the month or setting it to the selected value
		if ($month==null || $month=='null') {
			if ($this->session->userdata('filter_month')==null || $this->session->userdata('filter_month')=='null') {
				$month = 0;
			}else {
				$month = $this->session->userdata('filter_month');
			}
		}
		if ($county==null || $county=='null') {
			$county = 0;
		}
		if ($sub_county==null || $sub_county=='null') {
			$sub_county = 0;
		}
		if ($partner==null || $partner=='null') {
			$partner = 0;
		}

		if ($pmtcttype==null || $pmtcttype=='null') {
			$pmtcttype = $this->session->userdata('pmtct_filter');
			if ($pmtcttype == null) {
				$pmtcttype = 0;
			}
		}
						
		$default = 0;	
		$sql = "CALL `proc_get_vl_pmtct_grouped`('".$pmtcttype."','".$year."','".$month."','".$to_year."','".$to_month."','".$county."','".$sub_county."','".$partner."')";
		// echo "<pre>";print_r($sql);echo "</pre>";die();
		$result = $this->db->query($sql)->result_array();
		// echo "<pre>";print_r(sizeof($result));die();

		$data['outcomes'][0]['name'] = "Not Suppressed";
		$data['outcomes'][1]['name'] = "Suppressed";
		$data['outcomes'][2]['name'] = "Suppression";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";
		

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['title'] = "";

		$data['categories'][0] 		= 'No Data';
		$data['outcomes'][0]['data'][0] = 0;
		$data['outcomes'][1]['data'][0] = 0;
		$data['outcomes'][2]['data'][0] = 0;
 		
 		$looop = 0;

		foreach ($result as $key => $value) {
			if ($looop < 100){
	 			$suppressed = (int) $value['undetected']+(int) $value['less1000'];
	            $nonsuppressed = (int) $value['less5000']+(int) $value['above5000'];
				$data['categories'][$key] 		  = $value['name'];
				$data['outcomes'][0]['data'][$key] = (int) $nonsuppressed;
				$data['outcomes'][1]['data'][$key] = (int) $suppressed;
				$data['outcomes'][2]['data'][$key] = round(@(((int) $suppressed*100)/((int) $suppressed+(int) $nonsuppressed)),1);
	 		}
	 		$looop ++;
		}
		// echo "<pre>";print_r($data);die();
		return $data;
	}
}
?>