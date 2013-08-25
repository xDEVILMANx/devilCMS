<?
class groups{
	// Returns the groups for users that are not logged in.
	function guestGroups(){
		global $CONN;
		$groupIDs = array();
		$sql = "SELECT ".COL_GROUPS_GID." FROM ".TAB_GROUPS." WHERE ".COL_GROUPS_GUEST." = '1'";
		$groups = mysql_query($sql, $CONN) or die(mysql_error());
		while($group = mysql_fetch_assoc($groups)){
			$groupIDs[] .= $group[COL_GROUPS_GID];
		}
		return $groupIDs;
	}
	
	// Returns the groups a user gets after registration
	function registrationGroups(){
		global $CONN;
		$groupIDs = array();
		$sql = "SELECT ".COL_GROUPS_GID." FROM ".TAB_GROUPS." WHERE ".COL_GROUPS_DEFAULT." = '1'";
		$groups = mysql_query($sql, $CONN) or die(mysql_error()."<br>".$sql);
		while($group = mysql_fetch_assoc($groups)){
			$groupIDs[] .= $group[COL_GROUPS_GID];
		}
		return $groupIDs;
	}
	
	// Checks if a user has a group that is in objects groups
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
	
	// Adds a new group to the database
	function groupAdd($name, $desc){
		global $CONN;
		$sqlStr = "INSERT INTO ".TAB_GROUPS."	(
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
		mysql_query($sqlStr, $CONN)or die($sqlStr);
		return mysql_insert_id();
	}
	
	// Updates a group in the database
	function groupUpdate($id, $name, $desc){
		global $CONN;
		$sqlStr = "UPDATE ".TAB_GROUPS." SET 
																				".COL_GROUPS_NAME."='".sql::escape($name)."',
																				".COL_GROUPS_DESC."='".sql::escape($desc)."'
																	WHERE 
																				".COL_GROUPS_ID." = '".sql::escape($id)."'";
		mysql_query($sqlStr, $CONN) or die(mysql_error());
	}
	
	// Deletes a group from the database
	function groupDelete($id){
		global $CONN;
		$sqlStr = "DELETE FROM ".TAB_GROUPS." WHERE ".COL_GROUPS_ID." = '".sql::escape($id)."'";
		mysql_query($sqlStr, $CONN)or die(mysql_error());
	}
	
	// Returns a groups name by it's GID
	function getGroupNameByGID($gid){
		global $CONN;
		$groupName = "N/A";
		$sql = "SELECT ".COL_GROUPS_NAME." FROM ".TAB_GROUPS." WHERE ".COL_GROUPS_GID." = '".sql::escape($gid)."'";
		$groups = mysql_query($sql, $CONN) or die(mysql_error()."<br>".$sql);
		if(mysql_num_rows($groups) == 1){
			$group = mysql_fetch_assoc($groups);
			$groupName = $group[COL_GROUPS_NAME];
		}
		return $groupName;
	}
}
?>
