<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *	   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package log4php
 */

/**
 * Appends log events to mail using php function {@link PHP_MANUAL#mail}.
 *
 * The appender sends all log events at once after the request has been
 * finsished and the appender is beeing closed.
 *
 * Configurable parameters for this appender:
 * 
 * - layout             - Sets the layout class for this appender (required)
 * - to                 - Sets the recipient of the mail (required)
 * - from               - Sets the sender of the mail (optional)
 * - subject            - Sets the subject of the mail (optional)
 * 
 * An example:
 * 
 * {@example ../../examples/php/appender_mail.php 19}
 * 
 * {@example ../../examples/resources/appender_mail.properties 18}
 * 
 * The above will output something like:
 * <pre>
 *      Date: Tue,  8 Sep 2009 21:51:04 +0200 (CEST)
 *      From: someone@example.com
 *      To: root@localhost
 *      Subject: Log4php test
 *      
 *      Tue Sep  8 21:51:04 2009,120 [5485] FATAL root - Some critical message!
 *      Tue Sep  8 21:51:06 2009,120 [5485] FATAL root - Some more critical message!
 * </pre>

 * @version $Revision: 1213283 $
 * @package log4php
 * @subpackage appenders
 */
class LoggerAppenderSmsWBY extends LoggerAppender {

    /**
     * Buffer which holds the email contents before it is sent.
     * @var string
     */
    protected $msg = '';

    public function append(LoggerLoggingEvent $event) {
        if($this->layout !== null) {
            $this->msg .= $this->layout->format($event);
        }
    }

    /**
     * 用微博易的邮件发送
     */
    public function close() {
		if($this->closed != true) {
			$from = $this->from;
			$to = $this->to;

			if(!empty($this->body) and $from !== null and $to !== null and $this->layout !== null) {
				if(!$this->dry) {
                    /**
                     * TODO 还未实现 发送短信代码
                     */
				} else {
				    echo "DRY MODE OF MAIL APP.: Send mail to: ".$to." with content: ".$this->body;
				}
			}
			$this->closed = true;
		}
	}

    /**
     * The host of the email.
     * @var string
     */
    protected $phone = '18611367408';
    /** Sets the 'phone' parameter. */
    public function setPhone($phone) {
        $this->setString('phone', $phone);
    }
    /** Returns the 'phone' parameter. */
    public function getPhone() {
        return $this->phone;
    }
}
