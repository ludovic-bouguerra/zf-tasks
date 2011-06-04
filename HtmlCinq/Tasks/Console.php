<?php
	/**
	 * Console.php
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
	 * @copyright Ludovic Bouguerra 2011
	 * @author Ludovic Bouguerra
	 *
	 * 
	 */
	//-- This application run only in CLI mode --
	if(defined('STDIN') ){ 

		include_once 'BaseTask.php';
		include_once 'TasksManager.php';
		
			
		// Define path to application directory
		defined('APPLICATION_PATH')
		    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../../application'));
		
		    
		// Define application environment
		defined('APPLICATION_ENV')
		    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
		
		    
		define("TASKS_USER_PATH", realpath(APPLICATION_PATH."/../tasks/"));
		    
		// Ensure library/ is on include_path
		set_include_path(implode(PATH_SEPARATOR, array(
		    realpath(APPLICATION_PATH . '/../library'),
		    get_include_path(),
		)));
		
		/** Zend_Application */
		require_once 'Zend/Application.php';
		
		// Create application
		$application = new Zend_Application(
		    APPLICATION_ENV,
		    APPLICATION_PATH . '/configs/application.ini'
		);
		
		
		$tasksManager = new TasksManager();
		$tasksManager->addPath(TASKS_USER_PATH);
		
		if ($argc >1){
			
			$tasksManager->load($argv[1], null);
		
		}
		else {
			//-- In this case show all tasks --
			$tasksManager->getHelp();
						
			
		}		
	}