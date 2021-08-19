<?php


use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBuilder
{
    private $hashedPassword = false;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param string $login {@see User::$login}.
     * @param string $email {@see User::$email}.
     * @param string $clearPassword The clear text password.
     * @return User A user object.
     */
    public function build(string $login, string $email, string $clearPassword): User
    {
        $user = (new User())->setLogin($login)->setEmail($email);
        $user->setPassword(
            $this->hashedPassword
                ? $this->passwordHasher->hashPassword($user, $clearPassword)
                : $clearPassword
        );
        return $user;
    }


    /**
     * Indicate whether or not to hash the password
     * for user.
     * @param bool $hashed
     * @return self
     */
    public function withHashedPassword(bool $hashed = true): self
    {
        $this->hashedPassword = $hashed;
        return $this;
    }
}