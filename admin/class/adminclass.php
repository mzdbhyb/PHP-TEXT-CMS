<?php
/*	整站系统类文档 - adminclass.php
	作用: 后台管理相关
	修改时间: 2014.5.6
*/
class admin{
	public $item_ar = array(
		array('网站设置','网站公告设置','首页自定义内容','友情链接设置','会员管理','高级编辑',), //综合设置 6
		array('发布文章','文章管理','文章评论管理','上传文件','上传文件管理',),//基本功能 5
		array('个人修改资料',),//个人资料 1
	);	
	
	public $user, $user_name, $id, $ad, $webname, $skin, $top_ad, $art_pl, $sort_ar, $ybegin, $sort_all;
	public $version = "<a href=\"http://gxblk.byethost11.com\">马黑整站系统v1.8</a>";
	
	public function __construct(){
		session_start();
		$this->user = @$_SESSION['SYS_admin'];
		if(!isset($this->user)) die('login error');
		if(function_exists('date_default_timezone_set')) date_default_timezone_set('PRC');
		$uf = "../source/user/".$this->user.".php";
		is_file($uf) ? include $uf : die('config file not fuond');
		$this->ad = $this->user == 'master' ? 0 : (isset($ad) ? $ad : 1);//权限级别：0最大
		$this->user_name = $logname;
		$this->id = isset($_GET['id']) ? $_GET['id'] : 0;
		if($this->id < 0 || $this->id>12) $this->id=0;
		require ("../source/webconf.php");
		$this->webname = $webname;
		$this->skin = $skin;
		$this->top_ad = $top_ad;
		$this->art_pl = $art_pl;
		$this->sort_ar = $sort_ar;
		$this->ybegin = isset($ybegin) ? $ybegin : date('Y');
		$this->sort_all = isset($sort_all) ? $sort_all : 6;//默认6个栏目
	}
	
	public function mk_items(){//操作项目
		$j = 0;
		$res = "\t\t\t[ <a href=\"../\"><span class=\"red\">返回首页</span></a> ]<br /><br />\n\t\t\t· ".($this->id == 0 ? "帮助" : "<a href=\"./\">帮助</a>");
		for($i=$this->ad;$i<count($this->item_ar);$i++){
			for($k=0;$k<count($this->item_ar[$i]);$k++){
				$j ++;
				$res .= "<br />\n\t\t\t· ".($j == $this->id ? trim($this->item_ar[$i][$k]) : "<a href=\"./?id=$j\">".trim($this->item_ar[$i][$k])."</a>");
			}
		}
		$res .= "<br />\n\t\t\t · <a href=\"../source/count/\">查看网站统计</a><br /><br />\n\t\t\t· <a href=\"./logout.php\"><span class=\"red\">安全退出</span></a><br /><br />\n\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;[ 登录用户: ".$this->user_name." ]\n";
		return $res;
	}
	
	public function mk_cont(){//操作项目内容
		$id = $this->ad == 0 ? $this->id : ($this->id > 0 ? $this->id + 6 : 0);
		switch($id) {
			case 0: echo $this->help(); break;//帮助
			case 1: echo $this->webset(); break; //网站设置
			case 2: echo $this->write(2); break;//网站公告
			case 3: echo $this->write(3); break;//首页自定义内容
			case 4: echo $this->ed_lnk();	break;//友情链接
			case 5: echo $this->user_ad(); break;//会员管理
			case 6: echo $this->su_edit(); break;//高级编辑
			case 7: echo $this->write(); break;//发布文章
			case 8: echo $this->art_ad(); break;//文章管理
			case 9: echo $this->cmm_ad(); break;//文章评论管理
			case 10: echo $this->upfile(); break;//上传文件
			case 11: echo $this->upload_ad(); break;//上传文件管理
			case 12: echo $this->edit_name(); break;//修改个人资料
		}
	}

	public function help(){//帮助
		return "\t\t<b>登录者身份: ".($this->user == 1 ? "站长" : "会员")."级别</b>
		<p>&nbsp;&nbsp;&nbsp; 您的权限全部罗列在左边窗格里，所有带“·”的项目为网站综合管理及其他功能模块的链接，您可以根据链接的字面含义和需要选择操作入口。<span class=\"red\">操作过程中请不要手动修改地址栏尾部！</span>祝您愉快！</p>
		<p>&nbsp;&nbsp;&nbsp; 感谢您使用马黑整站文章管理系统！了解更多的马黑相关作品和其他资讯，请关注如下网站：<p>
		<blockquote>
			·<a target=\"_blank\" href=\"http://gxblk.ks8.ru\">马黑整站系统</a><br />
			·<a target=\"_blank\" href=\"http://txtphp.500yun.com/\">马黑整站系统静态版</a><br />
			·<a target=\"_blank\" href=\"http://xdiary.gb7.ru\">XDiary之家</a><br />
			·<a target=\"_blank\" href=\"http://mahei.px6.ru/\">HTML5 Trial</a><br />
			·<a target=\"_blank\" href=\"http://gxblk.blog.163.com/\">黑草地</a><br />
			·<a target=\"_blank\" href=\"http://howfile.com/ls/cf8bd880/\">howfile网盘</a><br />
		</blockquote>
		<p align=\"right\"><br /><br />马黑 2014.4.17</p>\n";
	}

