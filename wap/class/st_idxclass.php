<?php
/*	整站系统类文档 - st_idxclass.php for wap
	作用: 文章栏目列表相关
	修改时间: 2015.4.22
*/
require ('class/webclass.php');

class st_idx extends web{
	
	public $st_id;

	function __construct($id=0){
		parent::__construct('..');
		$this->st_id=(int)(isset($_GET['id']) ? $_GET['id'] : 0);
		if($this->st_id < 0) $this->st_id = 0;
		if($this->st_id > $this->sort_all) $this->st_id = $this->sort_all;
		$this->webname = $this->sort_ar[$this->st_id][0];
	}
  
	public function mk_cont(){//导航页面内容
		$pg = (int)(isset($_GET['pg'] )? $_GET['pg'] : 0);
		$res = "\t\t\t<div class=\"title mid\">".$this->webname."</div>\n";
		$fn = "../art/".$this->sort_ar[$this->st_id][1].".txt";
		if(file_exists($fn)) $s_str=array_reverse(file($fn));
		$total = @count($s_str);
		$pnum = 26;  //每页显示条目
		$res .= "\t\t\t<div class=\"position fright\">".$this->mkpage($total,$pnum,$pg,"&id=$this->st_id")."</div>\n\t\t\t<table id=\"list\" class=\"tab_mid shadow\">\n\t\t\t\t";
		for($i=$pg*$pnum;$i<$pg*$pnum+$pnum;$i++){
			$res .= "\t\t\t\t<tr class=\"".($i%2 == 0 ? "grey" : "dark")."\">\n";
			$k = $i+1; //序号
			if($i<$total){
				@list($st,$a_fn,$a_title,$auth,$show_fn,$create_tm,$sd) = explode("#",$s_str[$i]);
				$sd = trim($sd);
				$res .= "\t\t\t\t\t<td>&nbsp;$k &nbsp;<a title=\" 点击查阅 \" href=\"show.php?st=$st&sd=$sd&art=$a_fn\">".stripslashes($a_title)."</a></td>\n</tr>\n";
			}else{
				$res .= "\n\t\t\t\t\t<td>&nbsp</td>\n\t\t\t\t</tr>\n";
			}
		}
		$res .= "\t\t\t</table>\n";
		$res .= "\t\t\t<div class=\"position fright\">".$this->mkpage($total,$pnum,$pg,"&id=$this->st_id")."</div>\n";
		return $res;
	}

}

?>