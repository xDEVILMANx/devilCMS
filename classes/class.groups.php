<?
class groups{
	
	function guestGroups(){
		global $CONN;
		$groupIDs = array();
		$sql = "SELECT 	".COL_GROUPS_GID." 
			FROM 	".TAB_GROUPS." 
			WHERE 	".COL_GROUPS_GUEST." = '1'";
		$groups = mysql_query($sql, $CONN) or die(mysql_error());
		while($group = mysql_fetch_assoc($groups)){
			$groupIDs[] .= $group[COL_GROUPS_GID];
		}
		return $groupIDs;
	}
	
	function registrationGroups(){
		global $CONN;
		$groupIDs = array();
		$sql = "SELECT 	".COL_GROUPS_GID." 
			FROM 	".TAB_GROUPS." 
			WHERE 	".COL_GROUPS_DEFAULT." = '1'";
		$groups = mysql_query($sql, $CONN) or die(mysql_error());
		while($group = mysql_fetch_assoc($groups)){
			$groupIDs[] .= $group[COL_GROUPS_GID];
		}
		return $groupIDs;
	}
	
	function check($userGroups, $objectGroups){
		if(is_array($userGroups) && is_array($objectGroups)){
			foreach($userGroups as $userGroup){
				if(in_array($userGroup, $objectGroups)){
					return true;
				}
			}
		}
		return false;
	}
	
	function groupAdd($name, $desc){
		global $CONN;
		$sql = "INSERT INTO ".TAB_GROUPS."
			(
				".COL_GROUPS_GID.", 
				".COL_GROUPS_NAME.", 
				".COL_GROUPS_DESC.", 
				".COL_GROUPS_CREATED."
			) 
			VALUES 
			(
				'".md5(uniqid(mt_rand(), true))."',
				'".sql::escape($name)."',
				'".sql::escape($desc)."',
				'".time()."'
			)";
		mysql_query($sql, $CONN)or die(mysql_error());
		return mysql_insert_id();
	}
	
	function groupUpdate($id, $name, $desc){
		global $CONN;
		$sql = "UPDATE 	".TAB_GROUPS." 
			SET 	".COL_GROUPS_NAME."='".sql::escape($name)."',
				".COL_GROUPS_DESC."='".sql::escape($desc)."'
			WHERE 	".COL_GROUPS_ID." = '".sql::escape($id)."'";
		mysql_query($sql, $CONN) or die(mysql_error());
	}
	
	function groupDelete($id){
		global $CONN;
		$sql = "DELETE FROM 	".TAB_GROUPS." 
			WHERE 		".COL_GROUPS_ID." = '".sql::escape($id)."'";
		mysql_query($sql, $CONN)or die(mysql_error());
	}
	
	function getGroupNameByGID($gid){
		global $CONN;
		$groupName = "N/A";
		$sql = "SELECT 	".COL_GROUPS_NAME." 
			FROM 	".TAB_GROUPS." 
			WHERE 	".COL_GROUPS_GID." = '".sql::escape($gid)."'";
		$groups = mysql_query($sql, $CONN) or die(mysql_error());
		
		if(mysql_num_rows($groups) == 1){
			$group = mysql_fetch_assoc($groups);
			$groupName = $group[COL_GROUPS_NAME];
		}
		return $groupName;
	}
}
?>
