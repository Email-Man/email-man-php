<?php

namespace EmailMan;

define("CRLF", "\r\n");
define("NEWLINE", "\r\n");

class EmailMan
{
    private $to = "";
    private $name = "";
    private $from = "";
    private $cc = [];
    private $bcc = [];
    private $isHtml = false;
    private $wordWrap = [
        "state" => true,
        "length" => 70
    ];
    private $replyTo = "";
    private $headers = [];
    private $header = "";
    private $message = "";
    private $subject = "";
    private $debugMessage = [];

    /**
     * sends the email
     * @param bool $debugMode true to enable debug mode and false to disable debug mode.
     *  By default, it's set to false
     * @return array|bool|void returns an array which is the debug message or returns a
     * boolean value depending on the outcome of the event
     */
    public function send(bool $debugMode = false)
    {
        if (!empty($this->from)) {
            if (!empty($this->subject)) {
                if (!empty($this->message)) {
                    if (!empty($this->header)) {
                        $sent = mail($this->to, $this->subject, $this->message, $this->header);
                        if ($sent) {
                            return true;
                        } else {
                            if ($debugMode) {
                                return $this->dumpDebugger();
                            } else {
                                return false;
                            }
                        }
                    } else {
                        $this->debugMessage["error"] = "**Header** not set!";
                    }
                } else {
                    $this->debugMessage["error"] = "**Message** not set!";
                }
            } else {
                $this->debugMessage["error"] = "**Subject** not set!";
            }
        } else {
            $this->debugMessage["error"] = "**From** not set!";
        }

    }

    /**
     * sets define whether the email is and html email or text.
     * @param bool $state define the html state true of false.
     * it's false by default
     * @return void
     */
    public function setHtml(bool $state = false): void
    {
        $this->isHtml = $state;
    }

    /**
     * set recipient address
     * @param array $sendTo email addresses to send to
     * @return void
     */
    public function setTo(array $sendTo): void
    {
        if (!empty($to)) {
            $this->to .= ", " . implode(', ', $sendTo);
        } else {
            $this->to = implode(', ', $sendTo);
        }
    }

    /**
     * add recipient email address
     * @param string $sendTo recipient email address
     * @param string $name name of the recipient
     * @return void
     */
    public function addTo(string $sendTo, string $name = ""): void
    {
        if ($this->validateAddress($sendTo)) {
            if (!empty($to)) {
                if (isset($name)) {
                    $this->to .= ", $name <$sendTo>";
                } else {
                    $this->to .= ", " . $sendTo;
                }
            } else {
                if (isset($name)) {
                    $this->to = "$name <$sendTo>";
                } else {
                    $this->to = $sendTo;
                }
            }
        } else {
            $this->debugMessage["addTo"]["error"] = "Invalid Email Format!";
        }
    }

    /**
     * set name of sender
     * @param string $sendName name of sender
     * @return void
     */
    public function setFromName(string $sendName): void
    {
        $this->name = trim($sendName);
    }

    /**
     * set sender address
     * @param string $sendFrom email address to send from
     * @return void
     */
    public function setFrom(string $sendFrom): void
    {
        $this->from = trim($sendFrom);
    }

    /**
     * set carbon copy
     * @param string | array $sendCC list of email addresses to receive a carbon copy of the email
     * @return void
     */
    public function setCC($sendCC): void
    {
        if (is_array($sendCC)) {
            $this->cc = $sendCC;
        } else {
            $this->cc[] = $sendCC;
        }
    }

    /**
     * set blind carbon copy
     * @param string | array $sendBCC list of emails addresses to receive a blind carbon copy of the email
     * @return void
     */
    public function setBCC($sendBCC): void
    {
        if (is_array($sendBCC)) {
            $this->bcc = $sendBCC;
        } else {
            $this->bcc[] = $sendBCC;
        }
    }

    /**
     * add recipient for carbon copy
     * @param string $sendCC address of the recipient
     * @param string $name name of carbon copy recipient
     * @return void
     */
    public function addCC(string $sendCC, string $name = ""): void
    {
        if ($this->validateAddress($sendCC)) {
            if (isset($name)) {
                $this->cc[] = "$name <$sendCC>'";
            } else {
                $this->cc[] = $sendCC;
            }

        } else {
            $this->debugMessage["addCC"]["error"] = "Invalid Email Format!";
        }
    }

    /**
     * add recipient for blind carbon copy
     * @param string $sendBCC address of the recipient
     * @param string $name name of blind carbon copy recipient
     * @return void
     */
    public function addBCC(string $sendBCC, string $name = ""): void
    {
        if ($this->validateAddress($sendBCC)) {
            $this->bcc[] = "$name <$sendBCC>";
        } else {
            $this->debugMessage["addBCC"]["error"] = "Invalid Email Format!";
        }
    }

    /**
     * prepares the headers for the email
     * @return void
     */
    public function prepareHeaders(): void
    {

        $this->headers = [];

        if (empty($this->name)) {
            $this->headers[] = "From: $this->from";
        } else {
            $this->headers[] = "From: $this->name <$this->from>";
        }

        if (!empty($this->cc)) {
            $this->headers[] = "Cc: " . implode(", ", $this->cc);
        }

        if (!empty($this->bcc)) {
            $this->headers[] = "Bcc: " . implode(", ", $this->bcc);
        }

        $this->headers[] = "MIME-Version: 1.0";

        if (isset($this->replyTo)) {
            $this->headers[] = "Reply-To: " . $this->replyTo;
        }

        if ($this->isHtml) {
            $this->headers[] = "Content-type: text/html; charset=UTF-8";
        } else {
            $this->headers[]  = "Content-Type: text/plain; charset=utf-8";
        }

        $this->headers[] = "X-Mailer: PHP/" . phpversion();

        $this->setHeader();
    }

    /**
     * set header for the email
     * @return void
     */
    private function setHeader(): void
    {
        $this->header = implode(CRLF, $this->headers);
    }

    /**
     * enable or disable word wrap for the content and sets the length of the
     * content. By default, it's set to true and the length
     * is set to 70 characters
     * @param bool $state state of word wrap
     * @param int $length length of word wrap
     * @return void
     */
    public function setWordWrap(bool $state, int $length): void
    {
        $this->wordWrap["state"] = $state;
        $this->wordWrap["length"] = $length;
    }

    /**
     * sets the message to be sent
     * @param string $message message to be sent
     * @return void
     */
    public function setMessage(string $message): void
    {
        if ($this->wordWrap["state"]) {
            $this->message = wordwrap(
                $message,
                $this->wordWrap["length"],
                NEWLINE
            );
        }
    }

    /**
     * returns the debug messages
     * @return array
     */
    public function dumpDebugger(): array
    {
        return $this->debugMessage;
    }

    /**
     * set the subject of the email
     * @param string $subject subject of the email
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->subject = trim($subject);
    }

    /**
     * set email address to reply to. It is usually the sender email
     * @param string $replyToAddress email address of the reply
     * @return void
     */
    public function setReplyTo(string $replyToAddress): void
    {
        $this->replyTo = trim($replyToAddress);
    }

    /**
     * validates the email format of the provided email address
     * @param string $address email address to validated
     * @return bool
     */
    public function validateAddress(string $address): bool
    {
        if (preg_match(
            "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/",
            $address)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * resets all the attributes of the class
     * @return void
     */
    public function reset()
    {
        foreach ($this as $key => $value) {
            if ($key == "wordWrap") {
                $this->$key = [
                    "state" => true,
                    "length" => 70
                ];
            } else if (is_array($this->$key)) {
                $this->$key = [];
            } else {
                $this->$key = null;
            }
        }
    }
}