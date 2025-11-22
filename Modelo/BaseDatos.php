<?php
class BaseDatos {
    private $engine;
    private $host;
    private $database;
    private $user;
    private $pass;
    private $debug;
    private $conexion;
    private $indice;
    private $resultado;
    
    private $sql; 
    private $error;

    public function __construct(){
        $this->engine = 'mysql';
        $this->host = 'localhost';
        $this->database = 'bdcarritocompras';
        $this->user = 'root';
        $this->pass = 'root';
        $this->debug = true;
        $this->error = "";
        $this->sql = "";
        $this->indice =0;
        $this->resultado;
    }

    public function Iniciar(){
        try{
            $this->conexion = new PDO($this->engine.':host='.$this->host.';dbname='.$this->database, $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }catch(PDOException $e){
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function Ejecutar($sql){
        $this->error = "";
        $this->sql = $sql;
        if($this->sql == ""){
            $this->error = "SQL vacio";
            return false;
        }
        try {
            $this->resultado = $this->conexion->query($this->sql);
            return true;
        } catch(PDOException $e){
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function Registro(){
        if($this->resultado){
            return $this->resultado->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    public function getError(){ return $this->error; }
    
    public function devuelveID(){
        if ($this->conexion) {
            return $this->conexion->lastInsertId();
        }
        return -1;
    }
}
?>