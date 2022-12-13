<?php

namespace App\Services\ApiNotification;

use Exception;
use GuzzleHttp\Client;

/**
 * Created by Deyan Ardi 2022.
 * API Services connect to http://sv1.notif.ganadev.com.
 */
class ApiNotificationCommandServices
{
    public function sendMailMessage($to, $subject, $text)
    {
        try {
            $data = [
                'apiToken' => config('general.api_token'),
                'to' => $to,
                'subject' => $subject,
                'html' => $text,
            ];
            $url = config('general.api_url').'/email/send/message';
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
                'apiToken' => config('general.api_token'),
                'to' => $to,
                'subject' => $subject,
                'html' => $text,
                'filename' => $filename,
                'link' => $link,
            ];
            $url = config('general.api_url').'/email/send/media';
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
                'apiToken' => config('general.api_token'),
                'no_hp' => intval('62'.$receiver), //include string 62 to the front of user's phone number
                'pesan' => $message,
            ];
            $url = config('general.api_url').'/whatsapp/send/message';
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
                'apiToken' => config('general.api_token'),
                'no_hp' => (int) '62'.$receiver, //include string 62 to the front of user's phone number
                'pesan' => $message,
                'link' => $file,
            ];
            $url = config('general.api_url').'/whatsapp/send/media';
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
                'apiToken' => config('general.api_token'),
            ];
            $url = config('general.api_url').'/target-api/single';
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
                'apiToken' => config('general.api_token'),
            ];
            $url = config('general.api_url').'/app-access/single';
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
