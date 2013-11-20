<?php
require '/kint/Kint.class.php';
     
     
Kint::dump( $_SERVER );
// or, even easier, use a shorthand:
d( $_SERVER );
// or, to seize execution after dumping use dd();
dd( $_SERVER ); // same as d( $_SERVER ); die;
     
     
// to see trace:
Kint::trace();
// or pass 1 to a dumper function
Kint::dump( 1 );
     
     
// to disable all output
Kint::enabled(false);
// further calls, this one included, will not yield any output
d('Get off my lawn!'); // no effect
