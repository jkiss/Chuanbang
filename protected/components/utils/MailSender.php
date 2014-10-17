<?php
/**
 * Created by PhpStorm.
 * User: gracier11
 * Date: 14-4-19
 * Time: 上午12:09
 */

class MailSender {

    private $mail;

    private $_to;
    private $_subject;
    private $_content;

    private $_error;

    public function __construct() {
        $this->mail = Yii::createComponent ( 'application.extensions.mailer.EMailer' );
        $this->mail->IsSMTP ();
        $this->mail->Host = 'smtp.163.com';
        $this->mail->Port = 25;
        $this->mail->SMTPAuth = true;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Username = 'hxfeng86@163.com';
        $this->mail->Password = 'hxfeng0401';

        $this->mail->SetFrom ( $this->mail->Username, 'dotfive.cn' );
    }

    public function setTo($to) {
        $this->_to = $to;
    }

    public function setSubject($subject) {
        $this->_subject = $subject;
    }

    public function setContent($content) {
        $this->_content = $content;
    }

    public function getError() {
        return $this->_error;
    }

    public function send($to = '', $subject = '', $content = '') {
        if(!empty($to)) $this->setTo($to);
        if(!empty($subject)) $this->setSubject($subject);
        if(!empty($content)) $this->setContent($content);
        $this->mail->AddAddress($this->_to, $this->_to);
        $this->mail->Subject = $this->_subject;
        $this->mail->MsgHTML($this->_content);
        if(!$this->mail->Send()) {
            $this->_error = $this->mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    }
}