<?php
/*~ class.phpmailer.php
.---------------------------------------------------------------------------.
|  Software: PHPMailer - PHP email class                                    |
|   Version: 5.1                                                            |
|   Contact: via sourceforge.net support pages (also www.worxware.com)      |
|      Info: http://phpmailer.sourceforge.net                               |
|   Support: http://sourceforge.net/projects/phpmailer/                     |
| ------------------------------------------------------------------------- |
|     Admin: Andy Prevost (project admininistrator)                         |
|   Authors: Andy Prevost (codeworxtech) codeworxtech@users.sourceforge.net |
|          : Marcus Bointon (coolbru) coolbru@users.sourceforge.net         |
|   Founder: Brent R. Matzelle (original founder)                           |
| Copyright (c) 2004-2009, Andy Prevost. All Rights Reserved.               |
| Copyright (c) 2001-2003, Brent R. Matzelle                                |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| We offer a number of paid services (www.worxware.com):                    |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'
*/

/**
 * PHPMailer - PHP email transport class
 * NOTE: Requires PHP version 5 or later
 * @package PHPMailer
 * @author Andy Prevost
 * @author Marcus Bointon
 * @copyright 2004 - 2009 Andy Prevost
 * @version $Id: class.phpmailer.php 447 2009-05-25 01:36:38Z codeworxtech $
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
/**
 * 邮件的日志类
 */
class LogMail {
    //邮件的实例
    static private $self = null;

    /**
     * @static
     * @return LogMail
     */
    static public function instance(){
        self::$self===null && self::$self=new LogMail();
        return self::$self;
    }

    private $oPhpMail = null;
    /**
     * @return PHPMailer
     */
    public function PHPMail(){
        if($this->oPhpMail===null){
            if(!class_exists('PHPMailer'))
                include_once realpath(dirname(__FILE__).'/../../BabySitter/ScriptSendEmailClass/class.phpmailer.php');
            if(class_exists('PHPMailer')){
                $oPHPMail = new PHPMailer();
                $oPHPMail->IsSMTP();
                $oPHPMail->SMTPDebug  = false;
                $oPHPMail->CharSet    = 'UTF-8';
                $oPHPMail->Encoding   = 'base64';
                $oPHPMail->SMTPAuth   = true;
                $oPHPMail->Host       = 'mail.sohu.net';
                $oPHPMail->Port       = 25;
                $oPHPMail->Username   = 'xiongmeng@weiboyi.com';
                $oPHPMail->Password   = 'xiongwang2008';
                $oPHPMail->Subject    = '发生错误鸟';
                $oPHPMail->SetFrom('xiongmeng@weiboyi.com');
                $oPHPMail->IsHTML(false);

                $this->oPhpMail = $oPHPMail;
            }
        }
        return $this->oPhpMail;
    }
}