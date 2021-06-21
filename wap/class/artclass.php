<?php
/*	整站系统类文档 - 文章显示 artclass.php for wap
	作用: 文章显示相关
	修改时间: 2015.4.24
*/
require ('class/webclass.php');

class art extends web{

	public $st, $sd, $art, $art_file, $ad, $ftitle, $author, $contents, $create_tm, $idx;//st:栏目 sd:目录 idx:返回栏目id

	function __construct(){
		parent::__construct('..');
		$this->st = isset($_GET['st']) ? $_GET['st'] : 1;
		for($i=0;$i<$this->sort_all;$i++){
			if($this->sort_ar[$i][1] == $this->st){
				$this->idx = $i;
				break;
			}
		}
		if(!isset($this->idx)) die('error of sort index in this page');
		$this->sd = isset($_GET['sd']) ? $_GET['sd'] : $this->st;//兼管已往文章，兼顾google搜索
		$this->art = isset($_GET['art']) ? $_GET['art'] : "null";
		$this->art_file = "../art/".$this->sd."/".$this->art.".php";
		if(is_file($this->art_file)){
			include ($this->art_file);
			$this->ftitle = stripslashes($ftitle);
			$this->author = stripslashes($author);
			$this->contents = stripslashes($contents);
			$this->webname = $this->ftitle;
			$this->create_tm = $create_tm;
		}
	}

	function mk_cont(){//文章内容
		if(is_file($this->art_file)){
			echo "\n\t\t\t<p class=\"title mid\">".$this->ftitle."</p>\n";
			echo "\t\t\t<div class=\"position\">位置: <a href=\"./\">首页</a> > <a href=\"./art.php?id=".$this->idx."\">".$this->sort_ar[$this->idx][0]."</a><br />[发布: ".$this->create_tm." &nbsp;作者: ".$this->author.$this->counter()."]</div>\n";
			echo "\t\t\t<div class=\"cont\" style=\"padding:10px 10px;font-size:12pt;\">\n\n".$this->contents.$this->art_nav()."\n\t\t\t</div>\n";
			$this->comm();//显示评论模块
			$bs = strstr($_SERVER['PHP_SELF'],"bshow.php")=="bshow.php" ? "b" : "m";
			if(isset($_GET['do']) && $_GET['do'] == "save") $this->sav_comm($bs);//处理提交的评论:常规页面
		}
	}

	public function counter(){//阅读计数
		$numfile = "../art/".$this->sd."c/".$this->art."_c.txt";
		$this->make_file($numfile,"1\n","a");
		return "&nbsp; 阅读: ".count(file($numfile));
	}

	public function art_nav() { //文章导读
		$fn = "../art/".$this->st.".txt";
		$str = "\n\n<p>";
		if(is_file($fn)){
			$ar = file($fn);
			$num = count($ar);
			for($i=0;$i<$num;$i++) {
				@list($s,$a,$t)=explode("#",$ar[$i]);
				if($this->art == $a) {
					if($i==0) {
						$str .= "前一篇: 没有了<br />";
					}else{
						list($ps,$pa,$pt,$pau,$pb,$pct,$psd) = explode("#",$ar[$i-1]);
						$psd = trim($psd);
						$str .= "前一篇: <a href=\"show.php?st=$this->st&sd=$psd&art=$pa\">".stripslashes($pt)."</a><br />";
					}
					if($i==$num-1) {
						$str .= "下一篇: 没有了<br />";
					}else{
						@list($ns,$na,$nt,$nau,$nb,$nct,$nsd) = explode("#",$ar[$i+1]);
						$nsd = trim($nsd);
						$str .= "下一篇: <a href=\"show.php?st=$this->st&sd=$nsd&art=$na\">".stripslashes($nt)."</a><br />";
					}
					break;
				}
			}
		}
		return $str."</p>\n";
	}

