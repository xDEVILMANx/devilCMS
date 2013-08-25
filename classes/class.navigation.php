<?
	class navigation{
		
		function blocks(){
			global $CONN, $DEVIL_CMS_INI;
			
			$navi = array();
			$sql = "SELECT * 
				FROM 		".TAB_BLOCKS." 
				ORDER BY 	".COL_BLOCKS_WEIGHT;
			$blocks = mysql_query($sql, $CONN) or die(mysql_error());
			
			while($block = mysql_fetch_assoc($blocks)){
				if(groups::check($_SESSION[$DEVIL_CMS_INI['USER']['LOGIN']['SESSION']['GROUPS']], navigation::getBlockGroups($block[COL_BLOCKS_ID]))){
					$blockLabels = unserialize($block[COL_BLOCKS_LABEL]);
					$navi['block'.$block[COL_BLOCKS_ID]]['label'] = $blockLabels['en'];
					$navi['block'.$block[COL_BLOCKS_ID]]['position'] = $block[COL_BLOCKS_POS];
					$navi['block'.$block[COL_BLOCKS_ID]]['links'] = navigation::links($block[COL_BLOCKS_ID]);
				}
			}
			return $navi;
		}
		
		function links($blockID){
			global $CONN, $DEVIL_CMS_INI;
			
			$naviLinks = array();
			$sql = "SELECT * 
				FROM 		".TAB_NAVI." 
				WHERE 		".COL_NAVI_BLOCK." = '".sql::escape($blockID)."' 
				ORDER BY 	".COL_NAVI_WEIGHT;
			$links = mysql_query($sql, $CONN) or die(mysql_error());
			
			while($link = mysql_fetch_assoc($links)){
				if(groups::check($_SESSION[$DEVIL_CMS_INI['USER']['LOGIN']['SESSION']['GROUPS']], content::getContentGroups($link[COL_NAVI_CID]))){
					$labels = unserialize($link[COL_NAVI_LABELS]);
					$naviLinks['link'.$link[COL_NAVI_ID]]['label'] = $labels['en'];
					$naviLinks['link'.$link[COL_NAVI_ID]]['cid'] = $link[COL_NAVI_CID];
				}
			}
			return $naviLinks;
		}
		
		function getBlockGroups($id){
			global $CONN;
			
			$groups = array();
			$sql = "SELECT 	".COL_NAVI_CID." 
				FROM 	".TAB_NAVI." 
				WHERE 	".COL_NAVI_BLOCK." = '".sql::escape($id)."'";
			$navis = mysql_query($sql, $CONN) or die(mysql_error());
			
			while($navi = mysql_fetch_assoc($navis)){
				$contentGroups = content::getContentGroups($navi[COL_NAVI_CID]);
				foreach($contentGroups as $contentGroup){
					if(!in_array($contentGroup, $groups)){
						$groups[] .= $contentGroup;
					}
				}
			}
			return $groups;
		}
		
		function updateNaviLinkSorting($naviID, $blockID, $weight){
			global $CONN;
			
			$sql = "UPDATE 	".TAB_NAVI." 
				SET 	".COL_NAVI_WEIGHT."='".sql::escape($weight)."',
					".COL_NAVI_BLOCK."='".sql::escape($blockID)."'
				WHERE 	".COL_NAVI_ID."='".sql::escape($naviID)."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
		
		function updateNaviBlockSorting($blockID, $position, $weight){
			global $CONN;
			
			$sql = "UPDATE 	".TAB_BLOCKS." 
				SET 	".COL_BLOCKS_WEIGHT."	='".sql::escape($weight)."',
					".COL_BLOCKS_POS."='".sql::escape($position)."'
				WHERE 	".COL_BLOCKS_ID."='".sql::escape($blockID)."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
		
		function insertBlock($blockLabels, $blockDesc, $blockPosition){
			global $CONN;
			$sql = "INSERT INTO ".TAB_BLOCKS."	
				(
	 				".COL_BLOCKS_LABEL.", 
					".COL_BLOCKS_DESC.",
					".COL_BLOCKS_POS.", 
					".COL_BLOCKS_WEIGHT.", 
					".COL_BLOCKS_LAST_CHANGE.", 
					".COL_BLOCKS_CREATED."
				)
				VALUES 
				(
	 				'".sql::escape(serialize($blockLabels))."',
					'".sql::escape($blockDesc)."',
					'".sql::escape($blockPosition)."',
					'-1',
					'".time()."',
					'".time()."'
				)";
			mysql_query($sql, $CONN)or die(mysql_error());
			return mysql_insert_id();
		}
		
		function deleteBlock($blockID){
			global $CONN;
			$sql = "DELETE FROM 	".TAB_BLOCKS."
				WHERE 		".COL_BLOCKS_ID." = '".sql::escape($blockID)."'";
			mysql_query($sql, $CONN)or die(mysql_error());
			
			$sql = "DELETE FROM 	".TAB_NAVI." 
				WHERE 		".COL_NAVI_BLOCK." = '".sql::escape($blockID)."'";
			mysql_query($sql, $CONN)or die(mysql_error());
		}
		
		function updateBlock($blockID, $blockLabels, $blockDesc){
			global $CONN;
			$sql = "UPDATE 	".TAB_BLOCKS." 
				SET 	".COL_BLOCKS_DESC."		='".sql::escape($blockDesc)."',
					".COL_BLOCKS_LABEL."		='".sql::escape(serialize($blockLabels))."',
					".COL_BLOCKS_LAST_CHANGE."	='".sql::escape(time())."'
				WHERE 	".COL_BLOCKS_ID."		='".sql::escape($blockID)."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
		
		function insertLink($cid, $labels, $desc, $blockID=0){
			global $CONN;
			$sql = "INSERT INTO 	".TAB_NAVI."
				(
						".COL_NAVI_CID.", 
						".COL_NAVI_DESC.",
						".COL_NAVI_LABELS.", 
						".COL_NAVI_BLOCK.", 
						".COL_NAVI_WEIGHT.", 
						".COL_NAVI_CREATED."
				) 
				VALUES 
				(
						'".sql::escape($cid)."',
						'".sql::escape($desc)."',
						'".sql::escape(serialize($labels))."',
						'".sql::escape($blockID)."',
						'-1',
						'".sql::escape(time())."'
				)";
			mysql_query($sql, $CONN)or die(mysql_error());
			return mysql_insert_id();
		}
		
		function updateLink($linkID, $labels, $desc, $cid){
			global $CONN;
			$sql = "UPDATE 	".TAB_NAVI." 
				SET 	".COL_NAVI_CID."='".sql::escape($cid)."',
					".COL_NAVI_DESC."='".sql::escape($desc)."',
					".COL_NAVI_LABELS."='".sql::escape(serialize($labels))."',
					".COL_NAVI_LAST_CHANGE."='".sql::escape(time())."'
				WHERE 	".COL_NAVI_ID."='".sql::escape($linkID)."'";
			mysql_query($sql, $CONN) or die(mysql_error());
		}
		
		function deleteLink($linkID){
			global $CONN;
			$sql = "DELETE FROM 	".TAB_NAVI." 
				WHERE 		".COL_NAVI_ID." = '".sql::escape($linkID)."'";
			mysql_query($sql, $CONN)or die(mysql_error());
		}
	}
?>
