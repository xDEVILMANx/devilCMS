<?
	class modules{
		// Returns a module from the database
		function getModule($moduleID){
			global $CONN;
			$sqlStr = "SELECT * FROM ".TAB_MODULES." WHERE ".COL_MODULES_ID."='".sql::escape($moduleID)."' LIMIT 1";
			$modules = mysql_query($sqlStr, $CONN) or die(mysql_error());
			return mysql_fetch_assoc($modules);
		}
		
		// Adds a new module to the database
		function insertModule($module){
			global $CONN;
			$sqlStr = "INSERT INTO ".TAB_MODULES."	(
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
			mysql_query($sqlStr, $CONN)or die(mysql_error());
			return mysql_insert_id();
		}
		
		// Deletes a module from the database
		function deleteModule($moduleID){
			global $CONN;
			$sqlStr = "DELETE FROM ".TAB_MODULES." WHERE ".COL_MODULES_ID." = '".sql::escape($moduleID)."'";
			mysql_query($sqlStr, $CONN)or die(mysql_error());
		}
		
		// Updates a module in the database
		function updateModule($module){
			global $CONN;
			$sqlStr = "UPDATE ".TAB_MODULES." SET 
																						".COL_MODULES_NAME."='".sql::escape($module['name'])."',
																						".COL_MODULES_DESC."='".sql::escape($module['desc'])."',
																						".COL_MODULES_FILES."='".sql::escape(serialize($module['files']))."'
																		 WHERE 
																		 				".COL_MODULES_ID."='".sql::escape($module['id'])."'";
			mysql_query($sqlStr, $CONN) or die(mysql_error());
		}
	}
?>
