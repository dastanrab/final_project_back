<?php
namespace MyClass;

class Sender
{
    public string $url='';
    public string $method='GET';
    public array $body=[];
    public array $headers=[];
    public bool $post=false;
    public string $endpoint="?";
    public function send()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        if ($this->method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        if ($this->method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        if(!empty($this->headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }
    public function get_body(string $key,$value){
    $this->post=true;
    $this->body[$key]=$value;
    return $this;
}
    public function signature($request_path = '', $timestamp = false,$version=1)
    {

        if (strlen($this->endpoint)>1){

            $this->url.=$request_path.$this->endpoint;
            $body ='';
            $request_path=$request_path.$this->endpoint;
        }
        else{
            $body = $this->post==true ? json_encode($this->body) : '' ;
            $this->url.=$request_path;
        }

        $timestamp = $timestamp ? $timestamp : time() * 1000;
        $what = $timestamp . $this->method . $request_path . $body;
        $this->headers[] = "Content-Type:application/json";
        $this->headers[] = "KC-API-KEY:".$this->key;
        $this->headers[] = "KC-API-TIMESTAMP:".$timestamp;
        $this->headers[] = "KC-API-KEY-VERSION:".$version;
        if ($version==2){
            $this->headers[]= "KC-API-PASSPHRASE:".base64_encode(hash_hmac('sha256', $this->passphrase, $this->secret, true));
        }else{
            $this->headers[]= "KC-API-PASSPHRASE:".$this->passphrase;
        }

        $this->headers[] = "KC-API-SIGN:".base64_encode(hash_hmac("sha256", $what, $this->secret, true));

        return  $request_path;
    }
    public function __construct($key, $secret, $passphrase,$url,$method)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->passphrase = $passphrase;
        $this->method=$method;
        $this->url=$url;
    }
    public function get_endpoint_body(string $key,string $value,bool $end=false){
        $this->post=false;
        if ($end==true){
            $this->endpoint.=$key."=".$value;
            $this->body[$key]=$value;
            return $this;
        }else{
            $this->endpoint.=$key."=".$value."&";
            $this->body[$key]=$value;
            return $this;
        }

    }
}
//$api=new API('61ecf3cd41a5330001d0ebb6','098fc390-a060-4fce-8bb4-db44ca7200c6','7nJgWyc6vGt8cVV','https://openapi-sandbox.kucoin.com','POST');
//print_r($api->get_body('type','trade')->get_body('currency','KCS')->signature("/api/v1/accounts",false,'POST')->send()) ;