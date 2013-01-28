<?php
	require './WeChatClient.class.php';
	$token = 'token string';
    $WeChatClient = new WeChatClient();
    define('ADMIN_USER_KEY', 'admin user key');
    define('ADMIN_COMMAND', 'admin_command');

	if(isset($_GET['echostr'])) {
		$WeChatClient->access();
	} else {
		if($WeChatClient->checkSignature()){
			$weChatMessage = $WeChatClient->getWeChatMessage();
			
			if($weChatMessage->Content === ADMIN_COMMAND) {
				if($weChatMessage->FromUserName == ADMIN_USER_KEY) {
                        //管理员操作
                        $message = "欢迎您，管理员";
						$WeChatClient->replyText($message);
				} else {
					//非法请求
				}
			} else if($weChatMessage->Content == '聊天'){
                $WeChatClient->replyText("你好，欢迎你和我聊天！");
			}
		}
	}
