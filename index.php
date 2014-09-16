<?php
session_start();
$tpl_north='north.html';
$tpl_west='west.html';
$tpl_center='center.html';

function debug($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}
class ArrayFilter
{
	var $array;
	var $result;
	
	var $prefix = ':';
	
	function ArrayFilter($array)
	{
		$this->array = $array;
	}
	
	function filter($where_expr)
	{
		if(func_num_args()>1)
		{
			$values = array_merge(array($where_expr),array_slice(func_get_args(),1));
			$where_expr = call_user_func_array('sprintf',$values);
		}
		
		$this->result = array();
		
		foreach($this->array as $item)
		{
			$pattern = '/'.$this->prefix.'([\w]+)/';
			$replace = '$item["\\1"]';
			
			$php_expr = preg_replace($pattern,$replace,$where_expr);
						
			if(eval('return '.$php_expr.';'))
			{
				$this->result[] = $item;
			}
		}
		return $this->result;
	}

}

// muck de consulta no DB
if(!isset($_SESSION['resultadoDB']))
$_SESSION['resultadoDB']=array(
	array('id'=>'1','data'=>'15-09-2014','horario'=>'8','status'=>'reservado'),
	array('id'=>'2','data'=>'15-09-2014','horario'=>'9','status'=>'reservado'),
	array('id'=>'3','data'=>'15-09-2014','horario'=>'11','status'=>'reservado'),
	array('id'=>'4','data'=>'15-09-2014','horario'=>'20','status'=>'reservado'),
	array('id'=>'5','data'=>'15-09-2014','horario'=>'21','status'=>'reservado'),
	array('id'=>'6','data'=>'15-09-2014','horario'=>'22','status'=>'reservado'),
	array('id'=>'7','data'=>'16-09-2014','horario'=>'19','status'=>'reservado'),
	array('id'=>'8','data'=>'16-09-2014','horario'=>'20','status'=>'reservado'),
	array('id'=>'9','data'=>'16-09-2014','horario'=>'21','status'=>'reservado'),
	array('id'=>'10','data'=>'16-09-2014','horario'=>'22','status'=>'reservado'),
	array('id'=>'10','data'=>'17-09-2014','horario'=>'8','status'=>'reservado'),
	array('id'=>'12','data'=>'17-09-2014','horario'=>'9','status'=>'reservado'),
	array('id'=>'13','data'=>'17-09-2014','horario'=>'10','status'=>'reservado'),
	array('id'=>'14','data'=>'17-09-2014','horario'=>'22','status'=>'reservado'),
	array('id'=>'15','data'=>'17-09-2014','horario'=>'23','status'=>'reservado'),
	array('id'=>'16','data'=>'17-09-2014','horario'=>'24','status'=>'reservado')
);
$_SESSION['date']=isset($_GET['date'])?$_GET['date']:date('d-m-Y');
$_SESSION['aba']=isset($_GET['aba'])?$_GET['aba']:'1';
isset($_GET['horario'])?$_SESSION['resultadoDB'][]=array('id'=>count($_SESSION['resultadoDB'])+1,'data'=>$_SESSION['date'],'horario'=>$_GET['horario'],'status'=>'reservado'):null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agenda de reservas</title>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.layout-latest.js"></script>
<script type="text/javascript" src="http://layout.jquery-dev.com/lib/js/jquery.layout.resizePaneAccordions-latest.js"></script>
<script type="text/javascript" src="js/jquery.zclip.min.js"></script>
<script>
	var _myDate;
	pageLayout={}, 
	pageLayout_settings = { }, 
	pageLayout_defaults = { 
		cookie: { 
			 name:	'AppLayout', 
			 keys:	'north.size,south.size,east.size,west.size,'+ 
					'north.isClosed,south.isClosed,east.isClosed,west.isClosed,'+ 
					'north.isHidden,south.isHidden,east.isHidden,west.isHidden' 
		}, 
		useStateCookie:		true, 
		applyDefaultStyles:	false, 
		onresize:			function(){
			$( '#accordion' ).accordion( 'refresh' );
		},
		initClosed:			false, 
		fxName:				'slide', 
		fxSpeed:			'slow',
		north: {
			initClosed:		false, 
			slidable:		true, 
			resizable:		false, 
			minSize:		50, 
			maxSize:		50
		},
		west: {
			initClosed:		false, 
			slideble:		true, 
			resizable:		true,
			minSize:		265,
			maxSize:		400
		},
		east: {
			initClosed:		false
		},
		south: {
			initClosed:		true,
			minSize:		30, 
			maxSize:		50
		}
	}; 

	$(document).on('ready',function() { 
		pageLayout = $('body').layout( 
			jQuery.extend( pageLayout_settings, pageLayout_defaults )
		);
		$( '#accordion' ).accordion({
			heightStyle:	"fill",
			active:			<?php echo $_SESSION['aba'];?>
		});

		_myDate={}, 
		_myDate_settings = { }, 
		_myDate_defaults = { 
			autoSize:			true,
			showOtherMonths:	true,
			monthNames:			[ "janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro" ],
			monthNamesShort:	[ "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez" ],
			dayNames:			[ "domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sabado" ],
			dayNamesMin:		[ "D", "S", "T", "Q", "Q", "S", "S" ],
			dayNamesShort:		[ "Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab" ],
			nextText:			"Próximo",
			prevText:			"Anterior",
			currentText:		" mês atual ",
			minDate:			0,
			showButtonPanel:	true,
			firstDay:			1,
			dateFormat:			"dd-mm-yy",
			defaultDate: 		"<?php echo $_GET['date'];?>",
			onSelect: 			function(dateText) {
									_myDate_selected=dateText;
									console.log("Selected date: " + dateText + "; input's current value: " + this.value);
									$(this).change();
								}
		}
		
		_myDate=$( "#datepicker" ).datepicker(
			jQuery.extend( _myDate_settings, _myDate_defaults )
		)
		.change(function() {
			window.location.href = "index.php?aba="+$( "#accordion" ).accordion( "option", "active" )+"&date="+this.value;
		});
	});
