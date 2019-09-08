<?php
// $Id: blauer-fisch $
//%%%%%%		File Name user.php 		%%%%%
define('_US_NOTREGISTERED', '没有注册?  点击 <a href="register.php">这里</a>开始注册');
define('_US_LOSTPASSWORD', '忘记密码?');
define('_US_NOPROBLEM', '没问题，输入注册时的电子邮箱地址吧。');
define('_US_YOUREMAIL', '电子邮箱地址: ');
define('_US_SENDPASSWORD', '发送密码');
define('_US_LOGGEDOUT', '你已登出');
define('_US_THANKYOUFORVISIT', '欢迎光临!');
define('_US_INCORRECTLOGIN', '不正确的登录!');
define('_US_LOGGINGU', '感谢登录, %s.');
define('_US_RESETPASSWORD', '重设密码');
define('_US_SUBRESETPASSWORD', '重设密码');
define('_US_RESETPASSTITLE', '密码超时!');
define('_US_RESETPASSINFO', '请完整填写下列表格以重设密码，如果邮箱地址、用户名、当前密码都正确，密码就会重设，你也就可以再次登录了。');
define('_US_PASSEXPIRED', '密码超时<br />将会指向一个表格页面，以便重设密码.');
define('_US_SORRYUNAMENOTMATCHEMAIL', '用户名、邮箱地址不匹配!');
define('_US_PWDRESET', '密码已重设!');
define('_US_SORRYINCORRECTPASS', '当前密码输入不正确!');

// 2001-11-17 ADD
define('_US_NOACTTPADM', '所选用户未激活.<br />请联系管理员.');
define('_US_ACTKEYNOT', '激活码不正确!');
define('_US_ACONTACT', '所选帐号已激活!');
define('_US_ACTLOGIN', '帐号已激活，请用密码登录.');
define('_US_NOPERMISS', '对不起，权限不足，不能执行此动作。');
define('_US_SURETODEL', '你真的要删除这个帐号吗?');
define('_US_REMOVEINFO', '将从数据库中删除所有信息');
define('_US_BEENDELED', '帐号将被删除');
define('_US_REMEMBERME', '记住我');

//%%%%%%		File Name register.php 		%%%%%
define('_US_USERREG', '用户注册');
define('_US_EMAIL', '电子邮箱');
define('_US_ALLOWVIEWEMAIL', '允许我的电子邮箱地址对其他用户可见');
define('_US_WEBSITE', '网站');
define('_US_TIMEZONE', '时区');
define('_US_AVATAR', '头像');
define('_US_VERIFYPASS', '验证密码');
define('_US_SUBMIT', '提交');
define('_US_LOGINNAME', '用户名');
define('_US_FINISH', '完成');
define('_US_REGISTERNG', '不能继续新用户');
define('_US_MAILOK', '允许接收管理员发送的邮件?');
define('_US_DISCLAIMER', '免责声明');
define('_US_IAGREE', '同意');
define('_US_UNEEDAGREE', '对不起，只有同意免责声明才能继续注册');
define('_US_NOREGISTER', '对不起，新用户注册功能已关闭');

// %s is username. This is a subject for email
define('_US_USERKEYFOR', '用户 %s 的验证码');

define('_US_YOURREGISTERED', '你已注册，一封包含验证码的邮件已发送到你的邮箱，请根据邮件中的提示来完成注册。 ');
define('_US_YOURREGMAILNG', '你已注册，但因服务器内部错误，验证邮件未能发送。由此带来的不便，我们深表遗憾。请向网站管理员发送邮件反映。');
define('_US_YOURREGISTERED2', '你已注册，请等候管理员的激活，如果帐号被激活，我们会发送电子邮件通知你，等候激活有可能需要较长的时间。');

// %s is your site name
define('_US_NEWUSERREGAT', '新用户已在 %s注册');
// %s is a username
define('_US_HASJUSTREG', '%s 刚刚注册!');

define('_US_INVALIDMAIL', '错误: 无效的邮箱');
define('_US_INVALIDNICKNAME', '错误: 无效的用户名, 请换个用户名');
define('_US_NICKNAMETOOLONG', '用户名太长，不应大于 %s 个字符.');
define('_US_NICKNAMETOOSHORT', '用户名太短，不应小于 %s 个字符.');
define('_US_NAMERESERVED', '错误: 用户名不可用');
define('_US_NICKNAMENOSPACES', '用户名中不能包含空格');
define('_US_LOGINNAMETAKEN', '错误: 用户名已被注册');
define('_US_NICKNAMETAKEN', '错误: 显示名称已被别人使用');
define('_US_EMAILTAKEN', '错误: 该邮箱地址已被注册');
define('_US_ENTERPWD', '错误: 必须设置密码');
define('_US_SORRYNOTFOUND', '对不起，找不到用户.');

