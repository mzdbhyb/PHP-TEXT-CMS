<?php
/*	整站系统类文档 - webclass.php for wap
	作用: 前台显示相关
	修改时间：2015.4.25
*/
class web {

	public $webname, $skin, $top_ad, $art_pl, $sort_ar, $skin_file, $nav_id, $nod_id, $log_in, $log_msg, $ybegin, $sort_all;

	public function __construct($id='.'){
		if(function_exists('date_default_timezone_set')) date_default_timezone_set('PRC');
		require ('../source/webconf.php');
		//require ('../skin/skin.php');
		$this->webname = $webname;
		$this->art_pl = $art_pl;
		$this->skin = $skin;
		$this->top_ad = $top_ad;
		$this->sort_ar = $sort_ar;
		//$this->skin_file = $id.'/skin/'.$skin_ar[$this->skin][1];
		$this->nod_id = $id;
		$this->ybegin = isset($ybegin) ? $ybegin : date('Y');
		$this->sort_all = isset($sort_all) ? $sort_all : 6;//默认6个栏目
		session_start();
		$this->log_in = @$_SESSION['SYS_admin'];
		$this->log_msg = (isset($this->log_in) ? "<a title=\" 进入后台 \" href=\"../admin/logout.php\"><span style=\"color:red;\">登出</span></a>" : "<a title=\" 用户登录 \" href=\"../admin/login.php\">登录</a>");
	}
	

	public function mk_nav($menu_id=0,$sort_id=100){//导航条 - menu_ar可以在其后添加导航项目
		$menu_ar = array(
			array('首页','./'),
			array('主栏目<span></span>','art.php'),
			array('留言薄'.$this->get_pad(),'npad.php'),
		);
		$res = "\n\t\t<ul>\n";
		for($i=0;$i<count($menu_ar);$i++){
			$m_id = $menu_id == $i ? ' id="current" ' : ' ';
			$m_id .= $i == 1 ?  "onclick=\"javascript:var dp=document.getElementById('nn');dp.style.display=(dp.style.display=='block'?'none':'block');\">"  : " href=\"".$menu_ar[$i][1]."\">";
			$res .= "\t\t\t<li><a".$m_id.$menu_ar[$i][0]."</a>";
			if($i == 1) {
				$res .= "\n\t\t\t\t<ul id=\"nn\" class=\"shadow\">\n";
				for($j=0;$j<$this->sort_all;$j++) {
					$res .= "\t\t\t\t\t<li><a ".($j == $sort_id ? "id=\"scurrent\"" : "")." title=\"".$this->sort_ar[$j][0]." \" href=\"art.php?id=".$j."\">".$this->sort_ar[$j][0]."</a></li>\n";
				}
				$res .= "\t\t\t\t</ul>\n\t\t\t</li>\n";
			}else{
				$res .= "</li>\n";
			}
		}
		return $res;
	}

	public function new_comm($dot){//最新评论
		$fn = $this->nod_id."/source/commrec.php";
		if(is_file($fn)) include $fn;
		$comm_tt = isset($comm_tt) ? $comm_tt : 0;
		$res = "<div class=\"left_tt_bg1\">评论资讯 [ 总 $comm_tt 则 ]</div>\n\t\t\t<div class=\"position\">\n";
		$fn = $this->nod_id."/art/cmm_newest.txt";
		if(!is_file($fn)){
			$res .= "\t\t\t\t<p>栏目资讯不存在</p>\n";
		}else{
			$i = 0;
			$fp = fopen($fn,"r");
			while($line = @fgets($fp,1024)){
				if($i<=9){
					@list($st,$art,$name,$show_fn,$sd) = explode('#',$line);
					$sd = trim($sd);
					$res .= "\t\t\t\t".$dot."<a href=\"".$this->nod_id."/art/".($show_fn == "b" ? "bshow.php" : "show.php")."?st=$st&sd=$sd&art=$art\">".stripslashes($name)."</a><br />\n";
				}
				$i ++;
			}
			fclose($fp);
		}
		return $res."\t\t\t</div>\n";
	}

	public function web_count($id=1){//显示网站统计
		echo "\n\t\t\t<div class=\"position\">";
		echo "\n\t\t\t\t".$this->online()."&nbsp; ".$this->log_msg."\n\t\t\t</div>\n";
	}

