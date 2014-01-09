<table style="background: #000;border-radius: 8pt;margin: 0 auto 5mm;padding: 0 4pt;box-shadow: 4px 6px 5px #888;"><tr style='vertical-align: text-top;'>
<td style='display: inline-block; color: lightgreen;'>
<h2 style='margin: 0;'>Infos</h2>
<ul style='margin: 0;'>
<?php foreach($GLOBALS['infos'] as $msg) echo '<li>'.$msg.'</li>'; ?>
</ul>
</td>
<td style='display: inline-block; color: orange;'>
<h2 style='margin: 0;'>Warnings</h2>
<ul style='margin: 0;'>
<?php foreach($GLOBALS['warnings'] as $msg) echo '<li>'.$msg.'</li>'; ?>
</ul>
</td>
<td style='display: inline-block; color: red;'>
<h2 style='margin: 0;'>Errors</h2>
<ul style='margin: 0;'>
<?php foreach($GLOBALS['errors'] as $msg) echo '<li>'.$msg.'</li>'; ?>
</ul>
</div>
</tr></table>
