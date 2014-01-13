<style>#forms>form{display: inline-block;} #forms{text-align: center;}</style>
<div id="forms">
<form method="POST">
	<input type="hidden" name="action" value="user"/><input type="submit" value="'user' details"/>
</form>
<form method="POST">
	<input type="hidden" name="action" value="list"/><input type="submit" value="'list' notes around"/>
</form>
<form method="POST">
	<input type="hidden" name="action" value="list_mine"/><input type="submit" value="'list_mine' (notes)"/>
</form>
<form method="POST">
	<input type="hidden" name="action" value="note_add"/>
	<input type="hidden" name="lat" value="<?php echo rand(464662412291, 466022801330)/10000000000; ?>"/>
	<input type="hidden" name="lon" value="<?php echo rand(64524078369, 68060302734)/10000000000; ?>"/>
	<input type="hidden" name="title" value="<?php echo shell_exec("shuf -n5 /usr/share/dict/words | tr '\n' ' '"); ?>"/>
	<input type="hidden" name="text" value="<?php echo shell_exec("shuf -n100 /usr/share/dict/words | tr '\n' ' '"); ?>"/>
	<input type="hidden" name="lifetime" value="0"/>
	<input type="hidden" name="lang" value="en"/>
	<input type="hidden" name="cat" value="<?php echo rand(0,3); ?>"/>
	<input type="submit" value="'note_add'"/>
</form>
<form method="POST">
	<input type="hidden" name="action" value="logout"/><input type="submit" value="'logout'"/>
</form>
</div>
