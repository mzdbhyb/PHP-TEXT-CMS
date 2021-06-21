<?php
/*	整站系统类文档 - idxclass.php
	作用: 首页显示相关
	修改时间: 2014.4.14
*/
require ('admin/class/webclass.php');

class idx extends web{

	function __construct(){
		parent::__construct('.');
	}
  
	public function new_art(){//最新发布
        $fn="./art/newest.txt";
        $msg="<strong> 最新发布</strong><br />";
        if(is_file($fn)){//库文件存在
            $ar=file($fn);
            for($i=0;$i<15;$i++){
                if(isset($ar[$i])){
                    @list($st,$ff,$fname,$au,$bb,$creat_tt,$sd)=explode("#",$ar[$i]);
                    $sd = trim($sd);
                    $url="art/".($bb == "b" ? "bshow.php" : "show.php")."?st=$st&sd=$sd&art=$ff";
                    $msg.="<br />·<a title=\" ".trim($au)." \" href=\"$url\">$fname</a>";
                }
            }
        }else{//不存在
            $msg .= "<p>·尚未发布文章或数据丢失</p>";
        }
        return $msg."\n\t\t\t";
    }
	
	public function web_news($f){//网站公告及首页自定义内容
		return (is_file($f) ? implode("",file($f)) : "<p>&nbsp; ·自定义内容</p>")."\n";
	}

}

?>