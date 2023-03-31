<?php

interface Pay
{
    public function pay();
    public function callback_url();
    public function verify();

}
