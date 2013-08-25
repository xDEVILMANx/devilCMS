<?
	session_start();
	
	require(dirname(__FILE__)."/classes/class.ini.php");
	$DEVIL_CMS_INI = Ini_Struct::parse(dirname(__FILE__)."/configs/devilCMS.ini", true);
	
	
	require($DEVIL_CMS_INI['CMS']['PATH']."/configs/config.db.connect.php");
	require($DEVIL_CMS_INI['CMS']['PATH']."/configs/config.db.php");
	
	
	require($DEVIL_CMS_INI['CMS']['PATH']."/".$DEVIL_CMS_INI['FILES']['CLASSES']['SQL']);
	require($DEVIL_CMS_INI['CMS']['PATH']."/".$DEVIL_CMS_INI['FILES']['CLASSES']['CONTENT']);
	require($DEVIL_CMS_INI['CMS']['PATH']."/".$DEVIL_CMS_INI['FILES']['CLASSES']['NAVIGATION']);
	require($DEVIL_CMS_INI['CMS']['PATH']."/".$DEVIL_CMS_INI['FILES']['CLASSES']['USER']);
	require($DEVIL_CMS_INI['CMS']['PATH']."/".$DEVIL_CMS_INI['FILES']['CLASSES']['GROUPS']);
	require($DEVIL_CMS_INI['CMS']['PATH']."/".$DEVIL_CMS_INI['FILES']['CLASSES']['MODULES']);
	require($DEVIL_CMS_INI['CMS']['PATH']."/".$DEVIL_CMS_INI['FILES']['CLASSES']['LANGUAGES']);

	
	$CONN = sql::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	user::login();
	
	if(isset($_SERVER[HTTP_DEVILCMS_CURRENT_MODULE])){
		$module = modules::getModule($_SERVER[HTTP_DEVILCMS_CURRENT_MODULE]);
		$files = unserialize($module[COL_MODULES_FILES]);
		if(is_array($files)){
			foreach($files as $file){
				require($DEVIL_CMS_INI['CMS']['PATH']."/".$file);
			}
		}
	}
	
	if(isset($_SERVER[HTTP_DEVILCMS_REQUESTED_TYPE]) && isset($_SERVER[HTTP_DEVILCMS_REQUESTED_FILE])){																						// NOT XML
		if(file_exists($_SERVER[HTTP_DEVILCMS_REQUESTED_FILE])){
			require($_SERVER[HTTP_DEVILCMS_REQUESTED_FILE]);
		}
	}
?>
