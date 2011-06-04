<?php

	/**
	 * BaseTask 
	 *
	 *
	 * @version 0.1 alpha
	 * 
	 * @license
	 * Copyright (C) 2011 Ludovic Bouguerra.
	 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License 
	 * as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
	 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
	 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	 * See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program. 
	 * If not, see http://www.gnu.org/licenses/
	 * 
	 * @author Ludovic Bouguerra
	 * @copyright Ludovic Bouguerra 2011
	 */
	abstract class BaseTask{
		
		/**
		 * 
		 * Function executed when this task called;
		 * @param unknown_type $args
		 */
		public abstract function main($args);
		
		/**
		 * 
		 * @return String : Description of task
		 */
		public abstract function getDescription();
		
	}