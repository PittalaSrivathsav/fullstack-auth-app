<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\TeacherModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends ResourceController
{
    private $key = "this_is_a_very_secret_key_for_jwt_authentication_12345";

    // ✅ Test method
    public function testMethod()
    {
        return "controller working";
    }

    // ✅ LOGIN (Generate Token)
    public function login()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        try {
            $userModel = new UserModel();
            $input = $this->request->getJSON(true);

            if (!$input) {
                return $this->response->setJSON(['error' => 'Invalid input']);
            }

            $user = $userModel->where('email', $input['email'])->first();

            if (!$user || !password_verify($input['password'], $user['password'])) {
                return $this->response->setJSON(['error' => 'Invalid credentials']);
            }

            $payload = [
                "iss" => "localhost",
                "iat" => time(),
                "exp" => time() + 3600,
                "data" => [
                    "id" => $user['id'],
                    "email" => $user['email']
                ]
            ];

            $token = JWT::encode($payload, $this->key, 'HS256');

            return $this->response->setJSON([
                "status" => "success",
                "token" => $token
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                "error" => $e->getMessage()
            ]);
        }
    }
    public function getTeachers()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, OPTIONS");

    $teacherModel = new \App\Models\TeacherModel();
    return $this->response->setJSON($teacherModel->findAll());
}
     public function getUsers()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, OPTIONS");

    $userModel = new \App\Models\UserModel();
    return $this->response->setJSON($userModel->findAll());
}

    public function options()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    exit;
}
    // 🔐 PROTECTED CREATE API
    public function createTeacherWithUser()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        try {
            // 🔐 Get token
            $header = $this->request->getHeaderLine("Authorization");

            if (!$header) {
                return $this->response->setJSON(['error' => 'Token required']);
            }

            $token = str_replace("Bearer ", "", $header);

            // 🔐 Verify token
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));

            // ✅ Continue if token valid
            $userModel = new UserModel();
            $teacherModel = new TeacherModel();

            $input = $this->request->getJSON(true);

            if (!$input) {
                return $this->response->setJSON(['error' => 'Invalid input']);
            }

            // Insert user
            $userId = $userModel->insert([
                'email' => $input['email'],
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'password' => password_hash($input['password'], PASSWORD_DEFAULT)
            ]);

            // Insert teacher
            $teacherModel->insert([
                'user_id' => $userId,
                'university_name' => $input['university_name'],
                'gender' => $input['gender'],
                'year_joined' => $input['year_joined']
            ]);

            return $this->response->setJSON([
                "status" => "success",
                "message" => "Protected API working"
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                "error" => "Invalid or expired token"
            ]);
        }
    }
}