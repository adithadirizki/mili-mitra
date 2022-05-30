<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->has('isLoggedIn')) {
            return redirect()->to('/');
        }

        $method = $this->request->getMethod();

        if ($method === "get") {
            $data = [
                "title" => "Login"
            ];
            return view('auth/login', $data);
        } elseif ($method === "post") {
            $isValidRules = $this->validate([
                "userid" => [
                    "label" => "User ID",
                    "rules" => "required",
                ],
                "password" => [
                    "label" => "Password",
                    "rules" => "required"
                ]
            ]);

            $session = session();

            if (!$isValidRules) {
                $session->setFlashdata("errors", $this->validator->getErrors());
                return redirect()->back();
            }

            $userid = $this->request->getPost("userid");
            $password = $this->request->getPost("password");

            $userModel = new UserModel();
            $user = $userModel->findUser($userid, $password);

            if (!$user) {
                $session->setFlashdata("message", [
                    "status" => false,
                    "text" => "User ID atau Password salah."
                ]);
                return redirect()->back();
            }

            $session->set([
                "agen_id" => $user->agenid,
                "agen_name" => $user->nama,
                "agen_host" => $_SERVER['REMOTE_ADDR'],
                "browser" => $_SERVER['HTTP_USER_AGENT'],
                "expire" => time() + (60 * 60),
                "agen_tipe" => $user->kelompok,
                "agen_level" => $user->level,
                "agen_akses" => $user->tipe,
                "agen_up" => $user->up,
                "agen_hp" => $user->hp,
                "isLoggedIn" => true
            ]);

            $session->setFlashdata("message", [
                "status" => true,
                "text" => "Berhasil login."
            ]);
            return redirect()->to('/');
        }
    }

    public function changePassword()
    {
        $method = $this->request->getMethod();

        if ($method === "get") {
            $data = [
                "title" => "Ganti Password",
                "nav_active" => "change-password"
            ];
            return view('ganti-password', $data);
        } elseif ($method === "post") {
            $isValidRules = $this->validate([
                'old_password' => [
                    'label' => 'Password Lama',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi.'
                    ]
                ],
                'new_password' => [
                    'label' => 'Password Baru',
                    'rules' => 'required|min_length[6]|is_alnum',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'min_length' => '{field} minimal {param} karakter.',
                        'is_alnum' => '{field} harus kombinasi huruf & angka.'
                    ]
                ],
                'confirm_password' => [
                    'label' => 'Konfirmasi Password',
                    'rules' => 'required|min_length[6]|is_alnum|matches[new_password]',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'min_length' => '{field} minimal {param} karakter.',
                        'is_alnum' => '{field} harus kombinasi huruf & angka.',
                        'matches' => '{field} tidak cocok.'
                    ]
                ]
            ]);

            $session = session();

            if (!$isValidRules) {
                $session->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back();
            }

            $old_password = $this->request->getPost('old_password');
            $new_password = $this->request->getPost('new_password');
            $hash_password = password_hash($new_password, PASSWORD_BCRYPT);

            $userModel = new UserModel();
            $user = $userModel->getUser();

            if (!password_verify($old_password, $user->password)) {
                $session->setFlashdata('message', [
                    'status' => false,
                    'text' => 'Password Lama salah'
                ]);
                return redirect()->back();
            }

            $isSuccess = $userModel->updatePassword($hash_password);
            if (!$isSuccess) {
                $session->setFlashdata('message', [
                    'status' => false,
                    'text' => 'Gagal mengubah passsword!'
                ]);
                return redirect()->back();
            }

            $session->setFlashdata('message', [
                'status' => true,
                'text' => 'Password berhasil diubah!'
            ]);
            return redirect()->back();
        }
    }

    public function changePIN()
    {
        $isValidRules = $this->validate([
            'old_pin' => [
                'label' => 'PIN Lama',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'new_pin' => [
                'label' => 'PIN Baru',
                'rules' => 'required|exact_length[4]|is_numeric',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'exact_length' => '{field} harus {param} angka.',
                    'is_numeric' => '{field} harus berisi angka.'
                ]
            ],
            'confirm_pin' => [
                'label' => 'Konfirmasi PIN',
                'rules' => 'required|exact_length[4]|is_numeric|matches[new_pin]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'exact_length' => '{field} harus {param} angka.',
                    'is_numeric' => '{field} harus berisi angka.',
                    'matches' => '{field} tidak cocok.'
                ]
            ]
        ]);

        $session = session();

        if (!$isValidRules) {
            $session->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back();
        }

        $old_pin = $this->request->getPost('old_pin');
        $new_pin = $this->request->getPost('new_pin');

        $userModel = new UserModel();
        $user = $userModel->getUser();

        if ($old_pin !== $user->pin) {
            $session->setFlashdata('message', [
                'status' => false,
                'text' => 'PIN Lama salah'
            ]);
            return redirect()->back();
        }

        $isSuccess = $userModel->updatePIN($new_pin);
        if (!$isSuccess) {
            $session->setFlashdata('message', [
                'status' => false,
                'text' => 'Gagal mengubah PIN!'
            ]);
            return redirect()->back();
        }

        $session->setFlashdata('message', [
            'status' => true,
            'text' => 'PIN berhasil diubah!'
        ]);
        return redirect()->back();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
