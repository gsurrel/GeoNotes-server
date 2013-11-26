<?php
require 'kint/Kint.class.php';


d($_SERVER);

d( 1 );


// to disable all output
Kint::enabled(false);
// further calls, this one included, will not yield any output
d('Get off my lawn!'); // no effect
