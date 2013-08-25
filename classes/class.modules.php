<?
	class modules{
		
		function getModule($moduleID){
			global $CONN;
			$sql = "SELECT * 
				FROM 	".TAB_MODULES." 
				WHERE 	".COL_MODULES_ID."='".sql::escape($moduleID)."' 
				LIMIT 	1";
			$modules = mysql_query($sql, $CONN) or die(mysql_error());
			return mysql_fetch_assoc($modules);
		}
		
		function insertModule($module){
			global $CONN;
			$sql = "INSERT INTO ".TAB_MODULES."	
				(
					".COL_MODULES_NAME.", 
					".COL_MODULES_DESC.", 
					".COL_MODULES_FILES.", 
					".COL_MODULES_CREATED."
				) 
				VALUES 
				(
					'".sql::escape($module['name'])."',
					'".sql::escape($module['desc'])."',
					'".sql::escape(serialize($module['files']))."',
					'".time()."'
				)";
			mysql_query($sql, $CONN)or die(mysql_error());
			return mysql_insert_id();
		}
		
		function deleteModule($moduleID){
			global $CONN;
			$sql = "DELETE FROM 	".TAB_MODULES." 
				WHERE 		".COL_MODULES_ID." = '".sql::escape($moduleID)."'";
			mysql_query($sql, $CONN)or die(mysql_error());
		}
		
		function updateModule($module){
			global $CONN;
			$sql = "UPDATE 	".TAB_MODULES." 
				SET 	".COL_MODULES_NAME."	='".sql::escape($module['name'])."',
					".COL_MODULES_DESC."	='".sql::escape($module['desc'])."',
					".COL_MODULES_FILES."	='".sql::escape(serialize($module['files']))."'
				WHERE 	".COL_MODULES_ID."	='".sql::escape($module['id'])."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
	}
?>
