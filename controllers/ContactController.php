<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Contact.php';

class ContactController
{
    private Contact $model;

    public function __construct()
    {
        $this->model = new Contact();
    }

    public function index(): void
    {
        $contacts = $this->model->getAll();
        $this->jsonResponse($contacts);
    }

    public function show(int $id): void
    {
        $contact = $this->model->getById($id);

        if (!$contact) {
            $this->jsonResponse(['message' => 'Contact not found'], 404);
            return;
        }

        $this->jsonResponse($contact);
    }

    public function store(): void
    {
        $payload = $this->getJsonBody();

        $errors = $this->validateContact($payload);

        if (!empty($errors)) {
            $this->jsonResponse(['errors' => $errors], 422);
            return;
        }

        try {
            $created = $this->model->create($payload);
            $this->jsonResponse($created, 201);
        } catch (Throwable $e) {
            $this->jsonResponse([
                'message' => 'Error creating contact',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): void
    {
        $contact = $this->model->getById($id);

        if (!$contact) {
            $this->jsonResponse(['message' => 'Contact not found'], 404);
            return;
        }

        $deleted = $this->model->delete($id);

        if ($deleted) {
            $this->jsonResponse(['message' => 'Contact deleted']);
        } else {
            $this->jsonResponse(['message' => 'Error deleting contact'], 500);
        }
    }

    private function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function getJsonBody(): array
    {
        $raw  = file_get_contents('php://input');
        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }

    private function validateContact(array &$data): array
    {
        $errors = [];

        $data['first_name'] = trim($data['first_name'] ?? '');
        $data['last_name']  = trim($data['last_name'] ?? '');
        $data['email']      = trim($data['email'] ?? '');

        if ($data['first_name'] === '') {
            $errors['first_name'] = 'First name is required';
        }

        if ($data['last_name'] === '') {
            $errors['last_name'] = 'Last name is required';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is not valid';
        }

        if (isset($data['phones'])) {
            if (!is_array($data['phones'])) {
                $errors['phones'] = 'Phones must be an array of strings';
            } else {
                $cleanPhones = [];

                foreach ($data['phones'] as $index => $phone) {
                    $phone = trim((string) $phone);

                    if ($phone === '') {
                        $errors["phones.$index"] = 'Phone number cannot be empty';
                        continue;
                    }

                    if (!preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
                        $errors["phones.$index"] = 'Phone number format is invalid';
                        continue;
                    }

                    $cleanPhones[] = $phone;
                }

                $data['phones'] = $cleanPhones;
            }
        } else {
            $data['phones'] = [];
        }

        return $errors;
    }
}
