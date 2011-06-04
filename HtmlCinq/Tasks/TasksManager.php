<?php
	/**
	 * 
	 * TaskManager
	 * Classe qui gère (chargement, informe sur le contenu ...) le fonctionnement des taches.
	 
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
	 * 
	 * @author Ludovic Bouguerra
	 * @copyright Ludovic Bouguerra 2011
	 */
	class TasksManager{
		
		
		/**
		 * 
		 * Constante : extension d'une tache
		 * @var String
		 */
		const TASK_EXTENSION = ".php";
		
		/**
		 * 
		 * Constante, séparateur d'espaces de noms
		 * @var String
		 */
		const NAMESPACE_SEPARATOR = ":";
		
		/**
		 * 
		 * Constante, classe mère des taches
		 * @var String
		 */
		const BASE_TASK_CLASSNAME = "BaseTask";
		
		/**
		 * 
		 * Répertoire ou sont stoqués les taches
		 * @var String[]
		 */
		protected $paths = array();
		

		
	
		
		/**
		 * 
		 * Affichage de l'aide
		 * 
		 */
		public function getHelp(){
			
			$result = array();
			
			// Now we browse all "$this->paths" to see if a task with this name exists.
			foreach ($this->paths as $path){
				
				$result = array();
				$this->listTask($path, $result);
				
				print_r($result);
				
			}
			
		}
		
		
		protected function listTask($path, $result){
			
			
			if (is_dir($path)){
				
				
				$path = rtrim($path,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;	
				if ($dir = @opendir($path)){
					
					
				    while (($file = readdir($dir)) !== false) {
	
				    	
				    	if($file != ".." && $file != "."){
				    		$this->listTask($path.$file, $result);
				       }
				       
				    }
					closedir($dir);
				 }
				
				
			}
			else{
				
				//-- On vérifie que c'est un fichier --
				if (is_file($path)){
					
					//-- On récupére les informations sur le fichier
					$result = pathinfo($path);
					//-- On rajoute le "." pour pouvoir comparer avec la constante de la classe.
					
					if (".".$result["extension"] == self::TASK_EXTENSION){
						include_once $path;
						
						$className = $result["filename"];
						
						if ($this->isValidTask($className)){
							//-- On récupére l'information sur l'aide de cette tache --
							
							$task = new $className();
							echo $task->getDescription();
							
						}
					}
				}
			}
			
		}
		
		
		/**
		 * 
		 * Load a task by name.
		 * 
		 * <h1>Tasks' name</h1>
		 * 
		 * 
		 * 
		 */
		public function load($taskName, $args){
			// First, we have to analyse taskname
			$fileName =	str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR , $taskName);
			
			
			$arrayTask = explode(DIRECTORY_SEPARATOR, $fileName);
			$className = $arrayTask[sizeof($arrayTask)-1];
						
			
			// We Add the file extension
			$fileName .= self::TASK_EXTENSION;
			
			// Now we browse all "$this->paths" to see if a task with this name exists.
			foreach ($this->paths as $path){
				
				//-- We try to know if the actual "path" is a directory
				if (is_dir($path)){
					
					//-- If the last char is not a "Directory separator" we add it 
					if (substr ($path, strlen ($path) - 1) != DIRECTORY_SEPARATOR){
						$path .= DIRECTORY_SEPARATOR;
					}
					
					//-- If the file Exists --
					$filePath = $path.$fileName; 
					if (file_exists($filePath)){
						
						include_once $filePath;
						
						if ($this->loadTask($className, $args)){
							exit(0);
						}
						
					}
					
				}
				
				
			}
			

		}
		
		/**
		 * 
		 * Vérifie si une classe est une tâche
		 * Elle doit : 
		 * 	<ul> 
		 * 		<li>Hériter de BaseTask</li>
		 * 	    <li>Etre placée dans un repertoire qui est défini dans la variable $this->path see $this->addPath($path) or $this->setPath($path)</li>
		 * 
		 * 	</ul> 
		 */
		protected function isValidTask($className){
			
			$reflexionClass = new ReflectionClass($className); 
			return class_exists($className, false) && in_array(self::BASE_TASK_CLASSNAME, class_parents($className, false)) && $reflexionClass->isInstantiable();
		}
		
		/**
		 * 
		 * Charge une tache avec son nom de classe.
		 * Il est auparavant vérifié que cette tâche respecte les différentes conventions.
		 * Attention : La classe doit impérativement être chargée, ou chargeable automatiquement avec 
		 * l'autoloader.
		 * 
		 * @param mixed $className
		 */
		protected function loadTask($className, $args){
			
			if ($this->isValidTask($className)){
				$class  = new $className();
				$class->main($args);
	
				//-- La tache a correctement été chargée --
				return true;
			}
			return false;
		
		}
		
		/**
		 * 
		 * Ajoute un repertoire, au path des Taches.
		 * Attention le premier repertoire inséré est prioritaire sur les autres.
		 * 
		 * 
		 */
		public function addPath($path){
			$this->paths[] = $path;
		}
		
		
		
		public function setPath(Array $path){
			$this->paths = $path;
		}
		
	}