<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Validator;
use App\Helpers\CsrfHelper;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $this->render('auth/login.twig');
    }

    public function login()
    {
        try {
            CsrfHelper::verifyToken($_POST['csrf_token']);

            $validator = new Validator();
            $rules = [
                'email' => 'required|email',
                'password' => 'required'
            ];

            if ($validator->validate($_POST, $rules)) {
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                $user = User::where('email', $email)->first();

                if ($user && password_verify($password, $user->password)) {
                    $_SESSION['user_id'] = $user->id;
                    header('Location: /dashboard');
                    exit;
                } else {
                    throw new \Exception('Credenciales inválidas');
                }
            } else {
                $this->render('auth/login.twig', ['errors' => $validator->getErrors()]);
            }
        } catch (\Exception $e) {
            $this->render('auth/login.twig', ['error' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function showRegistrationForm()
    {
        $this->render('auth/register.twig');
    }

    public function register()
    {
        try {
            CsrfHelper::verifyToken($_POST['csrf_token']);

            $validator = new Validator();
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'role_id' => 'required|numeric'
            ];

            if ($validator->validate($_POST, $rules)) {
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);
                $sub_empresa_id = filter_input(INPUT_POST, 'sub_empresa_id', FILTER_VALIDATE_INT) ?: null;

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role_id' => $role_id,
                    'sub_empresa_id' => $sub_empresa_id
                ]);

                if ($user) {
                    $_SESSION['user_id'] = $user->id;
                    header('Location: /dashboard');
                    exit;
                } else {
                    throw new \Exception('Error al registrar el usuario');
                }
            } else {
                $this->render('auth/register.twig', ['errors' => $validator->getErrors()]);
            }
        } catch (\Exception $e) {
            $this->render('auth/register.twig', ['error' => $e->getMessage()]);
        }
    }

    public function showForgotPasswordForm()
    {
        $this->render('auth/forgot_password.twig');
    }

    public function sendResetLink()
    {
        try {
            CsrfHelper::verifyToken($_POST['csrf_token']);

            $validator = new Validator();
            $rules = ['email' => 'required|email'];

            if ($validator->validate($_POST, $rules)) {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $user = User::where('email', $email)->first();
             if ($user) {
                    $token = bin2hex(random_bytes(50));
                    $user->password_reset_token = $token;
                    $user->password_reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    $user->save();

                    $resetLink = $_ENV['APP_URL'] . "/reset-password?token=$token";

                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = $_ENV['SMTP_HOST'];
                        $mail->SMTPAuth = true;
                        $mail->Username = $_ENV['SMTP_USERNAME'];
                        $mail->Password = $_ENV['SMTP_PASSWORD'];
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = $_ENV['SMTP_PORT'];

                        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
                        $mail->addAddress($user->email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Recuperación de contraseña';
                        $mail->Body    = "Por favor, haga clic en el siguiente enlace para restablecer su contraseña: <a href='$resetLink'>Restablecer contraseña</a>";

                        $mail->send();
                        $this->render('auth/reset_link_sent.twig');
                    } catch (Exception $e) {
                        throw new \Exception('No se pudo enviar el correo. Error: ' . $mail->ErrorInfo);
                    }
                } else {
                    throw new \Exception('No se encontró un usuario con ese correo electrónico.');
                }
            } else {
                $this->render('auth/forgot_password.twig', ['errors' => $validator->getErrors()]);
            }
        } catch (\Exception $e) {
            $this->render('auth/forgot_password.twig', ['error' => $e->getMessage()]);
        }
    }

    public function showResetPasswordForm()
    {
        $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
        $user = User::where('password_reset_token', $token)
                    ->where('password_reset_expires', '>', date('Y-m-d H:i:s'))
                    ->first();

        if ($user) {
            $this->render('auth/reset_password.twig', ['token' => $token]);
        } else {
            $this->render('auth/reset_password.twig', ['error' => 'Token inválido o expirado.']);
        }
    }

    public function resetPassword()
    {
        try {
            CsrfHelper::verifyToken($_POST['csrf_token']);

            $validator = new Validator();
            $rules = [
                'token' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required'
            ];

            if ($validator->validate($_POST, $rules)) {
                $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
                $password = $_POST['password'];
                $password_confirmation = $_POST['password_confirmation'];

                $user = User::where('password_reset_token', $token)
                            ->where('password_reset_expires', '>', date('Y-m-d H:i:s'))
                            ->first();

                if ($user) {
                    if ($password === $password_confirmation) {
                        $user->password = password_hash($password, PASSWORD_DEFAULT);
                        $user->password_reset_token = null;
                        $user->password_reset_expires = null;
                        $user->save();

                        $this->render('auth/password_reset_success.twig');
                    } else {
                        throw new \Exception('Las contraseñas no coinciden.');
                    }
                } else {
                    throw new \Exception('Token inválido o expirado.');
                }
            } else {
                $this->render('auth/reset_password.twig', ['errors' => $validator->getErrors(), 'token' => $_POST['token']]);
            }
        } catch (\Exception $e) {
            $this->render('auth/reset_password.twig', ['error' => $e->getMessage(), 'token' => $_POST['token']]);
        }
    }
}