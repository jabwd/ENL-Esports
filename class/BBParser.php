<?php
// Wolfenstein: Enemy Territory League
// Copyright © 2011 Antwan van Houdt
//******************************************************************************
//	  This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//******************************************************************************
//
$bbCodes = array("","");

class BBParser
{
	function parse($p_stringIN)
	{
		$p_stringIN = str_ireplace("[u]", "<u>",$p_stringIN);
		$p_stringIN = str_ireplace("[/u]", "</u>",$p_stringIN);
		
		$p_stringIN = str_ireplace("[b]", "<b>",$p_stringIN);
		$p_stringIN = str_ireplace("[/b]", "</b>",$p_stringIN);
		
		$p_stringIN = str_ireplace("[i]", "<i>",$p_stringIN);
		$p_stringIN = str_ireplace("[/i]", "</i>",$p_stringIN);
		
		$p_stringIN = str_ireplace("[i]", "<i>",$p_stringIN);
		$p_stringIN = str_ireplace("[/i]", "</i>",$p_stringIN);
		
		$p_stringIN = str_ireplace("[img]", "<img alt=\"postImage\" src=\"",$p_stringIN);
		$p_stringIN = str_ireplace("[/img]", "\"/>",$p_stringIN);
		return $p_stringIN;
	}
}
?>