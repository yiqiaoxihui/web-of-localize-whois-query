<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Whois extends Eloquent {
	protected $connection="mongodb";
    protected $collection = 'whois3';

}