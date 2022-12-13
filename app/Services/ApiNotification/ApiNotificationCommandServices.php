<?php

namespace App\Services\ApiNotification;

use Exception;
use GuzzleHttp\Client;

class ApiNotificationCommandServices
{
    public function sendMailMessage($to, $subject, $text)
    {
        try {
            $data = [
                'apiToken' => "ganadev-7491dffb-c997-45d1-ad01-8b4a5a1837d1",
                'to' => $to,
                'subject' => $subject,
                'html' => $text,
            ];
            $url = 'http://sv1.notif.ganadev.com/email/send/message';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
            ];

            return $info;
        }
    }

    public function sendMailMedia($to, $subject, $text, $filename, $link)
    {
        try {
            $data = [
                'apiToken' => "ganadev-7491dffb-c997-45d1-ad01-8b4a5a1837d1",
                'to' => $to,
                'subject' => $subject,
                'html' => $text,
                'filename' => $filename,
                'link' => $link,
            ];
            $url = 'http://sv1.notif.ganadev.com/email/send/media';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
            ];

            return $info;
        }
    }

    public function sendWaMessage($receiver, $message)
    {
        try {
            $data = [
                'apiToken' => "ganadev-7491dffb-c997-45d1-ad01-8b4a5a1837d1",
                'no_hp' => intval('62'.$receiver), //include string 62 to the front of user's phone number
                'pesan' => $message,
            ];
            $url = 'http://sv1.notif.ganadev.com/whatsapp/send/message';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );

            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
            ];

            return $info;
        }
    }

    public function sendWaMedia($receiver, $file, $message)
    {
        try {
            $data = [
                'apiToken' => "ganadev-7491dffb-c997-45d1-ad01-8b4a5a1837d1",
                'no_hp' => (int) '62'.$receiver, //include string 62 to the front of user's phone number
                'pesan' => $message,
                'link' => $file,
            ];
            $url = 'http://sv1.notif.ganadev.com/whatsapp/send/media';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
            ];

            return $info;
        }
    }

    public static function getSingleDevice()
    {
        try {
            $data = [
                'apiToken' => "ganadev-7491dffb-c997-45d1-ad01-8b4a5a1837d1",
            ];
            $url = 'http://sv1.notif.ganadev.com/target-api/single';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
            ];

            return $info;
        }
    }

    public function getStatusApp()
    {
        try {
            $data = [
                'apiToken' => "ganadev-7491dffb-c997-45d1-ad01-8b4a5a1837d1",
            ];
            $url = 'http://sv1.notif.ganadev.com/app-access/single';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
            ];

            return $info;
        }
    }
}