	public function get_ip($vip='unknown'){//获取ip
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],$vip)){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],$vip)){
			$ip = $_SERVER['REMOTE_ADDR']; 
		}
		if(false !== strpos($ip, ',')) $ip = reset(explode(',', $ip));//多层代理
	 	return $ip;  
	}

	public function online(){//日统计及在线人数
		$fn = "../count.txt"; //访问统计文档
		$file_online = "../online.txt"; //在线统计文档
		$today = date('z');
		$uid = session_id() ? session_id() : $this->get_ip();
		if(!is_file($fn)) $this->make_file($fn,$today."\n");
		$online_ar = is_file($file_online) ? file($file_online) : array($uid."=".time());
		$ar = file($fn);
		if(trim($ar[0]) != $today){//跨日
			$yFile = $this->nod_id."/source/count/".date('Y').".txt";
			$this->make_file($yFile,trim($ar[0])." = ".(count($ar)-1)."\n","a"); //写数据到年库
			$this->make_file($fn,$today."\n".$uid."\n");//重写日库
		}else{//当日
			$this->make_file($fn,$uid."\n","a");
		}
		$ar =file($fn);//刷新数据
		$click = count($ar) - 1;
		$ar = array_unique($ar);//去除数组重复值
		$res= count($ar) - 1;
		$total=count($online_ar);//在线总人数
		$i = 0;
		$add = 0;//是否加入新ID的Flag
		while($i<$total){//统计在线人数
			list($id,$tt) = explode("=",$online_ar[$i]);
			if($id == $uid){//如果ID已存在
				$add = 1;
				$online_ar[$i] = $id."=".time()."\n";//改写时间
			}else{//如果ID不存在
				if(time() - $tt > 600){
					$online_ar[$i] = "";//下线清除
				}
			}
			$i ++;
		}
		if($add != 1) $online_ar[] = $uid."=".time()."\n";
		$online_ar = array_filter($online_ar);//过滤空项
		$this->make_file($file_online,implode("",$online_ar));
		return "<div class=\"position\">IP:".$res."&nbsp;点击:".$click."&nbsp;在线:".count($online_ar);
	}

	public function make_file($file,$str,$m="w") {//保存文件
		if($fp=fopen($file,$m)) {
			$startTime = microtime ();
			do {
				$canWrite = flock ( $fp, LOCK_EX );  
				if(!$canWrite) usleep (round(rand(0,100)*1000));
			} while ((!$canWrite) && ((microtime () - $startTime) < 1000));
			if ($canWrite) { 
				fwrite($fp,$str);
				flock($fp,LOCK_UN);
			}
			fclose($fp);
		}
	}

	public function mkpage($all,$pgnum,$idx,$other="") {//分页
		$tt=($all%$pgnum==0 ? $all/$pgnum : floor($all/$pgnum)+1);
		$pgrtt=($tt%5==0 ? floor($tt/5) : floor($tt/5)+1);
		$pgrid=floor($idx/5);
		$pre5=$pgrid*5-1;
		$behind5=$pgrid*5+5;
		$last=$tt-1;
		$res = ($idx!=0 ? "<a title=\" 返回第一页 \" href=\"".$_SERVER['PHP_SELF']."?pg=0".$other."\">&lt;&lt;</a>&nbsp;" : "<span style=\"color:#888\">&lt;&lt;</span>&nbsp;");
		$res .= ($pgrid!=0 ? "<a title=\" 前面五页 \" href=\"".$_SERVER['PHP_SELF']."?pg=$pre5".$other."\">&lt;</a>&nbsp;" : "<span style=\"color:#888\">&lt;</span>&nbsp;");
		for($j=$pgrid*5;$j<$pgrid*5+5;$j++){
			$jp=$j+1;
			if($j<$tt) $res .= ($j==$idx ? "<span class=\"red\">$jp</span>&nbsp;" : "<a href=\"".$_SERVER['PHP_SELF']."?pg=$j".$other."\">$jp</a>&nbsp;");
		}
		$res .= ($pgrid!=$pgrtt-1 ? "<a title=\" 后面五页 \" href=\"".$_SERVER['PHP_SELF']."?pg=$behind5".$other."\">&gt;</a>&nbsp;" : "<span style=\"color:#888\">&gt;</span>&nbsp;");
		$res .= ($idx!=$tt-1 ? "<a title=\" 最后一页 \" href=\"".$_SERVER['PHP_SELF']."?pg=$last".$other."\">&gt;&gt;</a>&nbsp;" : "<span style=\"color:#888\">&gt;&gt;</span>&nbsp;");
		$res .= "[ 共 $tt 页 总条目 $all ]";
		return $res;
	}

	public function get_pad(){
		$newpad = "";
		$fn = $this->nod_id."../npad/newpad.php";
		if(is_file($fn)){
			include ($fn);
			if($new > 0) $newpad = "<sub>$new</sub>";
		}
		return $newpad;
	}

}

?>
