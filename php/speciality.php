<?php

class Speciality extends DataBase {
    public function add ($name, $number, $departmentHead)
    {
        try {
            $count = $this->findCount($name, $number);

            if ($count[0]['count(*)'] <= 0) {
                $this::query(
                    "INSERT INTO `specialty`(`name`, `spec_number`, `department_head`) VALUES (:name, :number, :department_head)",
                    [
                        ':name'             => $name,
                        ':number'           => $number,
                        ':department_head'  => $departmentHead
                    ]
                );
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function change ($id, $name, $number, $departmentHead)
    {
        try {
            $count = $this->findCount($name, $number, $id);

            if ($count[0]['count(*)'] == 0) {
                $sql = $this::query("UPDATE `specialty` SET `name` = :name, `spec_number` = :number, `department_head` = :department_head WHERE `id` = :id",
                    [
                        ':name'             => $name,
                        ':number'           => $number,
                        ':department_head'  => $departmentHead,
                        ':id'               => $id
                    ]
                ); return 1;
            } return 0;
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function delete ($id) {
        try {
            $count = $this->findCount($id);

            if (count[0]['count(*)'] > 0) {
                $sql = $this::query(
                    "DELETE FROM `specialty` WHERE `specialty`.`id` = :specialty",
                    [
                        ':specialty'  => $id
                    ]
                );
                return 1;
            }
            return 0;

            } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }

    public function findCount($name, $number, $id = null): array
    {
        try {
            if (!$name && !$number)
                return $this::query("SELECT count(*) FROM `specialty` where `specialty`.`id` = :specialty",
                    [
                        ':specialty'  => $id

                    ]
                );
            return $this::query("SELECT count(*) FROM `specialty` WHERE (`name` = :name OR `spec_number` = :number) AND `id` != :id",
            [
                ':name'     => $name,
                ':number'   => $number,
                ':id'       => $id
            ]
        );
        } catch (Exception $e) {
            echo "Помилка виконання! Зверніться до Адміністратора сайту!";
        }
    }
}