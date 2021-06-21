<?php
/*	整站系统类文档 - xdclass.php
	作用: xdiary相关
	修改时间：2014.4.14
*/
require "../admin/class/webclass.php";

class xd extends web{

	public $year, $month,$day, $idx, $content, $zday, $xdiary=0;
	public $ar,$db;

	public function __construct(){
		parent::__construct('..');
		for($i=$this->ybegin;$i<=date('Y');$i++){//读入年库信息
			$fn="data/".$i.".txt";
			if(is_file($fn)) $this->db[$i]=$fn;
		}
		if(isset($_GET['year'])){
			$this->year=(int)$_GET['year'];
			if($this->year<$this->ybegin) $this->year=$this->ybegin;
			if($this->year>date('Y')) $this->year=date('Y');
			$fn="data/".$this->year.".txt";
			if(is_file($fn)){
				$this->ar=file($fn);
				$this->idx=isset($_GET['idx']) ? (int)$_GET['idx'] : 0;
				if($this->idx>=count($this->ar)) $this->idx=count($this->ar)-1;
				if($this->idx<=0) $this->idx=0;
				$this->xdiary=1;
			}
		}else{
			if(isset($this->db)){
				foreach($this->db as $k=>$v) $ar[]=$k;
				$max_id=max($ar);
				$fn=$this->db[$max_id];
				$this->ar=file($fn);
				$this->year=$max_id;
				$this->idx=count($this->ar)-1;
				$this->xdiary=1;
			}
		}
		if($this->xdiary == 1){
			$this->ar=file($fn);
		}else{
			$this->ar[0]="0%%<p class=\"red\">未发现本年度的日记数据 !</p>\n";
			$this->idx=0;
			$this->month=0;
			if(!isset($this->year)) $this->year=date('Y');
		}
		$cont=explode('%%',$this->ar[$this->idx]);
		$this->zday=trim($cont[0]);
		$this->content=trim($cont[1]);
		$z_sec=mktime(0,0,0,1,1,0+$this->year)+$this->zday*86400;
		$this->month=date('n',$z_sec);
		$this->day=date('j',$z_sec);
	}
	
	public function m_xdiary($m){//月第一天日记
		$res=-1;
		$x=date('z',mktime(0,0,0,0+$m,1,0+$this->year));
		$y=$x+date('t',mktime(0,0,0,0+$m,1,0+$this->year))-1;
		for($i=0;$i<count($this->ar);$i++){
			$line_ar=explode('%%',$this->ar[$i]);
			if(trim($line_ar[0])>=$x and trim($line_ar[0])<=$y){
				$res=$i;
				break;
			};
		}
		return $res;
	}

	public function d_xdiary($d){//日历导航:日
		$x=date('z',mktime(0,0,0,0+$this->month,0+$d,0+$this->year));
		if($d == $this->day) $d = "<span class=\"red\">$d</span>";
		if($d == "") $d = "&nbsp;";
		for($i=0;$i<count($this->ar);$i++){
			$line_ar = explode('%%',$this->ar[$i]);
			if(trim($line_ar[0]) == $x){
				$d = $this->xdiary == 0 ? "<b>$d</b>": "<a href=\"".$_SERVER['PHP_SELF']."?year=$this->year&idx=$i\"><b>$d</b></a>";
				break;
			}
		}
		return $d;
	}

