<?php
// Criação da classe .
class DBCon {

	// Declaração das variaveis com dados do DB (set null para o constructor)
	private $host; // Nome ou IP do Servidor
	private $user; // Usuário do Servidor MySQL
	private $pass; // Senha do Usuário MySQL
	private $base; // Nome do seu Banco de Dados

	// Criaremos as variáveis que Utilizaremos no script
//	public $query;
	public $link;
	public $resultado;
	public $fetch;

	public function set($h=null,$u=null,$p=null,$b=null){
		if (isset($h)&&isset($u)&&isset($p)&&isset($b)){
			$this->host=$h;
			$this->user=$u;
			$this->pass=$p;
			$this->base=$b;
		} else {
			echo $host, $user, $pass, $base;
		}
	}


	public function debug($bug){
		echo "<pre>";
		print_r ($bug);
		echo "</pre>";
	}

	public function show($show){
		echo $show;
	}

	// Instancia o Objeto para podermos usar
	function MySQL(){

	}


	// Cria a função para efetuar conexão ao Banco MySQL (não é muito diferente da conexão padrão).
	// Veja que abaixo, além de criarmos a conexão, geramos condições personalizadas para mensagens de erro.
	public function conecta(){
		$this->link = @mysql_connect($this->host,$this->user,$this->pass);
		// Conecta ao Banco de Dados
		if(!$this->link){
			// Caso ocorra um erro, exibe uma mensagem com o erro
			print "Ocorreu um Erro na conexão MySQL:";
			print "<b>".mysql_error()."</b>";
			die();
		}elseif(!mysql_select_db($this->base,$this->link)){
			// Seleciona o banco após a conexão
			// Caso ocorra um erro, exibe uma mensagem com o erro
			print "Ocorreu um Erro em selecionar o Banco:";
			print "<b>".mysql_error()."</b>";
			die();
		}
	}

	// Cria a função para "query" no Banco de Dados
	public function sql_query($query,$f=true){
		$this->conecta();
		//    $this->query = $query;

		// Conecta e faz a query no MySQL
		if($this->fetch = mysql_query($query)){
			if ($f) {
			  	while($m = mysql_fetch_assoc($this->fetch)){
			  		$this->resultado[] = $m;
			  	}
				$this->desconecta();
				return $this->resultado;
			} else {
				return mysql_affected_rows();
			}
		}else{
			// Caso ocorra um erro, exibe uma mensagem com o Erro
			print "Ocorreu um erro ao executar a Query MySQL: <b>$query</b>";
			print "<br><br>";
			print "Erro no MySQL: <b>".mysql_error()."</b>";
			die();
		}        
	}

	// Cria a função para "query" no Banco de Dados
	public function fetch_object(){
		$m = mysql_fetch_object($this->fetch);
		return $m;
	}
	public function clear_result(){
		unset($this->resultado);
		return true;
	}


	public function last_id() {
		return mysql_insert_id();
	}


	// Cria a função para Desconectar do Banco MySQL
	public function desconecta(){
		return mysql_close($this->link);
	}
}

//Classe conexão
class Conexao {
    
	//Declaração das variaveis com dados do DB (set null para o constructor)
	private $host; // Nome ou IP do Servidor
	private $user; // Usuário do Servidor MySQL
	private $pass; // Senha do Usuário MySQL
	private $base; // Nome do seu Banco de Dados

	public $link;

	//Selecionar dados do banco para conexão
	public function set($h,$u,$p,$b){
		if (isset($h)&&isset($u)&&isset($p)&&isset($b)){
			$this->host=$h;
			$this->user=$u;
			$this->pass=$p;
			$this->base=$b;
		} else {
			echo "Erro ao selecionar dados do banco";
		}
	}

    //Conecta-se com o banco de dados
    function conecta() {
		//Conectar com o banco de dados
		$this->link = mysqli_connect($this->host,$this->user,$this->pass)
			or die(mysqli_error());
        //Selecionar o banco após a conexão
		if($this->link) {
			mysqli_select_db($this->link,$this->base)
			or die("Não foi possível selecionar o banco");
		} else {
			echo "Não foi possível selecionar o banco de dados. Não conectado.";
		}
    }

	//Cria a função para "query" no Banco de Dados ********************** Ajustar para mysqli ******************
 	public function sql_query($query,$f=true){
	    $this->conecta();

		//Conecta e faz a query no MySQL
	    if($this->fetch = mysql_query($query)){
	    	if($f) {
			  	while($m = mysql_fetch_assoc($this->fetch)){
			  		$this->resultado[] = $m;
			  	}
					$this->desconecta();
					return $this->resultado;
			} else {
				return mysql_affected_rows();
			}
	    } else {
			  // Caso ocorra um erro, exibe uma mensagem com o Erro
			  print "Ocorreu um erro ao executar a Query MySQL: <b>$query</b>";
				print "<br><br>";
				print "Erro no MySQL: <b>".mysql_error()."</b>";
				die();
			}        
	}

	public function last_id() {
		return mysql_insert_id();
	}

	//Desconecta do banco de dados
	public function desconecta(){
		return mysqli_close($this->link);
	}


	public function debug($bug){
		echo "<pre>";
		print_r ($bug);
		echo "</pre>";
	}

	public function show($show){
		echo $show;
	}



}

?>
