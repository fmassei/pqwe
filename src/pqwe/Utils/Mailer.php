<?php
/**
 * Mailer class
 */
namespace pqwe\Utils;

use pqwe\Exception\PqweException;

/**
 * class with some helpers to send emails
 */
class Mailer {
    /**
     * send a simple HTML email
     *
     * @param string $to Destination address
     * @param string $subject EMail subject
     * @param string $message The message in HTML format
     * @param string $from Source address (e.g. no-reply<noreply@pqwe.org>)
     * @return bool The result of the mail() function
     */
    public static function sendHTML($to, $subject, $message, $from) {
        $eol = PHP_EOL;
        $mailHeaders = "Content-Type: text/html; charset=utf-8$eol".
            "MIME-Version: 1.0$eol".
            "From: $from$eol".
            "X-Mailer: PHP$eol";
        return mail($to, $subject, $message, $mailHeaders);
    }

    /**
     * send an HTML email with an attachment
     *
     * @param string $to Destination address
     * @param string $subject EMail subject
     * @param string $message The message in HTML format
     * @param string $from Source address (e.g. no-reply<noreply@pqwe.org>)
     * @param string $file The file path
     * @return bool The result of the mail() function
     * @deprecated will be renamed soon
     */
    public static function sendMultipart($to, $subject, $message, $from, $file) {
        $filename = basename($file);
        $file_size = filesize($file);
        $file_content = file_get_contents($file);
        $file_content = chunk_split(base64_encode($file_content));
        $separator = md5(time());
        $eol = PHP_EOL;
        $headers = "From: $from$eol".
            "MIME-Version: 1.0$eol".
            "Content-Type: multipart/mixed; boundary=\"$separator\"$eol".
            "Content-Transfer-Encoding: 7bit$eol$eol".
            "This is a MIME encoded message.$eol$eol".

            "--$separator$eol".
            "Content-Type: text/html; charset=utf-8$eol".
            "Content-Transfer-Encoding: 8bit$eol$eol".
            "$message$eol$eol".

            "--$separator$eol".
            "Content-Type: application/octet-stream; name=\"$filename\"$eol".
            "Content-Transfer-Encoding: base64$eol".
            "Content-Disposition: attachment$eol$eol".
            "$file_content$eol$eol".
            "--$separator--";
        return mail($to, $subject, "", $headers);
    }

    /**
     * Send an email with both text and html
     *
     * @param string $to Destination address
     * @param string $subject EMail subject
     * @param string $from Source address (e.g. no-reply<noreply@pqwe.org>)
     * @param string $text The message in plain text
     * @param string $html The message in HTML format
     * @return bool The result of the mail() function
     */
    public static function sendTextAndHtml($to, $subject, $from, $text, $html)
    {
        $text = quoted_printable_encode($text);
        $html = quoted_printable_encode($html);
        $separator = md5(time());
        $eol = PHP_EOL;
        $headers = "From: $from$eol".
            "MIME-Version: 1.0$eol".
            "Content-Type: multipart/alternative; boundary=\"$separator\"$eol".

            "--$separator$eol".
            "Content-Transfer-Encoding: quoted-printable$eol".
            "Content-Type: text/plain; charset=utf-8$eol".
            "Mime-Version: 1.0$eol$eol".
            "$text$eol$eol".

            "--$separator$eol".
            "Content-Transfer-Encoding: quoted-printable$eol".
            "Content-Type: text/html; charset=utf-8$eol".
            "Mime-Version: 1.0$eol$eol".
            "$html$eol$eol".

            "--$separator--";
        return mail($to, $subject, "", $headers);
    }
}