	public function show_cal(){//日历
		$cal="<div class=\"left_tt_bg1\"><form>\n<select onchange=\"javascript:window.location=this.options[this.selectedIndex].value\">\n";
		for($i=date('Y');$i>=$this->ybegin;$i--){//==年
			if(isset($this->db[$i])) $cal.="<option value=\"".$_SERVER['PHP_SELF']."?year=$i\"".($i==$this->year ? " selected" :"").">".$i."年</option>\n";
		}
		if($this->xdiary==0) $cal.="<option selected>".$this->year."年</optin>\n";
		$cal.="</select>\n<select onchange=\"javascript:window.location=this.options[this.selectedIndex].value\">\n";//==月
		$mday=array('一','二','三','四','五','六','七','八','九','十','十一','十二');
		for($i=0;$i<12;$i++){
			$j=$i+1;
			$selected=$this->month==$j ? "selected" :"";
			if($this->m_xdiary($j)>-1){
				$id=$this->m_xdiary($j);
				$cal.="<option value=\"".$_SERVER['PHP_SELF']."?year=$this->year&idx=$id\" $selected>".$mday[$i]."月</option>\n";
			}
		}
		$cal.="</select>\n</form></div>\n<table cellspacing=\"0\" cellpadding=\"0\" id=\"xdcont\">\n";
		$cal.="<tr><td class=\"red\">日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td class=\"red\">六</td></tr>\n";//==日
		$mdays=date("t",mktime(0,0,0,0+$this->month,1,0+$this->year));
		$wk1st=date("w",mktime(0,0,0,0+$this->month,1,0+$this->year));
		$trnum=ceil(($mdays+$wk1st)/7);
		for($i=0;$i<$trnum;$i++){
			$cal.="<tr class=\"position\">";
				for($k=0;$k<7;$k++) {
					$tabidx=$i*7+$k;
					$dnum=($tabidx<$wk1st || $tabidx>$mdays+$wk1st-1) ? "" : $tabidx-$wk1st+1;
					$dnum=$this->d_xdiary($dnum);
					$cal.="<td>".$dnum."</td>";
				}
			$cal.="</tr>\n";
		}
		$cal.="</td></tr></table>\n";
		$cal.="<p>".(!isset($_GET['year']) ? " [复位日记]" : "[<a href=\"./\">复位日记</a>]")."</p>\n";
		return $cal;
	}

	public function show_day(){//xd标题日期
		$edit="";
		$wkday=array('日','一','二','三','四','五','六');
		$w=date('w',mktime(0,0,0,0+$this->month,0+$this->day,0+$this->year));
		$res=$this->year."年".$this->month."月".$this->day."日 星期".$wkday[$w];
		if($this->log_in=="master"){
			if(isset($this->ar[$this->idx]) && $this->xdiary==1) $edit.="<a href=\"./edit.php?year=$this->year&idx=$this->idx\">编辑</a>";
			if($this->write()==1) $edit.="&nbsp;<a href=\"write.php\">写日记</a>";
		}
		if($edit!="") $res.=" [".$edit."]";
		return $res;
	}

	public function write(){//能否写日记
		$fn="data/".date('Y').".txt";
		if(!is_file($fn)){
			return 1;
		}else{
			if($this->year == date('Y')){
				$last_line = $this->ar[(count($this->ar)-1)];
				$line = explode("%%",$last_line);
				return ($line[0] == date('z') ? 0 : 1);
			}else{
				$db = file($fn);
				$line = explode("%%",$db[count($db)-1]);
				return ($line[0] == date('z') ? 0 : 1);
			}
		}
	}

	public function xdnav(){//上下则日记导航
		$pre_year = isset($this->db[($this->year-1)]) ? count(file($this->db[$this->year-1]))-1 : 0;
		$pre_id = $this->idx>=1 ? "<a href=\"./?year=$this->year&idx=".($this->idx-1)."\">前一则</a>" : ($this->year < $this->ybegin+1 ? "前一则" : "<a href=\"./?year=".($this->year-1)."&idx=$pre_year\">前一年</a>");
		$next_id = $this->idx<count($this->ar)-1 ? "<a href=\"./?year=$this->year&idx=".($this->idx+1)."\">下一则</a>" : ($this->year < date('Y') ? "<a href=\"./?year=".($this->year+1)."&idx=0\">下一年</a>" : "下一则");
		return $this->year."': ".$pre_id."&nbsp; ".$next_id;
	}

	public function counter(){//日记阅读数
		if($this->xdiary==1){
			$fn = "data/".$this->year;
			if(!is_dir($fn)) mkdir($fn, 0777);
			$fn .= "/".$this->zday.".txt";
			$fp = fopen($fn,"a");
			flock($fp,LOCK_EX);
			fwrite($fp,"1\n");
			flock($fp,LOCK_UN);
			fclose($fp);
			$num = count(file($fn));
			return $num;
		}
	}

