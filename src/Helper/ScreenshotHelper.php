<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 30-7-2018
 * Time: 16:47
 */

namespace Stormyy\B3\Helper;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Stormyy\B3\Models\Screenshot;

class ScreenshotHelper {

    /**
     * @param Screenshot $screenshot
     * @return array
     */
    static function predict(Screenshot $screenshot){
        $client = new Client();

        try {
            $response = json_decode($client->request('POST', 'https://europe-west1-luvclan-196301.cloudfunctions.net/ScreenshotChecker', [
                'json' => ['image' => base64_encode(\Storage::disk('screenshots')->get($screenshot->filename))]
            ])->getBody()->getContents());

            $prediction =  $response->payload[0];

            return ['label' => $prediction->displayName, 'score' => $prediction->classification->score];

        } catch (GuzzleException $e) {


            return null;
        }



    }

}