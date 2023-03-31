<?php
namespace MyClass;

use mysqli;

class Check_user
{
    public bool $wflag=false;
    public int $user;
    public bool $status=false;
    public array $data=[];
    public mysqli $conn;
    public string $table='';
    public string $query='';
    public array $where=[];
  public function __construct(int $user_id,mysqli $conn)
  {
      $this->user=$user_id;
      //$this->conn=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
      if (!$conn){
          return false;
      }
      else{
          $this->conn=$conn;
          $query="SELECT `account_secretkey` as `secret` , `account_passphrase` as `pass` , `account_api_key` as `key` , `exchange_id` as `exchange`  FROM `accounts` WHERE `user_id` = ".$this->user." AND `exchange_id` = 1 LIMIT 1 ";
          $result=mysqli_query($conn,$query);
          if ($result->num_rows>0){
              $row = mysqli_fetch_assoc($result);
              $this->data=['secret'=>$row['secret'],'pass'=>$row['pass'],'key'=>$row['key'],'exchange'=>$row['exchange']];
              $this->status=true;
              return true;
          }
          else{
              return false;
          }


      }

  }
  public function select(string $table,array $field){
        $this->table=$table;
        $this->data=$field;
        return $this;
  }
  public function where(string $field,string $operator,string $value,string $logic=''){
      $this->wflag=true;
      if (isset($this->where['start']) ){
          $this->where['data']=[$field,$operator,$value,$logic];
      }else{
          $this->where['start']=[$field,$operator,$value];
      }
      return $this;

      }
  public function get(){
      $field_name=[];
      $this->query.='SELECT';
      if (empty($this->data)){
          $this->query.="*";
      }
      else{
          $i=0;
          foreach ($this->data as $data){
              $field_name[$data]='';
              if (isset($this->data[$i+1])){
                  $this->query.=' '.$data.' ,';
              }
              else{
                  $this->query.=' '.$data.' ';
              }
              $i++;
          }
      }
      $this->query.='FROM '.$this->table;
      if (empty($field_name)){
          $result1=mysqli_query($this->conn,'SHOW COLUMNS FROM '.$this->table);
          $fields=mysqli_fetch_all($result1);
          foreach ($fields as $value){
              $field_name[$value[0]]='';
          }
      }
      $result=mysqli_query($this->conn,$this->query);
      $d=mysqli_fetch_all($result);
      $total=[];
      foreach ($d as $data){
          $i=0;
          foreach ($field_name as $key => $value){
              $field_name[$key]=$data[$i];
              $i++;
          }
          array_push($total,$field_name);
      }
      return $total;
  }
}