</script>

<link rel="stylesheet" href="css/layout-default-latest.css" type="text/css">
<link rel="stylesheet" href="jquery-ui-1.11.1.custom/jquery-ui.theme.css" type="text/css">
<!--link href="css/normalize.css" rel="stylesheet" type="text/css"/-->
<style type="text/css">
	
	* {margin:0; border:0;padding:0;z-index:0;}
	table, tr{width:100%;}
	.horario{width:20%;}
	.acao{width:80%;}
	.header{background:#aaf;}
	.dark{background:#aaa;}
	.light{background:#eee;}
	#north{}
	#west{overflow:hidden;}
	#center{}
	#accordion{margin:0px;overflow:hidden;}
	.ui-accordion-header{font-size: small;overflow:hidden;padding:5px;}
	.editor{font-size: normal;}
	
	.ui-layout-west{background: url('images/darkdenim3.png') repeat 0 0 #555;}
	
.ui-datepicker {  
    width: 246px;
    height: auto;  
    margin: 5px auto 0;  
    font: 9pt Arial, sans-serif;  
    -webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);  
    -moz-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);  
    box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);  
} 	
.ui-datepicker a {  
    text-decoration: none;  
}
.ui-datepicker table {  
    width: 100%;  
}

.ui-widget-content{
	background: transparent;
}

.ui-datepicker-header {  
    background: #756FEB url("jquery-ui-1.11.1.custom/images/ui-bg_gloss-wave_65_756fee_500x100.png") 50% 50% repeat-x;
    color: #e0e0e0;  
    font-weight: bold;  
    -webkit-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, 2);  
    -moz-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);  
    box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);  
    text-shadow: 1px -1px 0px #000;  
    filter: dropshadow(color=#000, offx=1, offy=-1);  
    line-height: 35px;  
    border-width: 1px 0 0 0;  
    border-style: solid;  
    border-color: #111;  
}
.ui-datepicker-title {  
    text-align: center;  
}
.ui-datepicker-prev, .ui-datepicker-next {  
    display: inline-block;  
    width: 30px;  
    height: 30px;  
    text-align: center;  
    cursor: pointer;  
    background-image: url('images/arrow.png');  
    background-repeat: no-repeat;  
    line-height: 600%;  
    overflow: hidden;  
}
.ui-datepicker-prev {  
    float: left;  
    background-position: center -30px;  
}  
.ui-datepicker-next {  
    float: right;  
    background-position: center 0px;  
}
.ui-datepicker thead {  
    background-color: #f7f7f7;  
    background-image: -moz-linear-gradient(top,  #f7f7f7 0%, #f1f1f1 100%);  
    background-image: -webkit-gradient(linear, left top, left bottombottom, color-stop(0%,#f7f7f7), color-stop(100%,#f1f1f1));  
    background-image: -webkit-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);  
    background-image: -o-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);  
    background-image: -ms-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);  
    background-image: linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);  
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7f7f7', endColorstr='#f1f1f1',GradientType=0 );  
    border-bottom: 1px solid #bbb;  
}
.ui-datepicker th {  
    text-transform: uppercase;  
    font-size: 6pt;  
    padding: 5px 0;  
    color: #666666;  
    text-shadow: 1px 0px 0px #fff;  
    filter: dropshadow(color=#fff, offx=1, offy=0);  
}
.ui-datepicker tbody td {  
    padding: 0;  
    border-right: 1px solid #bbb;  
}
.ui-datepicker tbody td:last-child {  
    border-right: 0px;  
}
.ui-datepicker tbody tr {  
    border-bottom: 1px solid #bbb;  
}  
.ui-datepicker tbody tr:last-child {  
    border-bottom: 0px;  
}
.ui-datepicker td span, .ui-datepicker td a {  
    display: inline-block;  
    font-weight: bold;  
    text-align: center;  
    width: 30px;  
    height: 30px;  
    line-height: 30px;  
    color: #666666;  
    text-shadow: 1px 1px 0px #fff;  
    filter: dropshadow(color=#fff, offx=1, offy=1);  
}  
.ui-datepicker-calendar .ui-state-default {  
    background: #ededed;  
    background: -moz-linear-gradient(top,  #ededed 0%, #dedede 100%);  
    background: -webkit-gradient(linear, left top, left bottombottom, color-stop(0%,#ededed), color-stop(100%,#dedede));  
    background: -webkit-linear-gradient(top,  #ededed 0%,#dedede 100%);  
    background: -o-linear-gradient(top,  #ededed 0%,#dedede 100%);  
    background: -ms-linear-gradient(top,  #ededed 0%,#dedede 100%);  
    background: linear-gradient(top,  #ededed 0%,#dedede 100%);  
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#dedede',GradientType=0 );  
    -webkit-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);  
    -moz-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);  
    box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);  
}  
.ui-datepicker-unselectable .ui-state-default {  
    background: #f4f4f4;  
    color: #b4b3b3;  
}
.ui-datepicker-calendar .ui-state-hover {  
    background: #f7f7f7;
}
.ui-datepicker-calendar .ui-state-active {  
    background: #6eafbf;  
    -webkit-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);  
    -moz-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);  
    box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);  
    color: #e0e0e0;  
    text-shadow: 0px 1px 0px #4d7a85;  
    filter: dropshadow(color=#4d7a85, offx=0, offy=1);  
    border: 1px solid #55838f;  
    position: relative;  
    margin: -1px;  
}
.ui-datepicker-calendar td:first-child .ui-state-active {  
    width: 29px;  
    margin-left: 0;  
}  
.ui-datepicker-calendar td:last-child .ui-state-active {  
    width: 29px;  
    margin-right: 0;  
}
.ui-datepicker-calendar tr:last-child .ui-state-active {  
    height: 29px;  
    margin-bottom: 0;  
}

.ui-datepicker-buttonpane{
    padding: 5px 5px;  
}
</style>
</head>

<body>
	<div class="ui-layout-north" id="north"></div>
	<div class="ui-layout-center" id="center">
		<table whidth=*>
			<tr>
				<th class="horario dark" whidth="200px">Horário</th>
				<th class="acao dark" whidth=*>Ações</th>
			</tr>
			<?php
			for($i=1;$i<=24;$i++){
				$horario=new ArrayFilter($_SESSION['resultadoDB']);
				$horario->filter(':horario=="'.$i.'" AND :data=="'.$_SESSION['date'].'"');
				$resultadoHora=($horario->result);
				$td_class=($i%2)==0?'dark':'light';
// 				debug($resultadoHora);
			?>
			<tr>
				<td class="<?php echo $td_class;?>"><?php echo $i.'h';?></td>
				<td class="<?php echo $td_class;?>" align="center"><?php echo count($resultadoHora)>0?$resultadoHora[0]['status']:'<a href="?aba='.$_SESSION['aba'].'&date='.$_SESSION['date'].'&horario='.$i.'"><div style="background:url(\'icones.gif\');width:33px;height:33px;"> </div></a>';?></td>
			</tr>
			<?php
			}
//			debug($_SESSION['resultadoDB']);
			?>
		</table>
	</div>
	<div class="ui-layout-west" id="west">
		<div id="accordion">
			<h3 class="ui-accordion-header">Info</h3>
			<div class="editor">
			</div>
			<h3 class="ui-accordion-header">Data</h3>
			<div class="editor hasDatepicker">
				<div id="datepicker"></div>
			</div>
			<h3 class="ui-accordion-header">Produtos</h3>
			<div class="editor" id="catalog">teste 03</div>
		</div>
	</div>
	<div class="ui-layout-south" id="south"></div>
</body>
</html>
