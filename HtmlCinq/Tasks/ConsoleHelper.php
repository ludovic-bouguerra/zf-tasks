<?php
	class ConsoleHelper{
		
		public static function showHelp($help){
			
			if (is_array($help)){
				foreach ($help as $key=>$value){
					echo $key. ":"; 
					self::showHelp($value);
				}
			}else{
				echo $help."\n";
			}
			
		}
		
		
	}