<?php


namespace MyClass;

use MyClass\Sender;
use mysqli;

class kucoin extends Sender
{
    public mysqli $conn;
    public int $user_id;
    public string $start;
    public string $end;
    public int $exchange_id;
    public function __construct($key, $secret, $passphrase, $url, $method,$exchange_id)
    {
        $this->conn=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
        $this->exchange_id=$exchange_id;
        parent::__construct($key, $secret, $passphrase, $url, $method);
    }
    public function create_account(string $currency,string $type){
            $check=new Check_user($_SESSION['user_id'],$this->conn);
            $check->data;
            $this->get_body('type',$type)->get_body('currency',$currency)->signature("/api/v1/accounts",false) ;
            $this->send();
            $data=json_decode($this->send());
            if ($data->code==200000) {
                return '200';

            }else{
                return '400';

            }

    }
    public function trade_update(string $start='1638943352000',string $end='1639461752000'){
        $this->start=$start;
        //$this->end=$end;
       // $path='/api/v1/orders?status=done&startAt='.$this->start.'&At='.$this->end;
        $path='/api/v1/orders?status=done&startAt='.$this->start;
        $this->signature($path,false,2);
            $data=json_decode($this->send());
            $c=0;
            if ($data->code==200000){
                if (isset($data->data->items)){
                    foreach ($data->data->items as $item){
                        if (!$item->cancelExist){
                            if (isset($item->walletTxId)){
                                $w=$item->walletTxId;
                            }
                            else{
                                $w='';
                            }
                            if (isset($item->remark)){
                                $r=$item->remark;
                            }else{
                                $r="";
                            }
                            if ($item->side=='sell'){
                                $side='S';
                            }
                            else{
                                $side='B';

                            }
                            if ($item->type=='market'){
                                $price=$item->dealFunds/$item->dealSize;
                                $size=$item->dealSize;
                            }
                            else{
                                $price=$item->price;
                                $size=$item->size;
                            }
                            $symbol=explode('-',$item->symbol);
                            $query1="INSERT  INTO `transfer` (`id`,`exchange_id`,`type`,`price`,`user_id`, `currency`, `status`, `isInner`, `amount`, `fee`, `walletTxId`, `createdAt`, `updatedAt`, `remark`) VALUES ('".$item->id."',".$this->exchange_id.",'".$side."',".$price.", ".$_SESSION['user_id'].",'".$symbol[0]."','".'success'."' , '0', ".$size.", ".$item->fee.",'".$w."' , ".$item->createdAt.",  ".$item->createdAt.",'".$r."' )
                    ON DUPLICATE KEY UPDATE `id`= '".$item->id."', `exchange_id` = ".$this->exchange_id.",`type` = '".$side."' ,`price` = ".$price." ,`user_id` = ".$_SESSION['user_id'].", `currency` = '".$symbol[0]."', `status` = 'success', `isInner` = '0', `amount` = ".$size.", `fee` = ".$item->fee.", `walletTxId` = '".$w."', `createdAt` = ".$item->createdAt.", `updatedAt` = ".$item->createdAt.", `remark` = '".$r."' ";
                            if (mysqli_query($this->conn,$query1)){
                            }
                            else{

                            }
                        }
                        else{
                            $c++;
                        }
                    }
                    return $c;
                }
                else{
                    return $c;
                }

            }
            else{
                return $c;
            }

    }
    public function trade_update1(){
        $path='/api/v1/limit/orders';
        $this->signature($path,false,2);
        $data=json_decode($this->send());
        if ($data->code==200000){
            if (isset($data->data)){
                foreach ($data->data as $item){
                    if (isset($item->walletTxId)){
                        $w=$item->walletTxId;
                    }
                    else{
                        $w='';
                    }
                    if (isset($item->remark)){
                        $r=$item->remark;
                    }else{
                        $r="";
                    }
                    if ($item->side=='sell'){
                        $side='S';
                    }
                    else{
                        $side='B';
                    }
                    if ($item->type=='market'){
                        $price=$item->dealFunds/$item->dealSize;
                        $size=$item->dealSize;
                    }
                    else{
                        $price=$item->price;
                        $size=$item->size;
                    }
                    $symbol=explode('-',$item->symbol);
                    $query1="INSERT  INTO `transfer` (`id`,`exchange_id`,`type`,`price`,`user_id`, `currency`, `status`, `isInner`, `amount`, `fee`, `walletTxId`, `createdAt`, `updatedAt`, `remark`) VALUES ('".$item->id."',".$this->exchange_id.",'".$side."',".$price.", ".$_SESSION['user_id'].",'".$symbol[0]."','".'success'."' , '0', ".$size.", ".$item->fee.",'".$w."' , ".$item->createdAt.",  ".$item->createdAt.",'".$r."' )
                    ON DUPLICATE KEY UPDATE `id`= '".$item->id."', `exchange_id` = ".$this->exchange_id.",`type` = '".$side."' ,`price` = ".$price." ,`user_id` = ".$_SESSION['user_id'].", `currency` = '".$symbol[0]."', `status` = 'success', `isInner` = '0', `amount` = ".$size.", `fee` = ".$item->fee.", `walletTxId` = '".$w."', `createdAt` = ".$item->createdAt.", `updatedAt` = ".$item->createdAt.", `remark` = '".$r."' ";
                    if (mysqli_query($this->conn,$query1)){
                    }
                    else{

                    }

                }
                return 'update';
            }
            else{
                return 'error';
            }

        }
        else{
            return 'error in update';
        }

    }
    public function dw_update(int $flag){
        $check=new Check_user($_SESSION['user_id'],$this->conn);
        $check->data;
        if ($flag==1){
            $name='deposits';
            $type='D';
        }
        else{
            $name='withdrawals';
            $type='W';
        }
        $path='/api/v1/'.$name.'?status=SUCCESS';
       // $path='/api/v1/accounts/61efb662aa25150001f85da4/ledgers';
        $arr=[];
        $this->signature($path,false);
        $result=json_decode($this->send());
        if (isset($result->data->items)){
                if (count($result->data->items)>0){
                    foreach ($result->data->items as $item){
                        $query4="SELECT * FROM `coins` WHERE `symbol` = '".$item->currency."' LIMIT 1 ";
                        $result4=mysqli_query($this->conn,$query4);
                        $row4 = mysqli_fetch_assoc($result4);
                        if (isset($row4['web_id'])){
                            $query3="SELECT * FROM `prices` WHERE `prices`.`real_date`< ".$item->createdAt."  AND web_id = ".$row4['web_id']."  ORDER BY `prices`.`real_date` DESC LIMIT 1";
                            $result3=mysqli_query($this->conn,$query3);
                            if ($result3->num_rows>0){
                                $row = mysqli_fetch_assoc($result3);
                                $price=$row['price'];
                            }
                            else{
                                $price=0;
                            }
                        }
                        else{
                            $price=0;
                        }
                        if (isset($item->walletTxId)){
                            $w=$item->walletTxId;
                        }
                        else{
                            $w='';
                        }
                        if (isset($item->remark)){
                            $r=$item->remark;
                        }else{
                            $r="";
                        }

                        $query1="INSERT IGNORE  INTO `transfer` (`id`,`exchange_id`,`account_id`,`type`,`price`,`user_id`, `currency`, `status`, `isInner`, `amount`, `fee`, `wd_id`, `createdAt`, `updatedAt`, `remark`) VALUES ('".rand(1000,5000)."',".$this->exchange_id.",'".$w."','".$type."',".$price.", ".$_SESSION['user_id'].",'".$item->currency."','".$item->status."' , '".$item->isInner."', ".$item->amount.", ".$item->fee.",'".$item->address."' , ".$item->createdAt.",  ".$item->updatedAt.",'".$r."' )";
                        if (mysqli_query($this->conn,$query1)){
                            array_push($arr,'ok');
                        }
                        else{
                            array_push($arr,'false');
                        }

                    }
                   return $arr;
                }
            }
              return '200';
    }
    public function account_list(){
        $path='/api/v1/accounts';
        $result1 = mysqli_query($this->conn,"select count(1) as c FROM `account` where `type` = 'trade' AND `user_id` = ".$_SESSION['user_id']." ");
        $row1 = mysqli_fetch_array($result1);
        if ($row1['c']>0){
            $query2="SELECT * FROM `account` where `user_id` = ".$_SESSION['user_id']." AND `type` = 'trade'";
            $accounts=mysqli_query($this->conn,$query2);
            return $accounts;
        }
        else{
            $this->signature($path,false,2);
            $data=json_decode($this->send());
            if ($data->code==200000){
                if (count($data->data ) > 0){
                    foreach ($data->data as $account){
                        $query1="INSERT INTO `account` (`id`,`type`,`user_id`, `currency`,`available`,`last_price`) VALUES ('".$account->id."','".$account->type."', ".$_SESSION['user_id'].",'".$account->currency."', ".$account->available." ,0)
                        ON DUPLICATE KEY UPDATE `id` = '".$account->id."',`type` = '".$account->type."',`user_id` = ".$_SESSION['user_id'].", `currency` = '".$account->currency."',`available` = ".$account->available.",`last_price` = 0";
                        if (mysqli_query($this->conn,$query1)){
                        }
                        else{
                        }
                    }
                    $query2="SELECT * FROM `account` where `user_id` = ".$_SESSION['user_id']." AND `type` = 'trade'";
                    $accounts=mysqli_query($this->conn,$query2);
                    return $accounts;
                }
                else{
                    return [];
                }
            }
            else{
                return '400';
            }
        }
    }
    public function last_price(){
        $result1 = mysqli_query($this->conn,"select count(1) as c FROM `account` where `type` = 'trade' ");
        $row1 = mysqli_fetch_array($result1);
        if ($row1['c']>0){
            $path='/api/v1/market/allTickers';
            $this->signature($path,false,2);
            $data=json_decode($this->send());
            $latest=$data->data->ticker;
            $query2="SELECT * FROM `account` where `user_id` = ".$_SESSION['user_id']." AND  `currency` != 'USDT' ";
            $accounts=mysqli_query($this->conn,$query2);
            $currencies=[];
            foreach ($accounts as $account){
                $currencies[$account['currency'].'-USDT']=['last'=>$account['currency'],'id'=>$account['id']];
            }
            $c=count($currencies);
            $i=0;
            foreach ($latest as $last){
                if ($i==$c){
                    break;
                }
                else{
                    if (array_key_exists($last->symbol,$currencies)){
                       // $currencies[$account['currency'].'-USDT']=['last'=>0,'id'=>$account['id']];
                        $q="UPDATE `account` SET `last_price`=" . $last->last. " WHERE `currency` = '" . $currencies[$last->symbol]['last'] . "' AND `user_id` = ".$_SESSION['user_id']."  ";
                        if (mysqli_query($this->conn,$q )){

                        }
                        else{

                        }
                        $i++;
                    }
                }
            }
            return '200';

        }
        else{

            return '200';

        }
    }
    public function check_auth(){
        $path='/api/v1/accounts';
            $this->signature($path,false,2);
            $data=json_decode($this->send());
            return $data;


    }
    public function dh(string $start='1638943352000'){
        $path='/api/v1/hist-deposits?startAt='.$start;
        $this->signature($path,false);
        $result=json_decode($this->send());
        return $result;
    }
}