<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Contact
{
    private PDO $conn;

    public function __construct()
    {
        $database   = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "
            SELECT c.id,
                   c.first_name,
                   c.last_name,
                   c.email,
                   GROUP_CONCAT(p.phone_number SEPARATOR ',') AS phones
            FROM contacts c
            LEFT JOIN phones p ON p.contact_id = c.id
            GROUP BY c.id
            ORDER BY c.id DESC
        ";

        $stmt = $this->conn->query($sql);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['phones'] = $row['phones']
                ? explode(',', $row['phones'])
                : [];
        }

        return $rows;
    }

    public function getById(int $id): ?array
    {
        $sql = "
            SELECT c.id,
                   c.first_name,
                   c.last_name,
                   c.email,
                   GROUP_CONCAT(p.phone_number SEPARATOR ',') AS phones
            FROM contacts c
            LEFT JOIN phones p ON p.contact_id = c.id
            WHERE c.id = :id
            GROUP BY c.id
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $row['phones'] = $row['phones']
            ? explode(',', $row['phones'])
            : [];

        return $row;
    }

    /**
     * Crear contacto + teléfonos (en transacción).
     * $data = ['first_name', 'last_name', 'email', 'phones' => []]
     */
    public function create(array $data): array
    {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO contacts (first_name, last_name, email)
                    VALUES (:first_name, :last_name, :email)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
            ]);

            $contactId = (int) $this->conn->lastInsertId();

            if (!empty($data['phones']) && is_array($data['phones'])) {
                $phoneSql  = "INSERT INTO phones (contact_id, phone_number)
                              VALUES (:contact_id, :phone_number)";
                $phoneStmt = $this->conn->prepare($phoneSql);

                foreach ($data['phones'] as $phone) {
                    $phoneStmt->execute([
                        'contact_id'   => $contactId,
                        'phone_number' => $phone,
                    ]);
                }
            }

            $this->conn->commit();

            $created = $this->getById($contactId);

            return $created ?? [];
        } catch (Throwable $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Eliminar contacto (phones se borran por ON DELETE CASCADE).
     */
    public function delete(int $id): bool
    {
        $sql  = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
