<?php
class mailling_generator{
	private $output,$output_source,$output_template,$template,$source,$DB_HOST,$DB_USER,$DB_PASS,$DB_BASE;
	// Bloco dos construtores mágicos
	public function __construct($arr=null){
		if(is_array($arr)){
			foreach($arr as $key=>$value){
				$$key=$value;
			}
		}
		$this->output=$output;
		$this->output_source=$source;
		$this->output_template=$template;
		$this->template=$template;
		$this->source=$source;
	}
	public function __toString(){
		if(isset($this->output))
			return '1';
		else
			return '0';
	}

	// Bloco dos sets
	
	// Configura a saída de impressao na tela
	public function set_output($str){
		$this->output=$str;
		return true;
	}
	// configura o arquivo fonte para gerar a saida em html
	public function set_source($s=null){
		if(isset($s)&&(!is_null($s)))
			$this->source=$s;
		if($this->set_output_file())
			return $this->output_source;
		else
			return 'Arquivo não existe';
	}
	// configura o arquivo template para gerar a saida em html
	public function set_template($s=null){
		if(isset($s)&&(!is_null($s)))
			$this->template=$s;
		if($this->set_output_template())
			return $this->output_template;
		else
			return 'Arquivo não existe';
	}

	// 
	private function set_output_file(){
		if(file_exists($this->source)){
			$this->output_source = file_get_contents($this->source); //read the file
			return true;
		}else{
			return false;
		}
	}
	private function set_output_template(){
		if(file_exists($this->template)){
			$this->output_template = file_get_contents($this->template); //read the file
			return true;
		}else{
			return false;
		}
	}

	// Bloco dos gets
	
	public function get_output($opt=null){
		if(isset($this->$opt)){
			$opt='output_'.$opt;
			$this->output=$this->$opt;
			return $this->output;
		}else{
			echo ' A saida não foi definida.';
			return false;
		}
	}
	public function mk_input_menu($type,$name,$value,$id,$content,$checked){
		$new_tag='<div class="edit_menus"><input type="'.$type.'" name="'.$name.'" value="'.$value.'" class="ui-helper-hidden-accessible" id="'.$id.'"'.($checked?' checked':null).' /><label for="'.$id.'">'.$content.'</label></div>';
		return $new_tag;
	}

	public function key_replace($arr=null,$source=null){
		if(!$source==null){
			$this->output_template=$source;
		}
		if(is_array($arr)){
			foreach($arr as $value){
				$this->output_template=str_replace($value[0],$value[1],$this->output_template);
			}
			return $this->output_template; 
		}
	}
	// arrey contendo arrays com name, type, value 
	public function mk_form($arr,$action=null){
		if(is_array($arr)){
			$tmp_input='';
			foreach($arr as $value){
				switch($value['type']){
					case 'hidden':
						$tmp_input.='<input type="hidden" name="chk_'.$value['name'].'" value="true" />';
						break;
					case 'text':
						$tmp_input.='<input type="text" id="'.$value['name'].'" name="'.$value['name'].'" value="'.$value['value'].'" />';
						break;
					case 'submit':
						$tmp_input.='<input type="submit" id="'.$value['name'].'" name="'.$value['name'].'" value="'.$value['value'].'" />';
						break;
					case 'label':
						$tmp_input.='<label for="'.$value['name'].'">'.$value['value'].'</label>';
						break;
				}
			}
			$tmp_form='<form action="#'.$action.'" method="post">'.$tmp_input.'</form>';
			return $tmp_form;
		}else{
			return false;
		}
	}
}
?>
