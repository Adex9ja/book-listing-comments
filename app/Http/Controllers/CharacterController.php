<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Models\CustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterController
{
    private Repository $api;

    public function __construct()
    {
        $this->api = new Repository();
    }
    public function getCharacters(Request $request): JsonResponse
    {
        try {
            $characters = [];
            if ($request->query("name")) {
                $name = $request->query("name");
                $characters = $this->api->getCharacterByName($name);
            } else if ($request->query("culture")) {

                $culture = $request->query("culture");
                $characters = $this->api->getCharactersByCulture($culture);
            } else if ($request->query("gender")) {

                $gender = $request->query("gender");
                $characters = $this->api->getCharactersByGender($gender);
            } else if ($request->query("age")) {

                $age = $request->query("age");
                $response = $this->api->getAllCharacters();

            } else {

                $characters = $this->api->getAllCharacters();
            }



            if ($request->query("order") == 'DESC') {
                $characters = array_reverse($characters);
            }



            $data = [
                "meta" => [
                    "total_characters" => count($characters),
                    "total_age" =>  NULL
                ],
                "data" => $characters
            ];


            return response()->json(new CustomResponse("Character(s) available", $data));
        } catch (\Exception  $e) {
            return response()->json(new CustomResponse($e->getMessage(), null, 500), 500);
        }
    }

    public function getCharacterById($id): JsonResponse
    {
        try {
            $character = $this->api->getCharacterByID($id);

            $data = [
                "meta" => [
                    "total_characters" => count($character),
                    "total_age" =>  NULL
                ],
                "data" => $character
            ];


            return response()->json(new CustomResponse("Character(s) available", $data));
        } catch (\Exception  $e) {
            return response()->json(new CustomResponse($e->getMessage(), null, 500), 500);
        }
    }
}
