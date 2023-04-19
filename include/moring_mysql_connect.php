<?PHP
	class Moring_MySQL{
		protected $moring_mysql_;
		protected $moring_petition_normal_user_mysql_;
		/*function Moring_MySQL_Connect(){
			global $moring_mysql_;
			
			$server = "localhost";         # MySQL/MariaDB 伺服器
			$dbuser = "root";       # 使用者帳號
			$dbpassword = "rg28n"; # 使用者密碼
			$dbname = "moring";    # 資料庫名稱

			try{
				$moring_mysql_ = new PDO(
					"mysql:host=".$server.";dbname=".$dbname.";charset=utf8",
					$dbuser,
					$dbpassword,
					Array(PDO::ATTR_PERSISTENT => true)				
				);
			} catch(PDOException $ex){
				$f = fopen("error_log/mysql_error.log","a+");
				fwrite($f,"Error!: " . $ex->getMessage() . "\n");
				die();
			}
			
		}*/
		
		static function Moring_Creat_MySQL_Connection(&$mysql,$server,$dbuser,$dbpassword,$dbname){
			try{
				$mysql = new PDO(
					"mysql:host=".$server.";dbname=".$dbname.";charset=utf8",
					$dbuser,
					$dbpassword,
					Array(PDO::ATTR_PERSISTENT => true)				
				);
			} catch(PDOException $ex){
				$f = fopen("error_log/mysql_error.log","a+");
				fwrite($f,"Error!: " . $ex->getMessage() . "\n");
				die();
			}
		}
		
		function Moring_MySQL_Connect(){
			global $moring_mysql_;
			Moring_MySQL::Moring_Creat_MySQL_Connection($moring_mysql_, "192.168.2.200", "petitio1_root", "rg28n", "petitio1_ntnu");
		}
		
		function Moring_Petition_Normal_User(){
			global $moring_petition_normal_user_mysql_;
			Moring_MySQL::Moring_Creat_MySQL_Connection($moring_petition_normal_user_mysql_, "192.168.2.200", "petitio1_normalUser", "waliCi", "petitio1_ntnu");
		}
		
		function Moring_MySQL_Close(){
			global $moring_mysql_;
			$moring_mysql_ = null;
		}
		
		function Moring_MySQL_Query_Executer(&$mysql){
			//$mysql->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			switch(func_num_args()){
				case 2:
					$sth = $mysql->prepare(func_get_arg(1));
					$sth->execute();
					return $sth;
				case 3:
					$sth = $mysql->prepare(func_get_arg(1));
					$sth->execute(func_get_arg(2));
					return $sth;
				default:
					return false;
			}
		}
		
		function Moring_MySQL_Normal_User_Query(){
			global $moring_petition_normal_user_mysql_;
			switch(func_num_args()){
				case 1:
					$sth = $moring_petition_normal_user_mysql_->prepare(func_get_arg(0));
					$sth->execute();
					return $sth;
				case 2:
					$sth = $moring_petition_normal_user_mysql_->prepare(func_get_arg(0));
					$sth->execute(func_get_arg(1));
					return $sth;
				default:
					return false;
			}
		}
		
		function Moring_MySQL_Query(){
			global $moring_mysql_;
			switch(func_num_args()){
				case 1:
					$sth = $moring_mysql_->prepare(func_get_arg(0));
					$sth->execute();
					return $sth;
				case 2:
					$sth = $moring_mysql_->prepare(func_get_arg(0));
					$sth->execute(func_get_arg(1));
					return $sth;
				default:
					return false;
			}
		}
	}
?>