	<?php
	class httpconnector{
		/**Curl类
		 *
		 */
		private $curl;
		/**cookie字符串
		 */
		private $cookie = '';
		/**源(用于最后结果调试)
		 */

		public function getSource(){
			return $this->sourceWmlStack;
		}
		//取cookie
		
		public function getCookie() {
			return ($this->cookie ==='') ? false : $this->cookie;
		
		}
		
		/**get方式下载网页内容
		 *@param $url
		 **@param $follow_location 是否跟随链接
		 *@return web conntent
		 */
		public function get($url,$follow_location=true){

			$this->curl = curl_init();
				
			curl_setopt($this->curl, CURLOPT_URL, $url);
			
			// 设置header
			curl_setopt($this->curl, CURLOPT_HEADER, 1);
			
			//个人以为应该在此判断$this->cookie是否为空再执行
			//The contents of the "Cookie: " header to be used in the HTTP request. Note that multiple cookies are separated with a semicolon followed by a space (e.g., "fruit=apple; colour=red")
			curl_setopt ($this->curl, CURLOPT_COOKIE , $this->cookie);
			
			// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->curl,CURLOPT_CONNECTTIMEOUT,5);
			curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION,$follow_location);
			// 运行cURL，请求网页
			$data = curl_exec($this->curl);
			// 关闭URL请求
			curl_close($this->curl);
			//找到cookie 放入cookiestring
	/*		
			preg_match("/Set-Cookie:(.*?);/",$data,$r);
				
			if(@$r[1]!=""){
				if($this->cookie==""){
					$this->cookie .= str_replace("\r\n","",$r[1]);
				}
			}
	*/
			$this->set_cookie($data);
			/*
			log_message('debug','抓取url: '.$url);
			log_message('debug','此时cookie: '.$this->cookie);
			log_message('debug','抓取返回data: '."\n".$data);
			*/


			//log_message('debug',$data);
			return $data;

		}

		/**POST方式下载网页内容
		 *@param $url
		 *@param $params post的信息串
		 *@param $follow_location 是否跟随链接
		 *@return web conntent
		 */
		public function post($url,$params,$follow_location=true){
				
			$this->curl = curl_init();
				
			curl_setopt($this->curl, CURLOPT_URL, $url);
				
			// 设置header
			curl_setopt($this->curl, CURLOPT_HEADER, 1);
				
			curl_setopt ($this->curl, CURLOPT_COOKIE , $this->cookie);//发送带Cookie的请求
				
			curl_setopt($this->curl, CURLOPT_POST, 1);
			
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);

			// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->curl,CURLOPT_CONNECTTIMEOUT,5);
			curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION,$follow_location);
			// 运行cURL，请求网页
			$data = curl_exec($this->curl);
				
			// 关闭URL请求
			curl_close($this->curl);
			//找到cookie 放入cookiestring
			$this->set_cookie($data);
			/*
			log_message('debug','POST提交url : '.$url);
			log_message('debug','提交参数: '.$params);
			log_message('debug','此时cookie'.$this->cookie);
			log_message('debug','抓取返回data: '."\n".$data);
			
				
			//放入调试栈
			array_push($this->sourceWmlStack,$data);
			*/	
			return $data;

		}
		
		//添加cookie
		private function set_cookie ($data) 
		{
			$m = preg_match_all('#Set-Cookie:(.*)\s#',$data,$matches);
			if($m) {
				$arr = implode('; ',array_map('trim',$matches[1]));
				//var_dump($matches);
				$arr2 = explode('; ',$arr);
				$arr2 = array_unique($arr2,SORT_STRING );
				foreach ($arr2 as $k=>$v) {
					//如果在原cookie中存在字段，不加入
					$v = trim($v);
					if(strpos($this->cookie,$v) !==FALSE) {
						unset($arr2[$k]);
					}
				}
				//附加到原cookie中
				if (count($arr2)>0){
					$this->cookie .=  '; ' . implode('; ',$arr2);
				}	
				
			}
			$this->cookie = trim($this->cookie,'; ');
			$this->cookie = trim($this->cookie,' ');
			return $this;	
		
		}
		
	}
