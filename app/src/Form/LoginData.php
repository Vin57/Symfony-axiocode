<?php


namespace App\Form;


use Symfony\Component\Validator\Constraints as Assert;

class LoginData
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private ?string $login = null;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private ?string $password = null;

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}