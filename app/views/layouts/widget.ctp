<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style type="text/css">
		html{font-family: "Open Sans", Arial, sans-serif;}
		b{font-weight:700;}
		small{font-size:80%;}
		table{border-spacing:0;border-collapse:collapse;}
		td{padding:0;}
		@media print{
		*,:after,:before{color:#000!important;text-shadow:none!important;background:0 0!important;-webkit-box-shadow:none!important;box-shadow:none!important;}
		thead{display:table-header-group;}
		tr{page-break-inside:avoid;}
		p{orphans:3;widows:3;}
		.label{border:1px solid #000;}
		.table{border-collapse:collapse!important;}
		.table td{background-color:#fff!important;}
		}
		*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
		:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
		p{margin:0 0 10px;}
		small{font-size:85%;}
		table{background-color:transparent;}
		.table{width:100%;max-width:100%;margin-bottom:20px;}
		.table>tbody>tr>td,.table>thead>tr>td{padding:8px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd;}
		.table>thead:first-child>tr:first-child>td{border-top:0;}
		.table-condensed>tbody>tr>td,.table-condensed>thead>tr>td{padding:5px;}
		.table-striped>tbody>tr:nth-of-type(odd){background-color:#f9f9f9;}
		.table-responsive{min-height:.01%;overflow-x:auto;}
		@media screen and (max-width:767px){
		.table-responsive{width:100%;margin-bottom:15px;overflow-y:hidden;-ms-overflow-style:-ms-autohiding-scrollbar;border:1px solid #ddd;}
		.table-responsive>.table{margin-bottom:0;}
		.table-responsive>.table>tbody>tr>td,.table-responsive>.table>thead>tr>td{white-space:nowrap;}
		}
		label{display:inline-block;max-width:100%;margin-bottom:5px;font-weight:700;}
		.label{display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em;}
		.label:empty{display:none;}
		.label-success{background-color:#5cb85c;}

		p{color:#777;line-height:24px;margin:0 0 20px;}
		.label-success{background-color:#47a447;color:#FFF;}
		.label.label-sm{font-size:0.7em;}
		.label{font-weight:normal;}

		.clear{clear:both;}

		@media screen and (max-width: 991px){
		.table-responsive{width:100%;margin-bottom:15px;overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;-ms-overflow-style:-ms-autohiding-scrollbar;border:1px solid #ddd;}
		.table-responsive > .table{margin-bottom:0;}
		.table-responsive > .table > thead > tr > td,.table-responsive > .table > tbody > tr > td{white-space:nowrap;}
		}
		.table{width:100%;}
		.text-center{text-align: center;}

		.belize-hole-flat-button{
			display: inline-block;
			position: relative;
			vertical-align: top;
			padding: 10px 15px;
			text-decoration: none;
			font-size: 22px;
			color: white;
			text-align: center;
			text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
			background: #2980b9;
			border: 0;
			border-bottom: 2px solid #2475ab;
			cursor: pointer;
			-webkit-box-shadow: inset 0 -2px #2475ab;
			box-shadow: inset 0 -2px #2475ab;
		}
		.belize-hole-flat-button:active{
		  top: 1px;
		  outline: none;
		  -webkit-box-shadow: none;
		  box-shadow: none;
		}
	</style>
</head>
<body>
	<?=$content_for_layout;?>
</body>
</html>