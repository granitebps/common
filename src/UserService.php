<?php

namespace Microservices;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class UserService
{
    private $endpoint;

    public function __construct()
    {
        $this->endpoint = env('USERS_ENDPOINT');
    }

    public function headers()
    {
        return [
            'Authorization' => 'Bearer ' . request()->cookie('jwt'),
        ];
    }

    public function request()
    {
        return Http::withHeaders($this->headers());
    }

    public function getUser(): User
    {
        $response = $this->request()
            ->get($this->endpoint . '/user');

        $json = $response->json();

        return new User($json);
    }

    public function isAdmin()
    {
        return $this->request()
            ->get($this->endpoint . '/admin')
            ->successful();
    }

    public function isInfluencer()
    {
        return $this->request()
            ->get($this->endpoint . '/influencer')
            ->successful();
    }

    public function allows($ability, $argument)
    {
        return Gate::forUser($this->getUser())->authorize($ability, $argument);
    }

    public function all($page)
    {
        return $this->request()
            ->get($this->endpoint . '/users?page=' . $page)
            ->json();
    }

    public function get($id): User
    {
        $json = $this->request()
            ->get($this->endpoint . '/users/' . $id)
            ->json();
        return new User($json);
    }

    public function create($data)
    {
        $json = $this->request()
            ->post($this->endpoint . '/users', $data)
            ->json();
        return new User($json);
    }

    public function update($id, $data)
    {
        $json = $this->request()
            ->put($this->endpoint . '/users/' . $id, $data)
            ->json();
        return new User($json);
    }

    public function delete($id)
    {
        return $this->request()
            ->delete($this->endpoint . '/users/' . $id)
            ->successful();
    }
}
