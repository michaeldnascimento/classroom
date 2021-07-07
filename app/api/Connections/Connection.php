<?php

namespace app\api\Connections;

use Google\Exception;
use Google_Client;
use Google_Service_Classroom;

class Connection
{

    private $credentials;
    private $client;
    private $is_connected;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->credentials = "../app/api/Credentials/credentials.json";
        $this->client = $this->create_client();
    }

    public function get_client()
    {
        return $this->client;
    }

    public function get_credentials()
    {
        return $this->credentials;
    }

    public function is_connected()
    {
        return $this->is_connected;

    }

    /**
     * @throws Exception
     */
    public function create_client ()
    {
        $client = new Google_Client();
        $client->setApplicationName('Gmail API PHP Quickstart');
        $client->setScopes(array(
                Google_Service_Classroom::CLASSROOM_COURSES,
                Google_Service_Classroom::CLASSROOM_COURSEWORK_ME,
                Google_Service_Classroom::CLASSROOM_TOPICS,
                Google_Service_Classroom::CLASSROOM_COURSEWORKMATERIALS,
                Google_Service_Classroom::CLASSROOM_ROSTERS,
                Google_Service_Classroom::CLASSROOM_ANNOUNCEMENTS,
                Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS,
                Google_Service_Classroom::CLASSROOM_GUARDIANLINKS_STUDENTS,
                Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS,
                Google_Service_Classroom::CLASSROOM_STUDENT_SUBMISSIONS_ME_READONLY,
                Google_Service_Classroom::CLASSROOM_PUSH_NOTIFICATIONS)
        );
        $client->setAuthConfig(  "../app/api/Credentials/credentials.json");
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = "../app/api/Token/token.json";
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } elseif($this->credentials_in_browser()) {
                $authCode = $_GET['code'];
                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            else {
                $this->is_connected = false;
                return $client;

            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        $this->is_connected = true;
        return $client;

    }



}