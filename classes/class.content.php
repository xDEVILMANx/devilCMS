<?
	class content{
		
		function getStartCID(){
			global $CONN;
			$sql = "SELECT 	".COL_CONTENT_CID." 
				FROM 	".TAB_CONTENT." 
				WHERE 	".COL_CONTENT_START." = '1' 
				LIMIT 	1";
			$contents = mysql_query($sql, $CONN) or die(mysql_error());
			$content = mysql_fetch_assoc($contents);
			return $content[COL_CONTENT_CID];
		}
		
		function getStartLoginCID(){
			global $CONN;
			$sql = "SELECT 	".COL_CONTENT_CID." 
				FROM 	".TAB_CONTENT." 
				WHERE 	".COL_CONTENT_START_LOGIN." = '1' 
				LIMIT 	1";
			$contents = mysql_query($sql, $CONN) or die(mysql_error());
			$content = mysql_fetch_assoc($contents);
			return $content[COL_CONTENT_CID];
		}
		
		function getActiveTheme(){
			global $CONN;
			$sql = "SELECT 	* 
				FROM 	".TAB_THEMES." 
				WHERE ".COL_THEMES_ACTIVE." = '1' 
				LIMIT 	1";
			$themes = mysql_query($sql, $CONN) or die(mysql_error());
			$theme = mysql_fetch_assoc($themes);
			
			$themeData['name'] = $theme[COL_THEMES_NAME];
			$themeData['path']['jQuery'] = $theme[COL_THEMES_PATH_JQUERY];
			$themeData['path']['background'] = $theme[COL_THEMES_PATH_BACKGROUND];
			
			return $themeData;
		}
		
		function changeActiveTheme($id){
			global $CONN;
			$sql = "UPDATE 	".TAB_THEMES." 
				SET 	".COL_THEMES_ACTIVE."='0'";
			mysql_query($sql, $CONN) or die(mysql_error());
			
			$sql = "UPDATE 	".TAB_THEMES." 
				SET 	".COL_THEMES_ACTIVE."='1'
				WHERE 	".COL_THEMES_ID." = '".sql::escape($id)."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
		
		function updateTheme($id, $themeData){
			global $CONN;
			$sql = "UPDATE 	".TAB_THEMES." 
				SET 	".COL_THEMES_NAME."='".sql::escape($themeData['name'])."',
					".COL_THEMES_PATH_JQUERY."='".sql::escape($themeData['path']['jQuery'])."',
					".COL_THEMES_PATH_BACKGROUND."='".sql::escape($themeData['path']['background'])."'
				WHERE 	".COL_THEMES_ID." = '".sql::escape($id)."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
		
		function deleteTheme($id){
			global $CONN;
			$sql = "DELETE FROM 	".TAB_THEMES." 
				WHERE 		".COL_THEMES_ID." = '".sql::escape($id)."'";
			mysql_query($sql, $CONN)or die(mysql_error());
		}
		
		function getContent($cid){
			global $CONN,$DEVIL_CMS_INI;
			$sql = "SELECT 	* 
				FROM 	".TAB_CONTENT." 
				WHERE 	".COL_CONTENT_CID." = '".sql::escape($cid)."' 
				LIMIT 	1";
			$contents = mysql_query($sql, $CONN) or die(mysql_error());
			$content = mysql_fetch_assoc($contents);
			
			if(file_exists($content[COL_CONTENT_PATH])){
				$content['status'] = 'noContent';
				$content['scripts'] = array();
				if(groups::check($_SESSION[$DEVIL_CMS_INI['USER']['LOGIN']['SESSION']['GROUPS']], explode(",", $content[COL_CONTENT_GROUPS]))){
					ob_start();
					$module = modules::getModule($content[COL_CONTENT_MODULE_ID]);
					$files = unserialize($module[COL_MODULES_FILES]);
					if(is_array($files)){
						foreach($files as $file){
							require($DEVIL_CMS_INI['CMS']['PATH']."/".$file);
						}
					}
					include($content[COL_CONTENT_PATH]);
					$content['html'] = utf8_encode(ob_get_clean());
					$content['moduleID'] = $content[COL_CONTENT_MODULE_ID];
					$content['scripts'] = content::getContentScripts($cid);
					$content['status'] = 'success';
					chdir(dirname($_SERVER['SCRIPT_FILENAME']));
				}else{
					$content['status'] = 'noPermission';
				}
			}
			return $content;
		}
		
		function deleteContent($id){
			global $CONN;
			$sql = "DELETE FROM 	".TAB_CONTENT." 
				WHERE 		".COL_CONTENT_ID." = '".sql::escape($id)."'";
			mysql_query($sql, $CONN)or die(mysql_error());
		}
		
		function getContentID($cid){
			global $CONN;
			$id = -1;
			$sql = "SELECT 	".COL_CONTENT_ID." 
				FROM 	".TAB_CONTENT." 
				WHERE 	".COL_CONTENT_CID." = '".sql::escape($cid)."' 
				LIMIT 	1";
			$contents = mysql_query($sql, $CONN) or die(mysql_error());
			if(mysql_num_rows($contents) == 1){
				$content = mysql_fetch_assoc($contents);
				$id = $content[COL_CONTENT_ID];
			}
			return $id;
		}
		
		function getContentCID($id){
			global $CONN;
			$sql = "SELECT 	".COL_CONTENT_CID." 
				FROM 	".TAB_CONTENT." 
				WHERE 	".COL_CONTENT_ID." = '".sql::escape($id)."' 
				LIMIT 	1";
			$contents = mysql_query($sql, $CONN) or die(mysql_error());
			$content = mysql_fetch_assoc($contents);
			return $content[COL_CONTENT_CID];
		}
		
		function getContentScripts($cid){
			global $CONN;
			$scripts = array();
			$sql = "SELECT 	".COL_CONTENT_SCRIPTS." 
				FROM 	".TAB_CONTENT." 
				WHERE 	".COL_CONTENT_CID." = '".sql::escape($cid)."' 
				LIMIT 	1";
			$contents = mysql_query($sql, $CONN) or die(mysql_error());
			$content = mysql_fetch_assoc($contents);
			$scripts = unserialize($content[COL_CONTENT_SCRIPTS]);
			return $scripts;
		}
		
		function updateContent($id, $name, $desc, $module_id, $groups, $start, $startLogin, $path, $scripts){
			global $CONN;
			if(!is_array($scripts)){
				$scripts = array();
			}
			$sql = "UPDATE 	".TAB_CONTENT." 
				SET 	".COL_CONTENT_NAME."		='".sql::escape($name)."',
					".COL_CONTENT_DESC."		='".sql::escape($desc)."',
					".COL_CONTENT_MODULE_ID."	='".sql::escape($module_id)."',
					".COL_CONTENT_GROUPS."		='".sql::escape(implode(",", $groups))."',
					".COL_CONTENT_START."		='".sql::escape($start)."',
					".COL_CONTENT_START_LOGIN."	='".sql::escape($startLogin)."',
					".COL_CONTENT_PATH."		='".sql::escape($path)."',
					".COL_CONTENT_SCRIPTS."		='".sql::escape(serialize($scripts))."',
					".COL_CONTENT_LAST_CHANGE."	='".sql::escape(time())."'
				WHERE 	".COL_CONTENT_ID."		='".sql::escape($id)."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
		
		function insertContent($cid, $name, $desc, $moduleID, $groups, $start, $startLogin, $path, $scripts){
			global $CONN;
			$sql = "INSERT INTO ".TAB_CONTENT."
				(
					".COL_CONTENT_CID.", 
					".COL_CONTENT_NAME.", 
					".COL_CONTENT_DESC.",
					".COL_CONTENT_MODULE_ID.", 
					".COL_CONTENT_GROUPS.", 
					".COL_CONTENT_START.", 
					".COL_CONTENT_START_LOGIN.",
					".COL_CONTENT_PATH.",
					".COL_CONTENT_SCRIPTS.",
					".COL_CONTENT_CREATED.",
					".COL_CONTENT_LAST_CHANGE."
				) 
				VALUES 
				(
					'".sql::escape($cid)."',
					'".sql::escape($name)."',
					'".sql::escape($desc)."',
					'".sql::escape($moduleID)."',
					'".sql::escape(implode(",", $groups))."',
					'".sql::escape($start)."',
					'".sql::escape($startLogin)."',
					'".sql::escape($path)."',
					'".sql::escape(serialize($scripts))."',
					'".sql::escape($time)."',
					'".sql::escape($time)."'
				)";
			mysql_query($sql, $CONN)or die(mysql_error());
			return mysql_insert_id();
		}
		
		
		function getContentGroups($cid){
			global $CONN;
			$sql = "SELECT 	".COL_CONTENT_GROUPS." 
				FROM 	".TAB_CONTENT." 
				WHERE 	".COL_CONTENT_CID." = '".sql::escape($cid)."'";
			$contents = mysql_query($sql, $CONN) or die(mysql_error());
			$content = mysql_fetch_assoc($contents);
			return explode(",", $content[COL_CONTENT_GROUPS]);
		}
	}
?>
