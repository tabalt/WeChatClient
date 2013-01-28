<?php
class WeChatClient {
    
    /**
   * 微信后台填写的 token 用作生成签名
	 * @author gnipt@actphp.com
	 * @var string
	 */
	protected  $token;
	
	/**
	 * 微信推送的消息对象
	 * @author gnipt@actphp.com
	 * @var object
	 */
	public $weChatMessage;
    
    public function __construct($token) {
        $this->token = $token;
    }
    
    /**
     * 微信网址接入
     */
    public function access() {
    	echo $this->getValue('echostr');
    }
    
    /**
     * 回复文本格式消息
     */
    public function replyText($content, $flag = 0) {
    	$messageTpl = <<<EOF
<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[text]]></MsgType>
	<Content><![CDATA[%s]]></Content>
	<FuncFlag>%s</FuncFlag>
</xml>
EOF;
		$this->parseMessage();
		$replyMessage = sprintf($messageTpl, $this->weChatMessage->FromUserName, $this->weChatMessage->ToUserName,  time(), $content, $flag);
		exit($replyMessage);
    }
    
	/**
     * 回复图文格式消息
     */
    public function replyImageText() {
		//TODO 回复图文格式消息
    	
    }
    
	/**
     * 回复地理位置格式消息
     */
    public function replyLocationText() {
		//TODO 回复地理位置格式消息
    	
    }
    
    /**
     * 验证签名
     */
    public function checkSignature(){
    	$signature = $this->getValue('signature');
            
	    $signatureArray = array($this->token, $this->getValue('timestamp'), $this->getValue('nonce'));
	    sort($signatureArray);
	    if( $signature === sha1(implode('', $signatureArray)) ){
	      return true;
	    } else {
	      return false;
	    }
    }
    
    /**
     * 解析微信推送的消息
     * @author gnipt@actphp.com
     */
    public function parseMessage(){
    	if (!is_object($this->weChatMessage)) {
    		$postData = isset($GLOBALS['HTTP_RAW_POST_DATA'])?$GLOBALS['HTTP_RAW_POST_DATA']:'';
    		$this->weChatMessage = simplexml_load_string($postData, 'SimpleXMLElement', LIBXML_NOCDATA);
    	}
    }
    /**
     * 获取微信推送的消息
     * @author gnipt@actphp.com
     */
    public function getWeChatMessage()
    {
    	$this->parseMessage();
    	return $this->weChatMessage;
    }
    
	/**
	 * 从$_GET中取值
	 * @author gnipt@actphp.com
	 * @param string $key
	 * @param var $defaultValue 默认值
	 * @param string $filter 过滤函数，不过滤传false
	 */
	private function getValue($key, $defaultValue = '', $filter = 'htmlspecialchars') {
	    $value = '';
	    if (false === $filter) {
	        $value = isset($_GET[$key]) ? $_GET[$key] : $defaultValue;
	    } else {
	        if (empty($filter)) {
	            $filter = 'htmlspecialchars';
	        }
	        $value = isset($_GET[$key]) ? $filter(trim($_GET[$key])) : $defaultValue;
	    }
	    return $value;
	}
    
}
