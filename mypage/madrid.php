<?php
function UTF($STR)
{
	return mb_convert_encoding($STR,'utf-8','cp1251');
}
?>