	public function show_cmm(){//评论显示及写评论
		if($this->xdiary == 1 && $this->art_pl == 1){
			$fst_num = rand(0,20);//验证算术题
			$sec_num = rand(0,20);
			$cal_ar = array("+","-");
			$cal_do = $fst_num > $sec_num ? $cal_ar[rand(0,1)] : "+";
			$gid = session_id()."%%".($cal_do == "+" ? $fst_num + $sec_num : $fst_num - $sec_num);//访客标识及骓码
			$val_str = $fst_num." ".$cal_do." ".$sec_num." = ？";
			$fn="data/".$this->year."cmm.txt";//评论内容
			if(is_file($fn)){
				$ar = array_reverse(file($fn));
				$j = 0;
				for($i=0;$i<count($ar);$i++){
					$line=explode("#",$ar[$i]);
					if($line[0]==$this->zday){
						$j+=1;
						$del_idx = count($ar) - $i - 1;
						$cmm_ar[]=$j." # ".$line[1].($this->log_in=="master" ? "<a href=\"delcmm.php?year=$this->year&idx=$this->idx&id=$del_idx\"><span class=\"red\">×</span></a>" : "");
					}
				}
			}
			$res = "<a name=\"pl\"></a>".(isset($cmm_ar) ? "<p class=\"position\">评论列表: [ ".count($cmm_ar)." 条 ]</p>\n<blockquote class=\"position shadow\">".implode("<br />",$cmm_ar)."</blockquote>" : "");
			$res .= "</p>\n<p style=\"color: #888;font-size:11pt;\">发表评论:<br /><form action=\"comm.php?year=$this->year&month=$this->month&idx=$this->idx\" method=\"post\" name=\"comm_form\" id=\"comm_form\">
<p style=\"text-align:center;\"><textarea style=\"width:90%;height:70px;padding:6px;border:1px solid #aaa;box-shadow:3px 3px 3px #aaa;\" required=\"true\" placeholder=\"欢迎评论: 30秒间隔限制\" onpropertychange=\"if(value.length>200)value=value.substr(0,200)\" name=\"comm_words\" id=\"comm_words\" maxlength=\"200\"></textarea></p>
<p style=\"text-align:right;\"><label for=\"guest\">姓名: </label><input placeholder=\"Your Name\" required=\"true\" name=\"guest\" id=\"guest\" value=\"\" maxlength=\"10\" />&nbsp;&nbsp;<label for=\"val\">验证码: </label><input id=\"val\" name=\"val\" size=\"6\" value=\"\" onfocus=\"javascript:document.getElementById('val_tip').innerHTML='".$val_str."'\" />&nbsp;<span id=\"val_tip\"></span><input type=\"hidden\" id=\"gid\" name=\"gid\" value=\"$gid\" /> &nbsp;<input type=\"submit\" value=\" 发表 \" id=\"cmdOK\" name=\"cmdOk\" /> &nbsp;</p></form></p>\n";
			return $res;
		}
	}

	public function show_new_cmm(){//最新评论
		$fn="data/newcmm.txt";
		if(is_file($fn)){
			$ar=file($fn);
			$num = count($ar) >= 5 ? 5 : count($ar);
			for($i=0;$i<$num;$i++){
				$line=explode("%%",$ar[$i]);
				$show_ar[]=$line[0];
			}
		}
		return "<p class=\"position\"><b>最新评论</b><br /><br />◇ ".(isset($show_ar) ? implode("<br />◇ ",$show_ar) : "近期没有评论")."</p>\n";
	}

	public function txt2html($txt) {//格式化文本
		$txt=trim($txt);
		$txt=str_replace("\n","-%%-",$txt);
		$txt_ar=explode("-%%-",$txt);
		for($i=0;$i<count($txt_ar);$i++) {
			$txt_ar[$i]=trim($txt_ar[$i]);
			if(substr($txt_ar[$i],0,1)!="<") $txt_ar[$i]="<p>".$txt_ar[$i]."</p>";
		}
		$str="";
		for($i=0;$i<count($txt_ar);$i++) {
			$str.=trim($txt_ar[$i]);
		}
		$str=trim($str);
		$str=str_replace("<p></p>","",$str);//刪除空行
		$str=str_replace("%%","％％",$str);//保护保留字符
		$str=stripslashes($str);
		return $str;
	}

	public function __destruct(){
		unset($this->ar);
		unset($this->db);
		unset($this->content);
	}

}

?>