	public function comm($limit=6){//文章评论
		$do = @$_GET['do'];
		if($this->art_pl == 1 && $do != 'save'){
			$fst_num = rand(0,20);//验证算术题
			$sec_num = rand(0,20);
			$cal_ar = array("+","-");
			$cal_do = $fst_num > $sec_num ? $cal_ar[rand(0,1)] : "+";
			$gid = session_id()."%%".($cal_do == "+" ? $fst_num + $sec_num : $fst_num - $sec_num);//访客标识+验证码
			$val_str = $fst_num." ".$cal_do." ".$sec_num." = ？";
			if(!isset($_GET['do'])) print "
			<div id=\"comm\">发表评论:<br />
				<form action=\"".$_SERVER['PHP_SELF']."?st=$this->st&sd=$this->sd&art=$this->art&do=save\" method=\"post\" name=\"comm_form\" id=\"comm_form\" onsubmit=\"return isub();\">
					<p style=\"text-align:center\"><textarea style=\"width:90%;height:60px;padding:6px;border:1px solid #aaa;box-shadow:3px 3px 3px #aaa;\" onpropertychange=\"if(value.length>500)value=value.substr(0,500)\" name=\"comm_words\" id=\"comm_words\" required=\"required\" placeholder=\"欢迎评论: 30秒间隔限制\"></textarea></p>
					<p><label for=\"guest\">姓名: </label><input name=\"guest\" id=\"guest\" size=\"8\" value=\"\" maxlength=\"12\" placeholder=\"name\" required=\"required\" />&nbsp;&nbsp;<input type=\"submit\" value=\"发布\" id=\"okey\" name=\"okey\" /><br />
					<label for=\"vnum\">验证码: </label><input id=\"vnum\" name=\"vnum\" size=\"4\" value=\"\" required=\"true\" onfocus=\"javascript:document.getElementById('val').innerHTML='".$val_str."';\"  />&nbsp;<span id=\"val\"></span>
					<input type=\"hidden\" id=\"gid\" name=\"gid\" value=\"$gid\" /></p>
				</form>\n";
			$comm_db_file = "../art/".$this->sd ."c/".$this->art.".txt";//显示评论
			if(file_exists($comm_db_file)) $comm_ar=array_reverse(file($comm_db_file));
			$comm_tt=@count($comm_ar);
			$cmm_ad = file_exists($comm_db_file) ? (($this->is_user()>1) ? " &nbsp;[ <a title=\"进入管理\" href=\"../admin/?id=".$this->is_user()."&act=chk&st=$this->st&art=$this->art\">管理评论</a> &nbsp;<a href=\"../admin/?id=9&st=$this->st&sd=$this->sd&art=$this->art&act=delall\">删除全部</a> ]" : "") : "";
			echo "\t\t\t\t<p id=\"pl\">评论列表 [".$comm_tt."条]".($comm_tt>$limit ? " &nbsp;&nbsp;<a href=\"comm_show.php?st=$this->st&art=$this->art\">全部评论</a>" : "").$cmm_ad."</p>\n";
			if($comm_tt > 0){
				echo "\t\t\t\t<div class=\"shadow\" style=\"background:#efefef;color:#333;padding:8px 6px;font-size:0.8em;\">\n";
				for($i=0;$i<$comm_tt;$i++) {
					if($i<$limit) {
						if(isset($comm_ar)) {
							$comm_play_ar=explode("#",$comm_ar[$i]);
							$idx = $comm_tt - $i - 1;
							$del_str = $this->is_user() > 1 ? "../admin/?id=".$this->is_user()."&st=$this->st&sd=$this->sd&art=$this->art&act=del&idx=$idx" : "";
							if($del_str != "") $del_str = "<span class=\"fright\"><a href=\"".$del_str."\">删除</a></span>";
							echo "\t\t\t\t\t<p>#".($idx+1)." | ".stripslashes($comm_play_ar[0])." 于 ".trim($comm_play_ar[2])." 发布: ".trim($del_str.stripslashes($comm_play_ar[1]))."</p>\n";
						}
					}
				}
				echo "\t\t\t\t</div>\n";
			}
			echo "\t\t\t</div>\n";
		}
	}

	public function sav_comm($bs){//保存评论
		if($this->art_pl == 1){
			$subtime = @$_SESSION['subtime'];
			$vnum = trim(@$_POST['vnum']);
			$uid = session_id();
			list($gid,$val) = explode("%%",$_POST['gid']);
			$val = trim($val);
			$back = $_SERVER['PHP_SELF']."?st=$this->st&sd=$this->sd&art=$this->art&#pl";
			$back = "<script language=\"javascript\">\nwindow.location.href='$back';\n</script>";
			if(time() - $subtime < 30 || $uid != $gid || $val != $vnum){//防刷机制:时间+cookie屏蔽
				echo $back;
			}else{
				$guest = $this->rep_str(@$_POST['guest']);
				if(empty($guest)) $guest = "匿名";
				$comm_words = $this->rep_str(@$_POST['comm_words']);
				if($comm_words !=""){
					$time=date("Y-n-j H:i");
					$comm_words=$guest."#".$comm_words."#".$time."\n";
					$commFile="../art/".$this->sd."c/".$this->art.".txt"; //文章评论库
					$this->make_file($commFile,$comm_words,'a');
					$cmm_rec="../source/commrec.php"; //评论总数
					if(file_exists($cmm_rec)){
						require($cmm_rec);
					}else{
						$comm_tt=0;
					}
					$comm_tt+=1;
					$comm_sav = '<?php $comm_tt='.$comm_tt.'; ?>';
					$this->make_file($cmm_rec,$comm_sav);
					$sav_str = ""; //最新评论
					$newest_file = "../art/cmm_newest.txt";
					$sav_str = $this->st."#".$this->art."#".$guest." - ".$time."#".$bs."#".$this->sd."\n";
					if(file_exists($newest_file)) {
						$old_ar = file($newest_file);
						for($i=0;$i<29;$i++) {//保存30条
							if(isset($old_ar[$i])) $sav_str .= $old_ar[$i];
						}
					}
					$this->make_file($newest_file,$sav_str);
				}
				$_SESSION['subtime'] = time();
				echo $back;
			}
		}
	}

	public function is_user() {//判断会员权限
		if(isset($this->log_in)){
			if($this->log_in == "master"){
				$res = 9;
			}else{
				$uf = "../source/user/".$this->log_in.".php";
				if(is_file($uf)){
					include ($uf);
					$ad = isset($ad) ? $ad : 1;
					if($ad == 1){
						if(strstr($this->art,$this->log_in)) $res = 3;
					}else{
						$res = 9;
					}
				}
			}
		}
		return (isset($res) ? $res : 0);
	}

	public function rep_str($str){//替换函数
		$str = trim(stripslashes($str));
		$str = str_replace("\n","",$str);
		$str = str_replace("#","井",$str);
		$str = strip_tags($str);
		return $str;
	}

}

?>
