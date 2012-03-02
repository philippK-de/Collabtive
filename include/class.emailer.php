<?php
/**
 * This class provides methods to handle emailing
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name emailer
 * @version 0.4.8
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class emailer
{
    private $from;
    private $mailsettings;

    function __construct($settings)
    {
        $this->mailsettings = $settings;
    }


    /**
     * Send an email to a member
     *
     * @param string $to Recipient's email address
     * @param string $subject Subjectline of the mail
     * @param string $text Textbody of the mail, HTML allowed
     * @return bool
     */
    function send_mail($to, $subject, $text)
    {
		//create PHP Mailer object
		$mailer = (object) new PHPmailer();
		//setup PHPMailer
		$mailer->From = $this->mailsettings["mailfrom"];
		$mailer->Sender = $this->mailsettings["mailfrom"];
		$mailer->FromName = $this->mailsettings["mailfromname"];
		$mailer->AddAddress($to);
		$mailer->Subject = $subject;
		$mailer->Body = $text;
		//send mail as HTML
		$mailer->IsHTML(true);
		//set charset
		$mailer->CharSet = "utf-8";
		//set mailing method... mail, smtp or sendmail
		$mailer->Mailer = $this->mailsettings["mailmethod"];
		//if it's smtp , set the smtp server
		if($this->mailsettings["mailmethod"] == "smtp")
		{
			$mailer->Host = $this->mailsettings["mailhost"];
			//setup SMTP auth
			if($this->mailsettings["mailuser"] and $this->mailsettings["mailpass"])
			{
				$mailer->Username = $this->mailsettings["mailuser"];
				$mailer->Password = $this->mailsettings["mailpass"];
				$mailer->SMTPAuth = true;
			}

		}

        if ($mailer->Send())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>