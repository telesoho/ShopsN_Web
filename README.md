# ShopsN2.X配置文档

最新版请以qq群422171904 的群文件以及百度云盘为准
群文件，群相册，群公告，可以回答您80%的疑问。请问重复咨询灌水问题降低qq群讨论质量。
技术讨论区：www.shopsn.net/bbs
因为ShopsN涉及多个客户端，有一定的复杂性，强烈建议安装前完整读一遍下文帖子。
http://www.shopsn.net/bbs/forum.php?mod=forumdisplay&fid=3&filter=typeid&typeid=6
建议安装环境：
Php5.5或Php5.6
Mysql 5.5以上

ShopsN 分为五个端 网站、手机网站、微信、安卓、IOS
全部版本下载地址：http://www.shopsn.net/bbs/thread-104-1-1.html

ShopsN是面向2020后的。所以在技术上比较超前。仅在web端保留了传统的开发方式。

移动端采用了先进的前后端分离（Vue）技术，放在网站内的手机网站（mobile）目录，是手机网站源代码编译后生成的，并不是源代码。放在moblie目录默认的编译代码，是我们的演示数据。

因此您需要下载手机网站源代码，进行编译，然后将生成的文件放入到mobile目录下(删除mobile内原有的老演示数据）。具体如何编译，请参考ShopsN手机H5端源代码目录的说明文档。
编译方法在线文档：http://www.shopsn.net/bbs/forum.php?mod=viewthread&tid=77&extra=page%3D1%26filter%3Dtypeid%26typeid%3D6

ShopsN接口端是对接app接口和H5及微信的，需要单独建一个虚拟主机站点。不要与网站共享代码。因为接口操作的结果是与网站共享数据库，读取写入结果到数据库。
api接口站点配置文档：http://www.shopsn.net/bbs/forum.php?mod=viewthread&tid=82&extra=page%3D1%26filter%3Dtypeid%26typeid%3D6


Uploads.zip是演示图包，可以下载后放到网站根目录。
请注意网站务必放在根目录！

微信版在2.2版本正式加入ShopsN家族！

安卓和ios在各自文档不必细说了。

前后端分离技术高度适用于外包公司及中大公司技术开发，可以减轻很大的工作量。


公司官网www.shopsn.net 欢迎使用我们的电商系统，也欢迎定制开发！

ShopsN可免费商用，允许二次开发（包括允许外包公司代客户开发），但严禁未获得授权下擅自删除版权标志，或用于改名（包括修改代码）后侵犯著作权当做自己产品二次销售！否则发现即追责到底。

安装提示：
1.请务必确认您的数据库存储引擎为Innodb而不是MyISAM。否则会引起安装创建数据库表失败的问题。Mysql5.5默认为MyISAM，需要修改模式。

2.代码包解压后放到根目录即可。请注意,不要放在非根目录!

3.index.php 文件必定是www.xxx.com/index.php的路径.
如果你填入的账号不是root，在安装之前请务必提前在Mysql里开通好数据库，获得账号（并赋予足够的访问权限！）及密码。我们的系统不能给你开通mysql的新数据库user和password。但是填写root意味着你的系统最高权限风险。建议不要使用root。

4.安装后如需修改数据库配置:./application/Common/Conf/db.php 填入你的ip、数据库、密码即可。

演示图包：
演示效果可参阅我司网站www.shopsn.net。上传图片均在根目录下的Uploads内。我们默认删除了演示图片。如果需要，可以到百度网盘下载。大约100m左右。地址：http://www.shopsn.net/bbs/thread-104-1-1.html  将图包Upaloads.rar下载后放到根目录覆盖即可。


备份还原数据库可能遇到的问题：
在mysqladmin里，MySQL导入文件大小默认是有限制的，最大为2M，所以当文件很大时候，直接无法导入，可修改PHP.ini参数调整：在php.ini中修改相关参数：影响MySQL导入文件大小的参数有三个：、
memory_limit=128M,upload_max_filesize=2M,post_max_size=8M  这里改到200m左右吧


内网测试技巧：
一个网站在上线之前都需要经过本地的测试,测试无误后再上传到服务器空间。网站在本地测试过程中可以使用默认的地址如:localhost或者127.0.0.1来访问本地的测试网站,当然我们还可以通过给本机绑定一个域名来进行访问测试。具体方法如下:用记事本打开:C:\WINDOWS\system32\drivers\etc\hosts 文件，增加一行
127.0.0.1     www.shopsn.com（或者你想要的域名） 保存即可
如果数据库配置无误，php版本正确，数据库正常启动并且你已经正确导入了数据库，没有缺少权限，现在你就可以打开www.shopsn.com了


登录地址：
后台登录地址：域名后输入/adminprov.php/
网站后台 默认 用户 admin 密码 admin888 
前台 内置一个前端用户账号：hxh；密码：admin888


密码重置：
管理员在db_admin  用户在db_user 里修改 。navcat工具类,注意修改后要保存才会生效!
密码可以用常见的md5 对应值，如admin888 对应  7fef6171469e80d32c0559f88b377245



我司可为授权用户代理价代购云主机，折上折九折代购腾讯云。
新网SSD硬盘主机箭头云六折。有意请联系。
提供代配置服务器环境及安装服务。
如需授权请联系

官网 www.shopsn.net
论坛 www.shopsn.net/bbs
上海亿速网络科技有限公司 2018.1.2

#安装方法

微信支付证书设置

微信支付申请好后将对应证书放于当前项目/Application/PlugInUnit/Wxpay/cacert目录下
