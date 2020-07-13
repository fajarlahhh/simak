<?php

namespace App;

class PushNotification
{
    private $id;
    private $pesan;
    private $judul;

    public function __construct($id, $pesan, $judul)
    {
        $this->id = $id;
        $this->ipesand = $pesan;
        $this->judul = $judul;
    }

    function send()
    {
        $fields = array(
            'app_id' => "17d7c4b9-f48c-45f9-98f7-e9a5eacf67d2",
            'include_player_ids' => $this->id,
            'contents' => array(
                "en" => $pesan
            ),
            'headings' => array("en" => $judul),
            'small_icon' => "mipmap/ic_logo"
        );
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NGEwMGZmMjItY2NkNy0xMWUzLTk5ZDUtMDAwYzI5NDBlNjJj'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}

