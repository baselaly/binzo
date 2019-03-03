<?php

class Validator
{

    public function sanitize_input($input, $type)
    {
        switch ($type) {
            case "email":
                $input = filter_var(trim($input), FILTER_SANITIZE_EMAIL);
                break;
            case "string":
                $input = filter_var(trim($input), FILTER_SANITIZE_STRING);
                break;
            case "integer":
                $input = filter_var(trim($input), FILTER_SANITIZE_NUMBER_INT);
                break;
            case "url":
                $input = filter_var(trim($input), FILTER_SANITIZE_URL);
                break;
            default:
                $input = strip_tags(trim($input));
                break;
        }
        return $input;
    }

    public function checkRequired($data, $required)
    {
        $errors = [];
        foreach ($required as $field) {
            if (empty($data[$field]) || !isset($data[$field])) {
                $errors[] = $field . ' field is required';
            }
        }
        return $errors;
    }

    public function email_validate($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public function validate_integer($integer)
    {
        if (filter_var($integer, FILTER_VALIDATE_INT)) {
            return true;
        }
        return false;
    }

    public function numberBetween($value, $range)
    {
        $min = $range[0];
        $max = $range[1];

        if ((preg_match("/^[-+]?[0-9]*\.?[0-9]+$/", $value)) && $value >= $min && $value <= $max) {
            return true;
        }
        return false;
    }

    public function max_lenght($input, $lenght)
    {
        return strlen($input) <= $lenght ? true : false;
    }

    public function min_lenght($input, $lenght)
    {
        return strlen($input) >= $lenght ? true : false;
    }

    public function validate_image($image, $size, $acceptable)
    {
        $acceptable = $acceptable;
        $size = $size; //4mb
        $errors = [];

        if ($image['error'] != 0) {
            $errors[] = 'error in uploaded image.';
        }

        if ((!in_array($image['type'], $acceptable)) && (!empty($image["type"]))) {
            $errors[] = 'Invalid image type. Only GIF, JPG and PNG types are accepted.';
        }

        if ($image['size'] > $size) {
            $errors[] = 'Invalid image type. Only JPG and PNG types are accepted.';
        }
        return $errors;
    }

    public function password_validate($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $errors = [];

        if (!$uppercase) {
            $errors[] = 'password must contain atleast 1 uppercase';
        }
        if (!$lowercase) {
            $errors[] = 'password must contain atleast 1 lowercase';
        }
        if (!$number) {
            $errors[] = 'password must contain atleast 1 number';
        }

        return $errors;
    }

}