define('_US_USERINVITE', '友情邀请');
define('_US_INVITENONE', '错误: 只能邀请注册');
define('_US_INVITEINVALID', '错误: 邀请码不正确');
define('_US_INVITEEXPIRED', '错误: 邀请码已过期');

define('_US_INVITEBYMEMBER', '只有现有会员才能邀请新会员; 请要求现有会员发送邀请邮件。');
define('_US_INVITEMAILERR', '服务器出错，含有注册链接的邮件未能发送。由此带来的不便，我们深表歉意。请再试一次，如还有问题，请发送邮件向管理员反映 <br />');
define('_US_INVITEDBERR', '因为内部错误，你的注册请求未能执行。请再试一次，如还有问题，请发送邮件向管理员反映。 <br />');
define('_US_INVITESENT', '一封含有注册链接的邮件已发送去你所提交的电子邮箱，请依照邮件中的提示完成注册。');
// %s is your site name
define('_US_INVITEREGLINK', '来自 %s 的注册邀请');

// %s is your site name
define('_US_NEWPWDREQ', ' %s 的新密码请求');
define('_US_YOURACCOUNT', '你在  %s 的帐号');

define('_US_MAILPWDNG', '邮箱密码：不能更新，请联系管理员');
define('_US_RESETPWDNG', '重设密码：不能更新，请联系管理员');

define('_US_RESETPWDREQ', ' %s 的重设密码请求');
define('_US_MAILRESETPWDNG', '重设密码：不能更新，请联系管理员');
define('_US_NEWPASSWORD', '新密码');
define('_US_YOURUSERNAME', '你的用户名');
define('_US_CURRENTPASS', '你当前的密码');
define('_US_BADPWD', '不安全的密码，密码中不能包含用户名');

// %s is a username
define('_US_PWDMAILED', ' %s 的密码已发送');
define('_US_CONFMAIL', ' %s 的确认邮件已发送');
define('_US_ACTVMAILNG', '发送邮件给 %s 失败');
define('_US_ACTVMAILOK', '给 %s 的通知邮件已发送');

//%%%%%%		File Name userinfo.php 		%%%%%
define('_US_SELECTNG', '没有用户被选中，请返回重试');
define('_US_PM', 'PM');
define('_US_ICQ', 'ICQ');
define('_US_AIM', 'AIM');
define('_US_YIM', 'YIM');
define('_US_MSNM', 'MSNM');
define('_US_LOCATION', '地址');
define('_US_OCCUPATION', '住址');
define('_US_INTEREST', '兴趣');
define('_US_SIGNATURE', '签名');
define('_US_EXTRAINFO', '其他信息');
define('_US_EDITPROFILE', '编辑个人资料');
define('_US_LOGOUT', '登出');
define('_US_INBOX', '收件箱');
define('_US_MEMBERSINCE', '注册自');
define('_US_RANK', '等级');
define('_US_POSTS', '发言/留言');
define('_US_LASTLOGIN', '上次登录');
define('_US_ALLABOUT', '%s的全部信息');
define('_US_STATISTICS', '统计');
define('_US_MYINFO', '我的资料');
define('_US_BASICINFO', '基本资料');
define('_US_MOREABOUT', '更多资料');
define('_US_SHOWALL', '显示全部');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE', '简介');
define('_US_REALNAME', '真实姓名');
define('_US_SHOWSIG', '总是使用签名');
define('_US_CDISPLAYMODE', '留言显示模式');
define('_US_CSORTORDER', '留言排列顺序');
define('_US_PASSWORD', '密码');
define('_US_TYPEPASSTWICE', '(将新密码输入两次以更改)');
define('_US_SAVECHANGES', '保存更改');
define('_US_NOEDITRIGHT', "对不起，你没有更改该用户资料的权限");
define('_US_PASSNOTSAME', '两次输入的密码不匹配. ');
define('_US_PWDTOOSHORT', '对不起, 密码至少<b>%s</b>个字符');
define('_US_PROFUPDATED', '你的资料已更新!');
define('_US_USECOOKIE', '将我的用户名在cookie中保存1年');
define('_US_NO', 'No');
define('_US_DELACCOUNT', '删除用户');
define('_US_MYAVATAR', '我的头像');
define('_US_UPLOADMYAVATAR', '上传头像');
define('_US_MAXPIXEL', '最大像素');
define('_US_MAXIMGSZ', '图片最大尺寸 (Bytes)');
define('_US_SELFILE', '选择文件');
define('_US_OLDDELETED', '你的旧头像将被删除!');
define('_US_CHOOSEAVT', '在列表中选择头像');
define('_US_SELECT_THEME', '默认主图');
define('_US_SELECT_LANG', '默认语言');

