<?php
/*	整站系统类文档 - idxclass.php for wap
	作用: 首页显示相关
	修改时间: 2015.4.22
*/
require ('class/webclass.php');

class idx extends web{

	function __construct(){
		parent::__construct('.');
	}
  
	public function new_art(){//最新发布
        $fn="../art/newest.txt";
        $msg = "\t\t\t<table style=\"width:100%;\" id=\"list\" class=\"tab_mid shadow\">\n";
        if(is_file($fn)){//库文件存在
            $ar=file($fn);
            for($i=0;$i<15;$i++){
            	$msg .= "\t\t\t\t<tr class=\"".($i%2 == 0 ? "grey" : "dark")."\"><td>";
                if(isset($ar[$i])){
                    @list($st,$ff,$fname,$au,$bb,$creat_tt,$sd)=explode("#",$ar[$i]);
                    $sd = trim($sd);
                    $url =  "show.php?st=$st&sd=$sd&art=$ff";
                    $msg .= "·<a title=\" ".trim($au)." \" href=\"$url\">$fname</a></td></tr>\n";
                }else{
                	$msg .= "&nbsp;</td></tr>\n";
                }
            }
        }
        $msg .= "\t\t\t</table>";
        return $msg."\n";
    }

}

?>