	public function write($id=0){
		$sav = @$_GET['sav'];
		if(isset($_GET['act']) && $_GET['act'] == 'edit') $id = 1;//编辑文章
		switch($id){//$id: 0-写新文章；1-编辑文章；2-网站公告；3-首页自定义内容
			case 1: $ht = "编辑文章";
			$st = @$_GET['st'];
			$sd = @$_GET['sd'];
			$art = @$_GET['art'];
			$fn = "../art/".$sd."/".$art.".php";
			include $fn; $edit_cont = htmlspecialchars(str_replace("\'","'",$contents));
			break;
			case 2: $ht = "网站公告设置"; $fn = "../source/webnews.txt"; $edit_cont = @implode("",file($fn)); break;
			case 3: $ht = "首页自定义内容设置"; $fn = "../source/index.txt"; $edit_cont = @implode("",file($fn)); break;
			default: $ht = "写新文章"; $edit_cont = "";
		}
		$res = "\t\t<h3>$ht</h3>\n";
		if($sav != 'sav'){//编辑文档
			$res .= "\n<script language=\"javascript\" src=\"xheditor/jquery.js\"></script>\n<script language=\"javascript\" src=\"xheditor/xheditor.js\"></script>\n<script language=\"javascript\">\n$(pageInit);
function pageInit(){\n\t$('#elm1').xheditor({linkTag:true, internalScript:true, tools:'Source,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,Align,List,Outdent,Indent,Link,Unlink,Anchor,Img,Flash,Media,Hr,Emot,Table,Preview,About', upImgUrl:\"upload.php\",upImgExt:\"jpg,jpeg,gif,png\",onUpload:insertUpload,emotMark:true,urlType:'rel'});\n}
function insertUpload(arrMsg){\n\tvar i,msg;\n\tfor(i=0;i<arrMsg.length;i++){\n\t\tmsg=arrMsg[i];\n\t\t$(\"#uploadList\").append('<option value=\"'+msg.id+'\">'+msg.localname+'</option>');\n\t}\n}\n</script>\n\t\t\t<form action=\"./?id=$this->id&sav=sav".($id == 1 ? "&act=edit&st=$st&sd=$sd&art=$art" : "")."\" name=\"artform\" method=\"post\">\n";
			if($id < 2){
				$res .= "\t\t\t\t<div id=\"etop\">\n\t\t\t\t\t<label for=\"title\">标题: </label>\n\t\t\t\t\t<input value=\"".(isset($ftitle) ? $ftitle : "")."\" name=\"title\" id=\"title\" size=\"50\" /> &nbsp;栏目：\n\t\t\t\t\t<select size=\"1\" name=\"sort\" id=\"sort\">\n";
				for($i=0;$i<$this->sort_all;$i++){
					$res .= "\t\t\t\t\t\t<option ".(isset($st) && $st == $this->sort_ar[$i][1] ? "selected" : "")." value=\"$i\"> ".$this->sort_ar[$i][0]." </option>\n";
				}
				$res .= "\t\t\t\t\t</select>\n\t\t\t\t\t<label for=\"author\">作者: </label><input name=\"author\" id=\"author\" size=\"20\" value=\"".(isset($author) ? $author : $this->user_name)."\" />&nbsp;\n\t\t\t\t\t<input type=\"checkbox\" name=\"bshow\" id=\"bshow\"".(isset($bshow) && $bshow == "b" ? " checked" : "")." value=\"ON\" /> 大页面\n\t\t\t\t</div>\n";
			}
			$res .= "\t\t\t\t<textarea id=\"elm1\" name=\"content\" style=\"width:900px;height:450px;\">\n\n$edit_cont\n\n\t\t\t\t</textarea>\n\t\t\t\t<p class=\"mid\"><input type=\"submit\" value=\" 发布 \" name=\"sub\" /></p>\n\t\t\t</form>\n";
		}else{//保存
			if($id<2){//发布与编辑文章
				$newest_file = "../art/newest.txt";//最新更新库
				if(is_file($newest_file)) $newest_ar = file($newest_file);
				if(!isset($art)) $art = ($this->user=="master" ? time() : $this->user."_".time());//主文档
				$bshow = isset($_POST['bshow']) ? "b" : "m";//大页面与否
				$sort = $_POST['sort'];//栏目序号
				$create_tm = isset($create_tm) ? $create_tm : date('Y.n.j');//发布时间
				$title = $this->str_rep($_POST['title']);
				$author = $this->str_rep($_POST['author']);
				$a_str = $this->str_rep($_POST['content']);
				if(empty($title)) $title = "文章标题未命名";
				$savestr = "<?php\n$"."sf=\"".$art."\";\n";//PHP起始符+文章
				$savestr .= "$"."ftitle=\"".$title."\";\n";// 标题
				$savestr .= "$"."author=\"".$author."\";\n";//作者
				$savestr .= "$"."bshow=\"".$bshow."\";\n";
				$savestr .= "$"."create_tm=\"".$create_tm."\";\n";//发布时间
				$savestr .= "$"."contents=\"".$a_str."\";\n?>";
				if($id == 0){//写新文章
					$st = $this->sort_ar[$sort][1];
					$sd = $this->sort_ar[$sort][2];
					$this->mk_dir("../art/".$sd);
					$this->mk_dir("../art/".$sd."c");
					$fn = "../art/".$sd."/".$art.".php";
					$line_msg = $st."#".$art."#".$title."#".$author."#".$bshow."#".$create_tm."#".$sd."\n";//新的库字串
					$this->savfile("../art/".$st.".txt",$line_msg,"a");//保存到目录库
					if(count($newest_ar) > 30) unset($newest_ar[30]);
					$this->savfile($newest_file,$line_msg.implode("",$newest_ar));//保存到最新发布库
				}else{//修改文章
					$new_str = "";
					$sort_file = "../art/".$st.".txt";//目录库
					$s_ar = file($sort_file);
					if($this->sort_ar[$sort][1] == $st){//未改变类别
						$line_msg = $st."#".$art."#".$title."#".$author."#".$bshow."#".$create_tm."#".$sd."\n";//新的库字串
						for($i=0;$i<count($s_ar);$i++){//调整库记录
							if(!strstr($s_ar[$i],$art) && !empty($s_ar[$i])) $new_str .= trim($s_ar[$i])."\n";
						}
						$new_str .= $line_msg;
						$this->savfile($sort_file,$new_str);
					}else{//改变了类别
						unlink($fn);//移除原文章
						$new_sd = $this->sort_ar[$sort][2];
						$this->mk_dir("../art/".$new_sd);
						$this->mk_dir("../art/".$sd."c");
						$fn = "../art/".$new_sd."/".$art.".php";
						$line_msg = $this->sort_ar[$sort][1]."#".$art."#".$title."#".$author."#".$bshow."#".$create_tm."#".$new_sd."\n";//新的库字串
						$this->savfile("../art/".$this->sort_ar[$sort][1].".txt",$line_msg,"a");//保存到新的目录库
						for($i=0;$i<count($s_ar);$i++){//清除库记录
							if(!strstr($s_ar[$i],$art) and !empty($s_ar[$i])) $new_str .= trim($s_ar[$i])."\n";
						}
						$new_str == "" ? unlink($sort_file) : $this->savfile($sort_file,$new_str);
						$cmm_file = "../art/".$sd."c/".$art.".txt";//文章评论库
						if(is_file($cmm_file)) rename($cmm_file,"../art/".$new_sd."c/".$art.".txt");
						$old_sf="../art/".$sd."c/".$art."_c.txt";//文章阅读统计
						$new_sf="../art/".$new_sd."c/".$art."_c.txt";
						if(is_file($old_sf)) rename($old_sf,$new_sf);
						$new_cmm_file = "../art/cmm_newest.txt";//最新评论库
						if(is_file($new_cmm_file)){
							$newcmm_ar = file($new_cmm_file);
							$cp_line = $st."#".$art;
							$sav_str="";
							for($i=0;$i<count($newcmm_ar);$i++){
								if(strstr($newcmm_ar[$i],$cp_line)){
									list($a,$b,$c,$d,$e) = explode("#",$newcmm_ar[$i]);
									$sav_str .= $this->sort_ar[$sort][1]."#".$art."#".$c."#".$d."#".$new_sd."\n";
								}else{
									$sav_str .= trim($newcmm_ar[$i])."\n";
								}
							}
							$this->savfile($new_cmm_file,$sav_str);
						}
						$sd = $new_sd;//目标目录已改变
					}
					$new_str = "";//调整最新发布库
					for($i=0;$i<30;$i++){
						if(!strstr($newest_ar[$i],$art) && !empty($newest_ar[$i])) $new_str .= trim($newest_ar[$i])."\n";
					}
					$new_str = $line_msg.$new_str;
					$this->savfile($newest_file,$new_str);
				}
				$this->savfile($fn,$savestr);//保存文章
				$this->dostat();//刷新文章统计
				$a_file = ($bshow == "b" ? "bshow.php" : "show.php");
				$res .= "<blockquote><br />文章发布成功！查阅：<a href=\"../art/$a_file?st=".$this->sort_ar[$sort][1]."&sd=$sd&art=$art\">".$title."</a></blockquote>";
			}else{//公告等项目
				$a_str = stripslashes($_POST['content']);
				$this->savfile($fn,$a_str);
				$res .= "<blockquote><br />".$ht."操作完成！返回查看: <a href=\"./?id=$this->id\">".stripslashes($ht)."</a></blockquote>";
			}
		}
		return $res;
	}

	public function su_edit(){//高级编辑
		$res = "\t\t<h3>超级编辑器</h3>\n";
		$act = @$_GET['act'];
		switch($act){
			case 'edit':
				$ef = @$_POST['ef'];
				$cont = is_file($ef) ? implode("",file($ef)) : "文件 $ef 不存在 可以新建";
				$cont = str_replace("<textarea","<*textarea",$cont);
				$cont = str_replace("</textarea","<*/textarea",$cont);
				$res .= "\t\t文档: $ef<br /><br /><span class=\"red\">（内容中若发现 textarea 前加 * 属正常现象，可不修改）</span><br />\n\t\t<form action=\"./?id=$this->id&act=save&ef=$ef\" name=\"eform\" method=\"post\">\n\t\t\t<textarea style=\"padding:10px;border:1px solid #ccc;width:90%;height:450px;box-shadow:4px 4px 4px #aaa;\" name=\"words\" id=\"words\">".$cont."</textarea>\n\t\t\t<p class=\"right\"><input type=\"submit\" value=\" 保存 \" name=\"art_edit\" />&nbsp; <input type=\"button\" value=\" 放弃 \" name=\"goback\" onclick=\"javascript:history.go(-1)\">\n\t\t</form>\n";
				break;
			case 'save':
				$ef = $_GET['ef'];
				$words = stripslashes($_POST['words']);
				$words = str_replace("<*textarea","<textarea",$words);
				$words = str_replace("<*/textarea","</textarea",$words);
				$this->savfile($ef,$words);
				$res .= "<blockquote><br />文档 $ef 已经保存！请 <a href=\"./?id=$this->id\">返回超级编辑页面</a></blockquote>";
				break;
			default:
				$res .= "\t\t<form action=\"./?id=$this->id&act=edit\" name=\"wnform\" method=\"post\">\n\t\t\t\t<p><label for=\"file\">请输入要编辑的文档路径和文件名(例如：../go.php 或 ../art/1.txt)：</label></p><input id=\"ef\" name=\"ef\" size=\"65\" />\n\t\t\t<p>支持文本文件，如.php、.htm、.dat、.txt等 &nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\" 打开 \" name=\"wn_open\" /></p>\n\t\t</form>\n";
				break;
		}
		return $res;
	}

	public function edit_name(){//修改个人资料
		$act = @$_GET['act'];
		$fn = "../source/user/".$this->user.".php";
		$res = "\t\t<h3>个人资料设置</h3>\n";
		switch($act) {
			case 'save':
				$u_name = isset($_POST['u_name']) ? trim($_POST['u_name']) : $logname;
				$u_pass= isset($_POST['u_pass']) ? trim($_POST['u_pass']) : $logpass;
				$u_msg="<?php\n\n\$logname=\"$u_name\";\n\$logpass=\"$u_pass\";\n\n?>";
				$this->savfile($fn,$u_msg);
				$fn="../source/u_list.txt";//用户列表
				$user_ar=file($fn);
				for($i=0;$i<count($user_ar);$i++){
					$comp_ar=explode(" = ",$user_ar[$i]);
					if($this->user == trim($comp_ar[0])) $user_ar[$i]=$this->user." = ".$u_name."\n";
				}
				$u_msg=implode("",$user_ar);
				$this->savfile($fn,$u_msg);
				$res .= "<blockquote><br />个人资料修改！请 <a href=\"./?id=$this->id\">返回查看修改结果</a></blockquote>";
				break;
			default:
				include $fn;
				$res .= "\t\t<form name=\"user_edit\" method=\"post\" action=\"./?id=$this->id&act=save\">
			<blockquote><label for=\"u_name\">账号: </label><input name=\"u_name\" id=\"u_name\" value=\"$logname\" size=\"40\" maxlength=\"16\" /> [ 支持中文 ]
			<br /><br /><label for=\"u_pass\">密码: </label><input name=\"u_pass\" id=\"u_pass\" value=\"$logpass\" size=\"40\" maxlength=\"16\" /> [ 支持大小写英文及阿拉伯数字 ]</blockquote>
			<p class=\"mid\"><input type=\"submit\" value=\" 提交修改 \" /></p>\n\t\t</form>\n";
				break;
		}
		return $res;
	}

	public function ed_lnk() {//友情链接
		$act = @$_GET['act'];
		$fn = "../source/link.txt";
		$res = "\t\t<h3>友情链接设置</h3>\n";
		switch($act){
			case 'save':
				$href_str="";
				for($i=0;$i<10;$i++){
						$href[$i]=$_POST['href'][$i];
						if(strstr($href[$i],"#")){
							$href_str.=trim($href[$i])."\n";
						}
				}
				$href_str=stripslashes($href_str);
				if($href_str != ""){
					$this->savfile($fn,$href_str);
					$res .= "\t\t<blockquote><br />友情链接设置完毕！请 <a href=\"./?id=$this->id\">返回查看结果</a></blockquote>\n";
				}
				break;
			default:
				if(is_file($fn)) $href_ar = file($fn);
				$res .= "\t\t<p>格式：<span class=\"red\">网址#网站名称</span>&nbsp;（注意: 网址和网站名称须完整，不要使用小角引号，不要用保留符号“#”）<br /><br />举例：<span class=\"red\">http://www.gxblk.com#马黑在线动力</span></p>\n\t\t<form method=\"post\" action=\"./?id=$this->id&act=save\">\n";
				for($i=0;$i<10;$i++){
					$res .= "\t\t\t<p>".($i<count($href_ar) ? "<input name=\"href[$i]\" size=\"70\" value=\"".trim($href_ar[$i])."\" />" : "<input name=\"href[$i]\" size=\"70\" value=\"\">")."</p>\n";
				}
				$res .= "\t\t\t<p class=\"mid\"><input type=\"submit\" value=\" 保存 \" name=\"sub\"></p>\n\t\t</form>\n";
				break;	
		}
		return $res;
	}

	public function webset() {//网站设置
		$act = @$_GET['act'];
		$res = "\t\t<h3>网站设置</h3>\n";
		$btfile = "../source/foot.php";
		switch($act){
			case 'save'://保存设置
				$art_pl = isset($_POST['art_pl']) ? 1 : 0;
				$yb_msg = (int)$_POST['ybegin'];
				if($yb_msg <= 0) $yb_msg = date('Y');
				if($yb_msg > date('Y')) $yb_msg = date('Y');
				$top_ad = trim($_POST['top_exp']) != "" ? $this->str_rep(trim($_POST['top_exp'])) : "马黑PHP文章管理系统";
				$webname = trim($_POST['webname']) != "" ? $this->str_rep(trim($_POST['webname'])) : "马黑整站系统";
				$xdskin = trim($_POST['xdskin']) != "" ? trim($_POST['xdskin']) : 0;
				$st_all = (int)$_POST['st_all'];
				$sort_str =  "\$sort_ar=".stripslashes($_POST['st_ar']);
				$t_str = "<?php\n\n\$webname=\"".$webname."\";\n\$skin=".$xdskin.";\n\$top_ad=\"".$top_ad."\";\n\$art_pl=".$art_pl.";\n\$sort_all=".$st_all.";\n".$sort_str."\n\$ybegin=".$yb_msg.";\n\n?>\n";
				$this->savfile("../source/webconf.php",$t_str);
				$btmsg = str_replace("<br />","",trim($_POST['btmsg1']))."<br />".str_replace("<br />","",trim($_POST['btmsg2']));
				$btmsg = "<?php \$bottom = \"".$this->str_rep($btmsg)."\"; ?>";
				$this->savfile($btfile,$btmsg);
				$this->dostat();//栏目变化需要更新文章统计
				$res .= "<blockquote><br />网站设置操作完毕！请 <a href=\"./?id=$this->id\">返回查看效果</a></blockquote>";
				break;
			default:
				$skin_str = "";
				include "../skin/skin.php";
				for($i=0;$i<count($skin_ar);$i++){
   					$skin_str.= ($this->skin==$i ? "<option value=\"$i\" selected> ".$skin_ar[$i][0]." </option>" : "<option value=\"$i\"> ".$skin_ar[$i][0]." </option>");
				}
				include $btfile;
				$btmsg = explode("<br />",$bottom); //底部信息
				$pl_str = ($this->art_pl==1 ? "checked" : "");
				$res .= "\t\t<form method=\"post\" action=\"./?id=$this->id&act=save\">
			<label for=\"webname\"><b>网站名称</b>：</label><input id=\"webname\" name=\"webname\" size=\"20\" value=\"$this->webname\" /><br /><br />
			<strong>网站风格</strong>: <select size=\"1\" id=\"xdskin\" name=\"xdskin\">$skin_str</select> &nbsp;&nbsp; 
			<strong>开放文章评论功能: </strong><input type=\"checkbox\" id=\"art_pl\" name=\"art_pl\" value=\"on\" $pl_str /><br /><br />
			<label for=\"ybegin\"><strong>创建时间</strong>：</label><input id=\"ybegin\" name=\"ybegin\" size=\"20\" value=\"".(isset($this->ybegin) ? $this->ybegin : date('Y'))."\" /><br /><br />
			<strong>栏目设置:</strong>
			<input type=\"hidden\" id=\"st_ar\" name=\"st_ar\" value=\"\" />
			<fieldset>
				<legend>栏目总数: <select name=\"st_all\" id=\"st_all\" onclick=\"mk_sort_js(this.value);\">";
		for($i=4;$i<9;$i++){
			$res .= "\n\t\t\t\t<option value=\"$i\"".($i == $this->sort_all ? " selected=selected" : "").">&nbsp;$i&nbsp;</option>";
		}
			$res .= "\n\t\t\t\t</select></legend>\n\t\t\t\t<div id=\"sort_area\"></div>\n\t\t\t</fieldset>\n\t\t\t<br /><label for=\"top_exp\"><strong>顶部装饰</strong> 请用html代码实现，高宽设定: style=\"width:100%;height:100px;\" (高度应大于等于100px)</label>
			<br /><br /><input type=\"text\" id=\"top_exp\" name=\"top_exp\" size=\"100\" value=\"".$this->str_rep2($this->top_ad)."\" /><br /><br />
			<strong>底部信息</strong>（内容分两行填写，它们将显示在站点网页的底部，支持HTML）:<br />
			第 一 行：<br /><textarea type=\"text\" id=\"btmsg1\" name=\"btmsg1\" rows=\"3\" cols=\"65\">$btmsg[0]</textarea><br />
			第 二 行：<br /><textarea type=\"text\" id=\"btmsg2\" name=\"btmsg2\" rows=\"2\" cols=\"65\">$btmsg[1]</textarea><br /><br />
			<p class=\"mid\"><input title=\" 提交保存 \" type=\"submit\" value=\" 保存设置 \" id=\"sub\" name=\"sub\" onclick=\"set_sAr();\" /></p>\n\t\t</form>
<script language=\"javascript\">

function mk_sort_js(num){
	str = '<table><tr><td valign=\"top\"><select onclick=\"x=this.options.selectedIndex;setButton();\" id=\"sort_opt\" name=\"sort_opt\" size=' + num + '>';
	str_hid = '';";
	$sort_str = "\n\tsort_str = [";
	for($i=0;$i<8;$i++){//用 $this->sort_all 不能全部赋值所以用 8
		$tstr = $i + 1;
		if(!isset($this->sort_ar[$i])) $this->sort_ar[$i] = array("未命名",$tstr,$tstr);
		$sort_str .= "'".$this->sort_ar[$i][0]." - ".$this->str_rep2($this->sort_ar[$i][1])." - ".$this->str_rep2($this->sort_ar[$i][2])."',";
	}
	$sort_str .= "];";
	$res .= $sort_str."
	for(i=0;i<num;i++){ str += (i==0 ? '<option selected=true>' : '<option>') + sort_str[i] + '</option>'; }
	str += \"</select></td><td valign='top'>\" + str_hid + \" <input id='up' type='button' value=' ↑ ' onclick='mvup();' /><br /><input title=' 设置栏目 ' id='rename' type='button' value='栏目' onclick='re_name();' /><br /><input title=' 设置目录 ' id='rename_dir' type='button' value='目录' onclick='re_name_dir();' /><br /><input id='down' type='button' value=' ↓ ' onclick='mvdown();' /></td></tr></table>  \";
	document.getElementById('sort_area').innerHTML = str;
}

all = document.getElementById('st_all').value;
mk_sort_js(all);
var x = document.getElementById('sort_opt').selectedIndex;

function mvup(){
	var y = document.getElementById('sort_opt');
	var ss = y.options[x].text;
	y.options[x].text = y.options[x-1].text;
	y.options[x-1].text = ss;
	x --;
	y.selectedIndex = x;
	setButton();
}

function mvdown(){
	var y = document.getElementById('sort_opt');
	var ss = y.options[x].text;
	y.options[x].text = y.options[x+1].text;
	y.options[x+1].text = ss;
	x ++;
	y.selectedIndex = x;
	setButton();
}

function setButton(){
	document.getElementById('up').disabled = x > 0 ? false : true;
	document.getElementById('down').disabled = x < all-1 ? false : true;
	set_sAr();
}

function re_name(){
	var y = document.getElementById('sort_opt');
	var arr = y.options[x].text.split('-');
	s_name = arr[0].replace(/^\s+|\s+$/g, '');
	var str = prompt('请输入栏目名称——',s_name);
	if(str == null || str == '') return;
	y.options[x].text = str.replace(/^\s+|\s+$/g, '') + ' - ' + arr[1].replace(/^\s+|\s+$/g, '') + ' - ' + arr[2].replace(/^\s+|\s+$/g, '');
	document.getElementById('T' + x).value = y.options[x].text;

}

function re_name_dir(){
	var y = document.getElementById('sort_opt');
	var arr = y.options[x].text.split('-');
	d_name = arr[2].replace(/^\s+|\s+$/g, '');
	var str = prompt('请输入目录名（只能用小写字母和阿拉伯数字）——',d_name);
	if(str == null || str == '') return;
	y.options[x].text = arr[0].replace(/^\s+|\s+$/g, '') + ' - ' + arr[1].replace(/^\s+|\s+$/g, '') + ' - ' + str.replace(/^\s+|\s+$/g, '');
	document.getElementById('T' + x).value = y.options[x].text;
}

function set_sAr(){
	var y = document.getElementById('sort_opt');
	var tstr = 'array(\\n';
	for(i=0;i<y.options.length;i++){
		temp = y.options[i].text.split(' - '); 
		tstr += '\\tarray(\"' + temp[0] + '\",' + temp[1] + ',\"' + temp[2] +'\"),\\n';
	}
	tstr += ');\\n';
	document.getElementById('st_ar').value = tstr;
}

setButton();
</script>\n";
				break;
		}
		return $res;
	}

	public function user_ad(){//会员管理
		$res = "\t\t<h3>会员管理</h3>\n";
		$ulst = "../source/u_list.txt";
		if(!is_file($ulst)) $this->savfile($ulst,'');
		$ar = file($ulst);
		$act = @$_GET['act'];
		$user = @$_GET['user'];
		if(isset($user)){
			$uf = "../source/user/".$user.".php";
			if($this->user == $user) die($res."<blockquote>管理员不能在此变更自己的账号！请 <a href=\"javascript:history.go(-1);\">返回设置页面</a>!</blockquote>");
			if(!is_file($uf)) die($res."<blockquote><br />账号 $user 不存在！请 <a href=\"./?id=$this->id\">返回重新设置</a></blockquote>");
		}
		switch($act){
			case 'save':
				$u_id = isset($user) ? $user : trim($_POST['u_id']);
				if(ctype_alnum($u_id)!=1) die($res."<blockquote>错误ID号, 请 <a href=\"javascript:history.go(-1);\">返回重新设置</a>!</blockquote>");
				$u_name = trim($_POST['u_name']);
				$u_pass = trim($_POST['u_pass']);
				if(ctype_graph($u_pass)!=1) die($res."<blockquote>密码设置错误！请 <a href=\"javascript:history.go(-1);\">返回重新设置</a>!</blockquote>");
				$ad = $_POST['ad'];
				$savstr = "<?php\n\n\$logname=\"".$u_name."\";\n\$logpass=\"".$u_pass."\";\n\$ad=\"".$ad."\";\n\n?>";
				if(isset($user)){//改变用户账号
					$this->savfile($uf,$savstr);
					for($i=0;$i<count($ar);$i++){
						list($ud,$un) = explode("=",$ar[$i]);
						if($user == trim($ud)) $ar[$i] = $u_id." = ".$u_name."\n";
					}
					$this->savfile($ulst,implode("",$ar));
					$res .= $this->goback($this->id);
				}else{//添加用户
					$uf = "../source/user/".$u_id.".php";
					if(is_file($uf)) die($res."<blockquote><br />账号 $u_id 已存在！请 <a href=\"./?id=$this->id\">返回重新设置</a></blockquote>");
					$this->savfile($uf,$savstr);
					$savstr = $u_id." = ".$u_name."\n";
					$this->savfile($ulst,$savstr,'a');
					$res .= $this->goback($this->id);
				}
				break;
			case 'ask':
				$res .= "<blockquote>确实要删除会员账号 $user 吗？请确认：<br /><br > · <a href=\"./?id=$this->id&user=$user&act=del\">删除</a><br /> · <a href=\"./?id=$this->id\">放弃</a></blockquote>";
				break;
			case 'del':
				$savstr = "";
				for($i=0;$i<count($ar);$i++){
					list($ud,$un) = explode("=",$ar[$i]);
					if($user != trim($ud)) $savstr .= $ar[$i];
				}
				$this->savfile($ulst,$savstr);
				unlink($uf);
				$res .= $this->goback($this->id);
				break;
			default:
				if(isset($ar)){
					$res .= "\t\t<p><b>会员列表</b>: [ ".count($ar)." ]\n\t\t<p>修改会员资料请单击用户名称:</p>\n\t\t<blockquote>\n";
					for($i=0;$i<count($ar);$i++){
						list($ud,$un) = explode("=",$ar[$i]);
						$res .= "\t\t\t".($this->user == trim($ud) ? "<span style=\"color:#999;\">".trim($un)."</span>" : ("<a href=\"./?id=$this->id&user=$ud\">".(isset($user) && $user==trim($ud) ? "<span class=\"red\">".trim($un)."</span>" : trim($un))))."</a>&nbsp;&nbsp;\n";
					}
					$res .= "\t\t</blockquote>\n";
				}else{
					$res .= "\t\t<p><b>会员列表</b>: [ 0 ]\n\t\t<p>\n\t\t<p>会员库不存在</p>\n";
				}
				if(isset($user)){
					include $uf;
					$chk = isset($ad) && $ad == 0 ? " checked" : "";
					$disabled = "disabled";
					$del = $this->user == $user ? "站长身份会员账号" : "<a href=\"./?id=$this->id&user=$user&act=ask\"><span class=\"red\">删除此账号</span></a>";
					$u_msg = "&user=$user";
					$back = "<a href=\"./?id=$this->id\">初始化本页</a>";
				}else{
					$chk = "";
					$del = "限制为小写字母和阿拉伯数字";
					$disabled = "";
					$back ="";
					$u_msg = "";
					$logname = "";
					$logpass = "";
				}
				$form_str = "\t\t<form name=\"adduser\" method=\"post\" action=\"./?id=$this->id&act=save".$u_msg."\">
			<table style=\"margin:auto; width:90%;\">
				<tr><td><label for=\"u_id\">ID号: </label><input name=\"u_id\" id=\"u_id\" size=\"40\" maxlength=\"16\" value=\"$user\" $disabled /> [ $del ]</td></tr>
				<tr><td><label for=\"u_name\">账号: </label><input name=\"u_name\" id=\"u_name\" size=\"40\" maxlength=\"16\" value=\"$logname\" /> [ 支持中文 ]</td></tr>
				<tr><td><label for=\"u_pass\">密码: </label><input name=\"u_pass\" id=\"u_pass\" size=\"40\" maxlength=\"16\" value=\"$logpass\" /> [ 支持大小写英文及常规键盘符号 ]</td></tr>
				<tr><td class=\"mid\"><p><input type=\"radio\" value=\"1\" name=\"ad\" id=\"ad\" ".(isset($chk) && $chk == 1 ? "" : "checked")." /> 普通会员 <input type=\"radio\" value=\"0\" name=\"ad\" id=\"ad\"".@$chk." /> 站长身份&nbsp;&nbsp; <input type=\"submit\" value=\" ".(isset($user) ? "修改账号" : "建立账号")." \" />&nbsp; $back</p></td></tr>
			</table>
		</form>\n";
				$res .= "\t\t<hr />\n".$form_str;
				break;	
		}
		return $res;
	}

	public function upfile(){//上传文件
		$res = "\t\t<h3>上传文件</h3>\n";
		$act = @$_GET['act'];
		$path = "../file";
		$this->mk_dir($path);
		switch($act){
			case 'up':
				if($_FILES["file"]["error"] > 0){
					$res .= "\t\t<blockquote>警告：<br /><br />&nbsp; &nbsp; 尚未选择文件或所选择的文件不存在！请 <a href=\"javascript:history.go(-1);\">返回前页</a> 重新操作！</blockquote>\n";
				}else{
					$exp=trim($_POST['exp']);
					$exp=str_replace("#","＃",$exp);
					if($exp==""){
						$res .= "\t\t<blockquote>警告：<br /><br />&nbsp; &nbsp; 保存的目标文件缺少说明！请 <a href=\"javascript:history.go(-1);\">返回前页</a> 重新操作！</blockquote>\n";
					}else{
						$file_name=$_FILES["file"]["name"];
						$temp_ar=explode(".", $file_name);
						$file_ext=array_pop($temp_ar);
						$file_ext=strtolower(trim($file_ext));
						$savFile=($this->user == "master" ? "" : $this->user."_").date("YmdHms").'_'.rand(10000, 99999).'.'.$file_ext;
						$res .= "\t\t<p>上传文件: ".$file_name."<br />文件类型: ".$_FILES["file"]["type"]."<br />文件大小: ".round(($_FILES["file"]["size"] / 1024),2)." Kb<br />保存名称: ".$savFile."</blockquote>\n";
						if(move_uploaded_file($_FILES["file"]["tmp_name"],$path."/".$savFile)){;
							$db_file=$path."/ufile.txt";
							$this->savfile($db_file, $path."/".$savFile."#".$exp."\n", 'a');
							$res .= "\t\t<blockquote>文件 <span class=\"red\">".$_FILES["file"]["name"]."</span> 已经成功上传为 <span class=\"red\">".$path."/".$savFile."</span><br /><br /><a href=\"./?id=$this->id\">返回上传页面</a></blockquote>\n";
						}else{
							$msg="<p>错误:<br /><br />&nbsp; &nbsp; 程序在上传文件时出现意外！请检查原因！";	
						}
					}
				}
				return $res;
				break;
			default:
				$res .= "\t\t<blockquote><form action=\"./?id=$this->id&act=up\" method=\"post\" enctype=\"multipart/form-data\">
		<p><label for=\"file\">请选择上传文件: </label><input type=\"file\" name=\"file\" id=\"file\" /> &nbsp; &nbsp; </p>
		<p><label for=\"exp\">文件说明: </label><input name=\"exp\" id=\"exp\" size=\"40\" style=\"width:300px;\" /></p>
		<p class=\"mid\"><input type=\"submit\" name=\"submit\" value=\" 开始上传 \" /></p>\n\t\t</form></blockquote>\n";
				break;
		}
		return $res;
	}

	public function upload_ad(){//上传文档管理
		$pg = isset($_GET['pg']) ? $_GET['pg'] : 0;
		$fs = isset($_GET['fs']) ? $_GET['fs'] : 0;
		$act = @$_GET['act'];
		$uf = $fs == 1 ? "../file/ufile.txt" : "../upload/upadmin.txt";
		$lnk = $fs == 0 ?  "图片 <a href=\"./?id=$this->id&fs=1\">其他</a>" : "<a href=\"./?id=$this->id&fs=0\">图片</a> 其他";
		$res = "\t\t<h3 class=\"mid\">上传文件管理 : ".$lnk."</h3>\n";
		if(is_file($uf)) $ar = file($uf);
		if($act == 'del'){ //删除
			$file=@$_GET['file'];
			if(empty($file) || !is_file($file)) die("wrong file name !");
			if($this->ad > 0){
				if(!strstr($file,$this->user)) die("error: you can del your pictures only");
			}
			unlink($file);
			$savstr="";
			for($i=0;$i<count($ar);$i++){
				if(!strstr($ar[$i],$file) and $ar[$i]!="") $savstr.=trim($ar[$i])."\n";
			}
			$savstr == "" ? unlink($uf) : $this->savfile($uf,$savstr);
			$res .= "<blockquote><br />文档 $file 删除操作完毕！ 请 <a href=\"javascript:history.go(-1);\">返回前页</a></blockquote>";
		}else{ //显示
			if(isset($ar)){
				for($i=count($ar)-1;$i>=0;$i--){//倒转数组
					if($this->ad == 0){//完全权限
						$newar[]=$ar[$i];
					}else{
						if($this->get_uf($ar[$i]) !="" ) $newar[]=$this->get_uf($ar[$i]);
					}
				}
				if(isset($newar)){
					$res .= "\t\t<p class=\"right\">".$this->mkpage(count($newar),20,$pg,$fs)."</p>\n";
					$res .= "\t\t<table style=\"width:100%;border:1px solid #aaa;padding:2px;\">\n\t\t\t<tr style=\"background-color:#aaa;\"><td style=\"width:40px;text-align:center;\">序号</td><td style=\"text-align:center;\">文件名</td><td style=\"width:150px;text-align:center;\">说明</td><td colspan=\"2\" class=\"mid\">相关操作</td></tr>\n";
					for($i=$pg*20;$i<$pg*20+20;$i++){
						if($i<count($newar)) $picstr = explode("#",trim($newar[$i]));
						$k=$i+1;
						$pic_idx = count($newar) - $i -1;
						if(isset($newar[$i])) $res .= "\t\t\t<tr style=\"background-color:#ddd;\"><td>&nbsp;$k</td><td>&nbsp;".($fs == 1 ? $picstr[0] : "<a target=\"_blank\" title=\" 查看 \" href=\"".$picstr[0]."\">".$picstr[0]."</a>")."</td><td id=\"$pic_idx\">".stripslashes (trim($picstr[1]))."</td><td width=\"10%\" align=\"center\">".($fs==1 ? "<a href=\"$picstr[0]\" target=\"_blank\">下载</a>" : "<a href=\"javascript:edit_msg($pic_idx)\">编辑说明</a>")."</a></td><td width=\"10%\" align=\"center\"><a href=\"./?id=$this->id&file=$picstr[0]&act=del&fs=$fs\">删除</a></td></tr>\n";
					}
					$res .= "\t\t</table>\n";
					$res .= "\t\t<p class=\"right\">".$this->mkpage(count($newar),20,$pg,$fs)."</p>\n";
					$res .= "<script language='javascript'>\nfunction edit_msg(picId){
	var picMsg = prompt('请输入图片说明: ',document.getElementById(picId).innerHTML);
	if(picMsg == null || picMsg == '') return;
	var sPic = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	sPic.open('POST','../pic/edit.php',true);
	sPic.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	sPic.send('id=' + picId + '&msg='  + picMsg + '');
	sPic.onreadystatechange = function(){
		if (sPic.readyState==4 && sPic.status==200){
			if(sPic.responseText) document.getElementById(picId).innerHTML = sPic.responseText;
		}\n\t}\n}\n</script>\n";
				}else{
					$res .= "<blockquote><br />未发现您的任何上传记录</blockquote>";
				}
			}else{
				$res .= "<blockquote><br />未发现任何上传记录</blockquote>";
			}
		}
		return $res;
	}

	public function art_ad($st=1){//文章管理
		$act = @$_GET['act'];
		$st = @$_GET['st'];
		$res = "\t\t<h3>文章管理</h3>\n";
		if($st>$this->sort_all) $st = $this->sort_all;
		if($st<1) $st = 1;
		$dat_file = "../art/".$this->sort_ar[$st-1][1].".txt";//栏目库
		if(is_file($dat_file)) $ar = file($dat_file);
		if($act == 'del'){//删除
			$sd = $_GET['sd'];
			$art = $_GET['art'];
			$df =  "../art/".$sd."/".$art.".php";//主文档
			$cf = "../art/".$sd."c/".$art.".txt";//评论文档
			if(!is_file($df)) die($res."<blockquote><br />文章 $df 不存在！ 请 <a href=\"javascript:history.go(-1);\">返回前页</a></blockquote>");
			if($this->ad != 0) {
				if($this->get_uf($art) == "") die($res."<blockquote><br />you can del your files only</blockquote>");	
			}
			@unlink($df);
			if(is_file($cf)){
				$del_num = count(file($cf));
				$cmm_rec = "../source/commrec.php";
				require ($cmm_rec);
				$comm_tt -= $del_num;
				$cmm_sav='<?php $comm_tt='.$comm_tt.'; ?>';
				$this->savfile($cmm_rec,$cmm_sav);
				@unlink ($cf); //删除评论库文件
				$new_cmm="../art/cmm_newest.txt";//最新评论库
				if(is_file($new_cmm)) {
					$cmm_str_ar=file($new_cmm);
					$savstr="";
					for($i=0;$i<count($cmm_str_ar);$i++) {
						if(!strstr($cmm_str_ar[$i],$art)) $savstr .= trim($cmm_str_ar[$i])."\n";
					}
					$this->savfile($new_cmm,$savstr);
				}
			}
			for($i=0;$i<count($ar);$i++){
				if(!strstr($ar[$i],$art)) $dat_sav[]=$ar[$i];
			}
			$dat_sav = isset($dat_sav[0]) ? trim(implode("",$dat_sav))."\n" : "";
			$this->savfile($dat_file,$dat_sav);
			$newest_dat="../art/newest.txt";//最新记录库
			$newest_db=file($newest_dat);
			for($i=0;$i<count($newest_db);$i++){
   				if(!strstr($newest_db[$i],$art)) $newest_sav[]=$newest_db[$i];
			}
			$newest_sav = trim(isset($newest_sav[0])) ? trim(implode("",$newest_sav))."\n" : "";
			$this->savfile($newest_dat,$newest_sav);
			$numFile="../art/".$sd."c/".$art."_c.txt";//文章阅读计数文档
			if(is_file($numFile)) unlink($numFile);
			$this->dostat();//刷新文章统计
			$res .= "文章 $df 删除完毕！请 <a href=\"javascript:history.go(-1);\">返回前页</a></blockquote>";
		}else{//文章列表
			$res .= "\t\t选择栏目 >>";
			for($i=0;$i<$this->sort_all;$i++){//栏目列表
				$res .= (($st-1 == $i ? "·<span class=\"red\">".$this->sort_ar[$i][0]."</span> &nbsp;" : "·<a href=\"?id=$this->id&st=".($i+1)."\">".$this->sort_ar[$i][0]."</a> &nbsp;"))."\n\t\t";
			}
			if(isset($ar)){
				$pg = isset($_GET['pg']) ? $_GET['pg'] : 0;
				for($i=count($ar)-1;$i>=0;$i--){//倒转数组
					if($this->ad == 0){
						$st_db[]=$ar[$i];
					}else{
						if($this->get_uf($ar[$i]) != "") $st_db[] = $this->get_uf($ar[$i]);
					}
				}
				if(isset($st_db)){
					$res .= "<p class=\"right\">".$this->mkpage(count($st_db),20,$pg,$st)."</p>\n";
					$res .= "\t\t<table style=\"width:100%;border:1px solid #bbb;padding:2px;\">\n";
					$res .= "\t\t\t<tr style=\"background-color:#aaa;\">\n\t\t\t\t<td style=\"width:45px;text-align:center;\">序号</td>\n\t\t\t\t<td style=\"width:400px;text-align:center;\">文 章 标 题</td>\n\t\t\t\t<td style=\"width:80px;text-align:center;\">作者</td>\n\t\t\t\t<td colspan=\"3\" class=\"mid\">操 作 选 项</td>\n\t\t\t</tr>\n";
					for($i=$pg*20;$i<$pg*20+20;$i++){
						if($i<count($st_db)){
							list($a_st,$a_fn,$a_title,$auth,$show_fn,$create_tm,$sd) = explode("#",$st_db[$i]);
							$sd = trim($sd);
							$url = "../art/".$sd."/".$a_fn.".php";
							$k = $i+1;
							$bb = $show_fn == "b" ? "bshow.php" : "show.php";
							if(!empty($st_db[$i])) $res .= "\t\t\t<tr style=\"background-color:#ddd;\">\n\t\t\t\t<td>".$k."</td>\n\t\t\t\t<td>".stripslashes($a_title)."</td>\n\t\t\t\t<td>".stripslashes($auth)."</td>\n\t\t\t\t<td class=\"mid\"><a href=\"../art/$bb?st=$a_st&sd=$sd&art=$a_fn\" target=\"_blank\">查看</a></td>\n\t\t\t\t<td class=\"mid\"><a href=\"./?id=".($this->id-1)."&st=$a_st&sd=$sd&art=$a_fn&act=edit\">编辑</a></td>\n\t\t\t\t<td class=\"mid\"><a href=\"./?id=".$this->id."&st=$a_st&sd=$sd&art=$a_fn&act=del\">删除</a></td>\n\t\t\t</tr>\n";
						}
					}
					$res .= "\t\t</table>\n\t\t<p class=\"right\">".$this->mkpage(count($st_db),20,$pg,$st)."</p>\n";
				}else{
					$res .= "<blockquote><br />尚未发现栏目 ".$this->sort_ar[$st-1][0]." 库文件中有您发布的文章!</blockquote>"; 	
				}
			}else{
				$res .= "<blockquote><br />尚未发现栏目 ".$this->sort_ar[$st-1][0]." 库文件!</blockquote>"; 	
			}
		}
		return $res;
	}

	public function cmm_ad(){//评论管理
		$act = @$_GET['act'];
		$st = (int)@$_GET['st'];
		if($st < 1) $st = 1;
		if($st > $this->sort_all) $st = $this->sort_all;
		$sd = @$_GET['sd'];
		$art = @$_GET['art'];
		$pg = @$_GET['pg'];
		$res = "\t\t<h3>文章评论管理</h3>\n";
		switch($act){
			case 'del':
				$idx = @$_GET['idx'];
				$dbFile = "../art/".$sd."c/".$art.".txt";
				if(!is_file($dbFile)) die($res."<blockquote><br />操作参数错误!</blockquote>");
				if($this->get_uf($art) == "") die($res."<blockquote><br />您没有删除权限!</blockquote>");
				$comm_ar = file($dbFile);
				$del_msg_ar = explode("#",$comm_ar[$idx]);
				unset($comm_ar[$idx]);
				$del_new_line = $st."#".$art."#".trim($del_msg_ar[0])." - ".trim($del_msg_ar[2]);//删除行内容
				count($comm_ar) > 0 ? $this->savfile($dbFile,implode('',$comm_ar)) : @unlink($dbFile);
				$cmm_rec = "../source/commrec.php";//评论总数
				require($cmm_rec);
				if($comm_tt > 0) $comm_tt -= 1;
				$comm_sav = '<?php $comm_tt='.$comm_tt.'; ?>';
				$this->savfile($cmm_rec,$comm_sav);
				$new_cmm_file = "../art/cmm_newest.txt";//最新评论
				if(is_file($new_cmm_file)){
					$cmm_str_ar = file($new_cmm_file);
					$sav_str = "";
					for($i=0;$i<count($cmm_str_ar);$i++){
						if(!strstr($cmm_str_ar[$i],$del_new_line)) $sav_str .= trim($cmm_str_ar[$i])."\n";
					}
					$sav_str != "" ? $this->savfile($new_cmm_file,$sav_str) : @unlink($new_cmm_file);//处理最新评论库
				}
				$res .= "<blockquote><br />删除操作完毕! 请 <a href=\"javascript:history.go(-1);\">返回前页</a>";
				break;
			case 'delall':
				$dbFile = "../art/".$sd."c/".$art.".txt";
				if(!is_file($dbFile)) die($res."<blockquote><br />操作参数错误!</blockquote>");
				if($this->get_uf($art) == "") die($res."<blockquote><br />您没有删除权限!</blockquote>");
				$ttc = count(file($dbFile));
				@unlink($dbFile);
				$cmm_rec = "../source/commrec.php";//评论总数
				require($cmm_rec);
				if($comm_tt > 0) $comm_tt -= $ttc;
				$comm_sav = '<?php $comm_tt='.$comm_tt.'; ?>';
				$this->savfile($cmm_rec,$comm_sav);
				$new_cmm_file = "../art/cmm_newest.txt";//最新评论
				if(is_file($new_cmm_file)){
					$cmm_str_ar = file($new_cmm_file);
					$sav_str = "";
					for($i=0;$i<count($cmm_str_ar);$i++){
						if(!strstr($cmm_str_ar[$i],$art)) $sav_str .= trim($cmm_str_ar[$i])."\n";
					}
					$sav_str != "" ? $this->savfile($new_cmm_file,$sav_str) : @unlink($new_cmm_file);//处理最新评论库
				}
				$res .= "<blockquote><br />删除操作完毕! 请 <a href=\"javascript:history.go(-1);\">返回前页</a>";
				break;
			case 'chk':
				$dbFile="../art/".$sd."c/".$art.".txt";
				if(!is_file($dbFile)) die($res."<blockquote><br />文章 $art 没有评论！请返回 <a href=\"./?id=$this->id\">评论总汇</a></blockquote>");
				$comm_ar=file($dbFile);
				$tt_num=count($comm_ar);
				$a_file="../art/".$sd."/".$art.".php"; 
				if(file_exists($a_file)){
					include ($a_file);
					$a_link="<a href=\"../art/".(isset($bshow) && $bshow == "b" ? "bshow.php" : "show.php")."?st=$st&sd=$sd&art=$art\">".stripslashes($ftitle)."</a>";
				}
				$res .= "\t\t文章: $a_link 评论总数: $tt_num<span style=\"float:right;\"><a href=\"./?id=$this->id\">评论总汇</a></span>\n\t\t<table style=\"width:100%;border:1px solid #bbb;padding:2px;\">\n";
				$res .= "\t\t\t<tr style=\"background-color:#aaa;\"><td style=\"width:80px;text-align:center;\"><b>评论人</b></td><td style=\"width:120px;text-align:center;\"><b>评论日期</b></td><td class=\"mid\"><b>评论内容</b></td><td style=\"width:60px;text-align:center;\"><a href=\"./?id=$this->id&st=$st&sd=$sd&art=$art&act=delall\"><strong>删除全部</strong></a></td></tr>\n";
				for($i=$pg*15;$i<$pg*15+15;$i++){
					if($i<$tt_num){
						$str_ar=explode("#",$comm_ar[$i]);
						$res .= "\t\t\t<tr style=\"background-color:#ddd;\"><td>".stripslashes($str_ar[0])."</td><td>".trim($str_ar[2])."</td><td>".stripslashes($str_ar[1])."</td><td class=\"mid\"><a href=\"./?id=$this->id&st=$st&sd=$sd&art=$art&act=del&idx=$i\">删除</a></td></tr>\n";
					}
				}
				$res .= "\t\t</table>\n\t\t<p class=\"right\">".$this->mkpage($tt_num,15,$pg,$st,"&art=$art&act=chk")."</p>\n";
				break;
			default:
				$res .= "\t\t选择栏目 >> ";
				for($i=0;$i<$this->sort_all;$i++){//栏目列表
					$res .= (($st-1 == $i ? "·<span class=\"red\">".$this->sort_ar[$i][0]."</span> &nbsp;" : "·<a href=\"?id=$this->id&st=".($i+1)."\">".$this->sort_ar[$i][0]."</a> &nbsp;"))."\n\t\t";
				}
				$sort_file="../art/".$this->sort_ar[$st-1][1].".txt";
				if(!is_file($sort_file)){
					$res .= "<p>栏目 ".$this->sort_ar[$st-1][0]." 文章尚未发布，没有评论</p>";
				}else{
					$sort_db_ar=array_reverse(file($sort_file));
					for($i=0;$i<count($sort_db_ar);$i++){//查找有没有评论
						list($a_st,$a_fn,$a_title,$auth,$show_fn,$create_tm,$a_sd) = explode("#",$sort_db_ar[$i]);
						$chk_file = "../art/".trim($a_sd)."c/".$a_fn.".txt";//评论库文件
						if(file_exists($chk_file)){      
							if($this->ad == 0) { //评论统计-完全权限
								$sort_db[] = $sort_db_ar[$i];
								$comm_num[] = count(file($chk_file));
							}else{//评论统计-会员权限
								if($this->get_uf($chk_file) != ""){
									$sort_db[] = $sort_db_ar[$i];
									$comm_num[] = count(file($chk_file));
								}
							}
						}
					}
					if(!isset($sort_db)){
						$res .= "<blockquote>栏目 ".$this->sort_ar[$st-1][0]." 没有评论</blockquote>";
					}else{
						$res .= "<p class=\"right\">".$this->mkpage(count($sort_db),15,$pg,$st)."</p>\n";
						$res .= "\t\t<table style=\"margin:auto;width:100%;padding:2px;border:1px solid #bbb;\">\n";
						$res .= "\t\t\t<tr style=\"background-color:#aaa;\">\n\t\t\t\t<td style=\"width:45px;text-align:center;\">序号</td>\n\t\t\t\t<td class=\"mid\">文 章 标 题</td>\n\t\t\t\t<td style=\"width:120px;text-align:center;\">作者</td>\n\t\t\t\t<td colspan=\"2\" style=\"width:100px;text-align:center;\">操 作 选 项</td>\n\t\t\t\t<td style=\"width:60px;text-align:center;\">评论统计</td>\n\t\t\t</tr>\n";
						for($i=$pg*15;$i<$pg*15+15;$i++){
							if($i<count($sort_db)){
								list($a_st,$a_fn,$a_title,$auth,$show_fn,$create_tm,$a_sd) = explode("#",$sort_db[$i]);
								$a_sd = trim($a_sd);
								$url = "../art/".$a_sd."/".$a_fn.".php";
								$k = $i+1;
								$back = "./?id=$this->id&sort=".$st;
								if(!empty($sort_db[$i]) && $comm_num[$i] > 0) $res .= "\t\t\t<tr style=\"background-color:#ddd;\">\n\t\t\t\t<td>$k</td>\n\t\t\t\t<td>".stripslashes($a_title)."</td>\n\t\t\t\t<td>".stripslashes($auth)."</td>\n\t\t\t\t<td><a href=\"../art/".($show_fn == "b" ? "bshow.php" : "show.php")."?st=$a_st&sd=$a_sd&art=$a_fn\" target=\"_blank\">阅读文章</a></td>\n\t\t\t\t<td align=\"center\"><a href=\"./?id=$this->id&act=chk&st=$a_st&sd=$a_sd&art=$a_fn\">编辑评论</a></td>\n\t\t\t\t<td align=\"center\">".$comm_num[$i]."</td>\n\t\t\t</tr>\n";
							}
						}
						$res .= "\t\t</table>\n\t\t<p class=\"right\">".$this->mkpage(count($sort_db),15,$pg,$st)."</p>\n";
					}
				break;
			}
		}
		return $res;
	}

	public function dostat(){
		$uri=$_SERVER['PHP_SELF'];
		$dir_name=pathinfo($uri,PATHINFO_DIRNAME);
		$dir_name=substr($dir_name,0,strlen($dir_name)-strlen("admin"));//相对目录名
		$savstr = "";
		for($i=0;$i<$this->sort_all;$i++){
			$fn = "../art/".$this->sort_ar[$i][1].".txt";
			$tt = is_file($fn) ? count(file($fn)) : 0;
			$savstr .="\n\t\t\t\t·<a href=\"".$dir_name."art/?id=$i\">".$this->sort_ar[$i][0]."</a> : ".$tt."<br />";
		}
		$sastr = "<?php\n\n//网站统计\n\necho '$savstr';\n\n?>";
		$this->savfile("../source/stat.php",$savstr);
	}

	public function str_rep($str){//替换函数一
		if(get_magic_quotes_gpc()==0) $str=addslashes($str);
		$str = str_replace('$','\$',trim($str));
		$str = str_replace("\'","'",$str);
		return $str;
	}

	public function str_rep2($str){//替换函数二
		$str = str_replace('"',"'",trim($str));
		return stripslashes($str);
	}

	public function savfile($fn,$str,$type='w'){//保存文档
		$fp = fopen($fn,$type);
		flock($fp,LOCK_EX);
		fwrite($fp,$str);
		flock($fp,LOCK_UN);
		fclose($fp);
	}

	public function goback($id) {//返回指定页
		return "<script language=\"javascript\">\nvar timerID=setTimeout(\"window.location='./?id=$id'\",2000);\n</script>\n<blockquote><br />&nbsp; &nbsp; 操作完毕！请 <a href=\"./?id=$id\">立即返回</a></blockquote>\n";
	}

	public function mkpage($all,$pgnum,$idx,$st,$other="") {//分页
		if(isset($st)) $st = "&st=$st";
		$tt=($all%$pgnum==0 ? $all/$pgnum : floor($all/$pgnum)+1); //总页数
		$pgrtt=($tt%5==0 ? floor($tt/5) : floor($tt/5)+1); //页组总数
		$pgrid=floor($idx/5); //页组所在序号
		$pre5=$pgrid*5-1; //前5页(页组最末一页为当前页, 故减去1)
		$behind5=$pgrid*5+5; //后5页(当前页为当页组的第一页)
		$last=$tt-1; //最后一页
		$res = ($idx!=0 ? "<a title=\" 返回第一页 \" href=\"./?id=$this->id&pg=0".$st.$other."\">&lt;&lt;</a>&nbsp;" : "<span style=\"color:#888\">&lt;&lt;</span>&nbsp;");
		$res .= ($pgrid!=0 ? "<a title=\" 前面五页 \" href=\"./?id=$this->id&pg=$pre5".$st.$other."\">&lt;</a>&nbsp;" : "<span style=\"color:#888\">&lt;</span>&nbsp;");
		for($j=$pgrid*5;$j<$pgrid*5+5;$j++){
			$jp=$j+1;
			if($j<$tt) $res .= ($j==$idx ? "<span class=\"red\">$jp</span>&nbsp;" : "<a href=\"./?id=$this->id&pg=$j".$st.$other."\">$jp</a>&nbsp;");
		}
		$res .= ($pgrid!=$pgrtt-1 ? "<a title=\" 后面五页 \" href=\"./?id=$this->id&pg=$behind5".$st.$other."\">&gt;</a>&nbsp;" : "<span style=\"color:#888\">&gt;</span>&nbsp;");
		$res .= ($idx!=$tt-1 ? "<a title=\" 最后一页 \" href=\"./?id=$this->id&pg=$last".$st.$other."\">&gt;&gt;</a>&nbsp;" : "<span style=\"color:#888\">&gt;&gt;</span>&nbsp;");
		$res .= "[ 共 $tt 页 总条目 $all ]";
		return $res;
	}

	function mk_dir($dname) { if(!is_dir($dname)) @mkdir($dname,0777); }//创建目录

	public function get_uf($str) {//取用户文档
		return (strstr($str,$this->user) ? $str : ($this->user == 'master' ? $str : ""));
	}
}

?>