define('_US_PRESSLOGIN', '点击下面的按钮登录');

define('_US_ADMINNO', '管理员组中的用户不能被删除');
define('_US_GROUPS', '用户所在组');

define('_US_YOURREGISTRATION', '你在%s注册');
define('_US_WELCOMEMSGFAILED', '在发送欢迎邮件时发生错误');
define('_US_NEWUSERNOTIFYADMINFAIL', '注册失败，通知管理员');
define('_US_REGFORM_NOJAVASCRIPT', '请开启JavaScript以确保能够正确登录');
define('_US_REGFORM_WARNING', '尽量使用一个安全的密码。尝试使用混合大小写字母、数字和符号来创建密码。密码尽可能复杂，当然你要能记住它');
define('_US_CHANGE_PASSWORD', '更改密码?');
define('_US_POSTSNOTENOUGH', '对不起，需要最少<b>%s</b>条留言，才能更新头像.');
define('_US_UNCHOOSEAVT', '只有达到这个数量，才能从下面表中选择头像');

// openid
define('_US_OPENID_NOPERM', '没有权限');
define('_US_OPENID_FORM_CAPTION', 'OpenID');
define('_US_OPENID_FORM_DSC', '');
define('_US_OPENID_EXISTING_USER', '已存在用户');
define('_US_OPENID_EXISTING_USER_LOGIN_BELOW', '如果你已注册过，请在下面输入用户名和密码以便和OpenID绑定');
define('_US_OPENID_NON_MEMBER_DSC', '如果现在还没有帐号，请输入新帐号的用户名，以便和OpenID绑定');
define('_US_OPENID_YOUR', 'OpenID帐号');
define('_US_OPENID_LINKED_AUTH_FAILED', '不是有效的用户名或密码，请重试');
define('_US_OPENID_LINKED_AUTH_NOT_ACTIVATED', '用户帐户未激活，请激活并重试');
define('_US_OPENID_LINKED_AUTH_CANNOT_SAVE', '对不起，发生错误，用户帐户及授权OpenID不能更新');
define('_US_OPENID_NEW_USER_UNAME_TOO_SHORT', '用户名太短，请重新输入');
define('_US_OPENID_NEW_USER_UNAME_EXISTS', '用户名已存在，请重新输入');
define('_US_OPENID_NEW_USER_CANNOT_INSERT', '对不起，发生错误，不能创建新帐户，请重试');
define('_US_OPENID_NEW_USER_CANNOT_INSERT_INGROUP', '对不起，发生错误，用户不能添加到该组，请联系管理员');
define('_US_OPENID_NEW_USER_AUTH_NOT_ACTIVATED', '最新创建的用户未被激活');
define('_US_OPENID_NEW_USER_CREATED', '新用户 %s 已创建，将自动登录...');
define('_US_OPENID_LINKED_DONE', 'OpenID已与用户名%S绑定，请登录...');
define('_US_ALREADY_LOGED_IN', '你已登录，请登出后再注册');
define('_US_ALLOWVIEWEMAILOPENID', 'OpenID对其他用户可见');
define('_US_SERVER_PROBLEM_OCCURRED', '检测滥发广告黑名单时发生错误!');
define('_US_INVALIDIP', '出错: 你的IP地址不允许注册');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "登录名");
define('_US_OLD_PASSWORD', "旧密码");
define('_US_NICKNAME', '显示昵称');
define('_US_MULTLOGIN', '不能登录!! <br />
        <p align="left" style="color:red;">
           出错原因：<br />
         - 你已登录<br />
         - 已有其他人使用你的用户帐户登录<br />
         - 在你关闭浏览器窗口时，没有登出<br />
        </p>
           请稍等并重试，如果还不能登录，请联系管理员');
define("_US_OPENID_LOGIN", "使用OpenID登录");
define("_US_OPENID_URL", "OpenID URL:");
define("_US_OPENID_NORMAL_LOGIN", "使用常规登录");
