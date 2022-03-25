<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Repository
{
    /**
     * @throws GuzzleException
     */
    public function httpGet($url): ResponseInterface
    {

        $client = new Client([
            // Base URI is used with relative requests
            "base_uri" => "https://www.anapioficeandfire.com/api/",
            // You can set any number of default request options.
            "timeout"  => 20.0,
        ]);


        return $client->request("GET", $url);
    }

    /**
     * @throws Exception
     */
    public function handler($response)
    {
        if ($response->getStatusCode() == 200) {

            return json_decode($response->getBody());
        } else {
            throw new Exception('Server error');
        }
    }


    //Getters for book

    public function getBookByID($book_id)
    {
        $path = "books/$book_id";
        return $this->handler($this->httpGet($path));
    }

    public function getBookByName($book_name)
    {
        $path = "books/?name=$book_name";
        return $this->handler($this->httpGet($path));
    }

    public function getBookByFromReleaseDate($fromReleaseDate)
    {
        $path = "books/?fromReleaseDate=$fromReleaseDate";
        return $this->handler($this->httpGet($path));
    }

    public function getAllBooks()
    {
        $path = 'books';
        return $this->handler($this->httpGet($path));
    }

    //Getters for Character

    public function getCharacterByID($char_id)
    {
        $path = "characters/$char_id";
        return $this->handler($this->httpGet($path));
    }

    public function getCharacterByName($char_name)
    {
        $path = "characters/?name=$char_name";
        return $this->handler($this->httpGet($path));
    }

    public function getAllCharacters()
    {
        $path = "characters";
        return $this->handler($this->httpGet($path));
    }

    public function getCharactersByCulture($culture_name)
    {
        $path = "characters/?culture=$culture_name";
        return $this->handler($this->httpGet($path));
    }

    public function getCharactersByGender($gender)
    {
        $path = "characters/?gender=$gender";
        return $this->handler($this->httpGet($path));
    }
}
